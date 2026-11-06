<?php

namespace App\Services\Dashboard;

use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Time-series helpers for dashboard charts: fill every day/month in the range, including empty ones,
 * and give localized labels ("6 نوفمبر", "Nov 2026").
 */
class Trend
{
    /** @return array{labels: string[], values: float[]} counts (or sums) per day for the last $days days */
    public static function daily(Builder $query, string $dateColumn, int $days = 14, ?string $sumColumn = null): array
    {
        $from = now()->subDays($days - 1)->startOfDay();
        $rows = (clone $query)
            ->where($dateColumn, '>=', $from)
            ->where($dateColumn, '<=', now()->endOfDay())
            ->selectRaw("DATE({$dateColumn}) as d, " . ($sumColumn ? "SUM({$sumColumn})" : 'COUNT(*)') . ' as v')
            ->groupBy('d')
            ->pluck('v', 'd');

        $labels = $values = [];
        foreach (CarbonPeriod::create($from, now()->startOfDay()) as $day) {
            $labels[] = $day->translatedFormat('j M');
            $values[] = round((float) ($rows[$day->toDateString()] ?? 0), 2);
        }

        return ['labels' => $labels, 'values' => $values];
    }

    /**
     * Sums per month for the last $months months, one series per group value.
     *
     * @param  array<int|string, string>  $groups  group column value => series name
     * @return array{labels: string[], series: array<int, array{name: string, data: float[]}>}
     */
    public static function monthlySum(Builder $query, string $dateColumn, string $sumColumn, string $groupColumn, array $groups, int $months = 6): array
    {
        $from = now()->startOfMonth()->subMonths($months - 1);
        $rows = (clone $query)
            ->where($dateColumn, '>=', $from)
            ->selectRaw("DATE_FORMAT({$dateColumn}, '%Y-%m') as m, {$groupColumn} as g, SUM({$sumColumn}) as v")
            ->groupBy('m', 'g')
            ->get();

        $labels = $keys = [];
        for ($i = 0; $i < $months; $i++) {
            $month = $from->copy()->addMonths($i);
            $keys[] = $month->format('Y-m');
            $labels[] = $month->translatedFormat('M Y');
        }

        $series = [];
        foreach ($groups as $value => $name) {
            $series[] = [
                'name' => $name,
                'data' => array_map(fn ($k) => round((float) optional($rows->first(fn ($r) => $r->m === $k && (string) $r->g === (string) $value))->v, 2), $keys),
            ];
        }

        return ['labels' => $labels, 'series' => $series];
    }

    public static function money(float|int|null $amount): string
    {
        $currency = config('hospital.currency');

        return number_format((float) $amount, 2) . ($currency ? ' ' . $currency : '');
    }

    /** Percentage change between two numbers, or null when there is nothing to compare with. */
    public static function change(float|int $current, float|int $previous): ?int
    {
        return $previous > 0 ? (int) round(($current - $previous) / $previous * 100) : null;
    }
}
