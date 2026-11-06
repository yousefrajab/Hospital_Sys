<?php

namespace App\Services\Dashboard;

use App\Models\Laboratorie;
use App\Models\Ray;

/**
 * Home page for radiology and laboratory staff. Both work the same way: doctors request a test
 * (case = 0), staff add the result (case = 1, employee_id = who did it).
 */
class DiagnosticsDashboard
{
    /** @param 'ray'|'lab' $kind */
    public static function build(string $kind, int $employeeId): array
    {
        $model = $kind === 'ray' ? Ray::class : Laboratorie::class;
        $q = fn () => $model::query();

        $kpis = [
            'pending' => $q()->where('case', 0)->count(),
            'done_today' => $q()->where('case', 1)->where('employee_id', $employeeId)->whereDate('updated_at', today())->count(),
            'done_month' => $q()->where('case', 1)->where('employee_id', $employeeId)->where('updated_at', '>=', now()->startOfMonth())->count(),
            'done_total' => $q()->where('case', 1)->count(),
        ];

        $requested = Trend::daily($q(), 'created_at', 14);
        $completed = Trend::daily($q()->where('case', 1), 'updated_at', 14);

        return [
            'kind' => $kind,
            'kpis' => $kpis,
            'trend' => [
                'labels' => $requested['labels'],
                'series' => [
                    ['name' => trans('ui.dash.requested'), 'data' => $requested['values']],
                    ['name' => trans('ui.dash.completed'), 'data' => $completed['values']],
                ],
            ],
            'queue' => $q()->where('case', 0)->with(['patient', 'doctor'])->oldest()->take(8)->get(),
            'recent' => $q()->where('case', 1)->where('employee_id', $employeeId)->with('patient')->latest('updated_at')->take(5)->get(),
            'routes' => $kind === 'ray'
                ? ['queue' => 'ray_employee.invoices_ray_employee.index', 'process' => 'ray_employee.invoices_ray_employee.edit', 'done' => 'ray_employee.completed_invoices', 'view' => 'ray_employee.view_rays']
                : ['queue' => 'laboratorie_employee.invoices_laboratorie_employee.index', 'process' => 'laboratorie_employee.invoices_laboratorie_employee.edit', 'done' => 'laboratorie_employee.completed_invoicess', 'view' => 'laboratorie_employee.view_laboratories'],
        ];
    }
}
