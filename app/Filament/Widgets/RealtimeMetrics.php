<?php

namespace App\Filament\Widgets;

use App\Models\ManagementSetting;
use App\Services\ManagerCalcService;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class RealtimeMetrics extends BaseWidget implements HasForms
{
    use InteractsWithForms;

    public ?string $from = null;

    public ?string $to = null;

    public ?float $expenseForecast = null;

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\DatePicker::make('from')
                ->label('From')
                ->default(now()->subDays(120)),
            Forms\Components\DatePicker::make('to')
                ->label('To')
                ->default(now()),
            Forms\Components\TextInput::make('expenseForecast')
                ->label('Expense Forecast (year)')
                ->numeric()
                ->default(fn () => ManagementSetting::query()->value('expense_forecast') ?? 0),
        ];
    }

    public function mount(): void
    {
        $this->form->fill([
            'from' => now()->subDays(120),
            'to' => now(),
            'expenseForecast' => ManagementSetting::query()->value('expense_forecast') ?? 0,
        ]);
    }

    protected function getStats(): array
    {
        $from = Carbon::parse($this->from ?? now()->subDays(120));
        $to = Carbon::parse($this->to ?? now());

        $svc = app(ManagerCalcService::class);
        $base = $svc->compute($from, $to);
        $cyc = $svc->cycles($base['pmre'], $base['pmrv'], $base['pmpf']);

        $minCash = $svc->minCash(
            $cyc['cash_cycle'],
            (float) ($this->expenseForecast ?? (ManagementSetting::query()->value('expense_forecast') ?? 0))
        );

        return [
            Stat::make('PMRE', number_format($base['pmre'], 2).' d')
                ->description(__('Avg. inventory days')),

            Stat::make('PMRV', number_format($base['pmrv'], 2).' d')
                ->description(__('Avg. receivables days')),

            Stat::make('PMPF', number_format($base['pmpf'], 2).' d')
                ->description(__('Avg. payables days')),

            Stat::make('Operating Cycle', number_format($cyc['operating_cycle'], 2).' d')
                ->label(__('Operating Cycle'))
                ->description('PMRE + PMRV'),

            Stat::make('Cash Cycle', number_format($cyc['cash_cycle'], 2).' d')
                ->label(__('Cash Cycle'))
                ->description('OC - PMPF'),

            Stat::make('Min. Cash', $minCash !== null ? 'R$ '.number_format($minCash, 2, ',', '.') : '—')
                ->label(__('Min. Cash'))
                ->description(__('Expense / (CCC/360)')),
        ];
    }

    protected int|string|array $columnSpan = 'full';
}
