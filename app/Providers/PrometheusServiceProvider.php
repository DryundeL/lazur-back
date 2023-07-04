<?php

namespace App\Providers;

use App\Models\Employee;
use App\Models\Student;
use Illuminate\Support\ServiceProvider;
use Spatie\Prometheus\Facades\Prometheus;

class PrometheusServiceProvider extends ServiceProvider
{
    public function register()
    {
        Prometheus::addGauge('Users count')
            ->value(fn() => Student::count() + Employee::count());

        Prometheus::addGauge('Employees and students count')
            ->label('type')
            ->value(function () {
                return [
                    [Employee::count(), ['employees']],
                    [Student::count(), ['students']],
                ];
            });
    }
}
