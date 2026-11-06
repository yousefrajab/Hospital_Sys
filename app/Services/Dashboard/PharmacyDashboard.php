<?php

namespace App\Services\Dashboard;

use App\Models\Medication;
use App\Models\PharmacyStock;
use App\Models\Prescription;

/** Home pages for pharmacy staff (dispensing) and the pharmacy manager (stock). */
class PharmacyDashboard
{
    /** Prescriptions the pharmacy still has to hand out. */
    public const TO_DISPENSE = [
        Prescription::STATUS_NEW,
        Prescription::STATUS_APPROVED,
        Prescription::STATUS_PROCESSING,
        Prescription::STATUS_PARTIALLY_DISPENSED,
    ];

    public static function employee(): array
    {
        $count = fn (array|string $status) => Prescription::whereIn('status', (array) $status)->count();

        return [
            'kpis' => [
                'to_dispense' => $count(self::TO_DISPENSE),
                'ready' => $count(Prescription::STATUS_READY_FOR_PICKUP),
                'dispensed_today' => Prescription::whereDate('dispensed_at', today())->count(),
                'on_hold' => $count(Prescription::STATUS_ON_HOLD),
                'low_stock' => self::stockLevels()['low'],
            ],
            'trend' => Trend::daily(Prescription::whereNotNull('dispensed_at'), 'dispensed_at', 14),
            'queue' => Prescription::whereIn('status', self::TO_DISPENSE)
                ->with(['patient', 'doctor'])->withCount('items')
                ->orderBy('prescription_date')->take(8)->get(),
        ];
    }

    public static function manager(): array
    {
        $levels = self::stockLevels();
        $warningDays = (int) config('pharmacy.stock_expiry_warning_days', 90);

        return [
            'warningDays' => $warningDays,
            'kpis' => [
                'medications' => $levels['active'],
                'low' => $levels['low'],
                'out' => $levels['out'],
                'expiring' => PharmacyStock::where('quantity_on_hand', '>', 0)
                    ->whereBetween('expiry_date', [today(), today()->addDays($warningDays)])
                    ->distinct('medication_id')->count('medication_id'),
                'stock_value' => (float) PharmacyStock::where('quantity_on_hand', '>', 0)->whereDate('expiry_date', '>', today())
                    ->selectRaw('SUM(quantity_on_hand * COALESCE(cost_price_per_unit, 0)) as v')->value('v'),
            ],
            'stockChart' => [
                'labels' => [trans('ui.dash.in_stock'), trans('ui.dash.low_stock'), trans('ui.dash.out_of_stock')],
                'values' => [$levels['ok'], $levels['low'] - $levels['out'], $levels['out']],
            ],
            'dispensedTrend' => Trend::daily(Prescription::whereNotNull('dispensed_at'), 'dispensed_at', 30),
            'lowList' => $levels['lowList'],
            'expiringList' => PharmacyStock::with('medication')
                ->where('quantity_on_hand', '>', 0)
                ->whereBetween('expiry_date', [today(), today()->addDays($warningDays)])
                ->orderBy('expiry_date')->take(6)->get(),
            'recentStock' => PharmacyStock::with('medication')->latest('received_date')->take(5)->get(),
        ];
    }

    /** Active medications by usable (unexpired) stock against their minimum level. */
    private static function stockLevels(): array
    {
        $meds = Medication::where('status', 1)
            ->withSum(['stocks as on_hand' => fn ($q) => $q->where('quantity_on_hand', '>', 0)->whereDate('expiry_date', '>', today())], 'quantity_on_hand')
            ->get();
        $low = $meds->filter(fn ($m) => (int) $m->on_hand <= (int) $m->minimum_stock_level);

        return [
            'active' => $meds->count(),
            'low' => $low->count(), // includes out of stock
            'out' => $meds->filter(fn ($m) => (int) $m->on_hand === 0)->count(),
            'ok' => $meds->count() - $low->count(),
            'lowList' => $low->sortBy(fn ($m) => (int) $m->on_hand)->take(6)->values(),
        ];
    }
}
