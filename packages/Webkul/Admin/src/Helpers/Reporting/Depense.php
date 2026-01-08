<?php

namespace Webkul\Admin\Helpers\Reporting;

use Illuminate\Support\Facades\DB;
use Webkul\Depense\Repositories\DepenseRepository;

class Depense extends AbstractReporting
{
    /**
     * Create a helper instance.
     *
     * @return void
     */
    public function __construct(protected DepenseRepository $depenseRepository)
    {
        parent::__construct();
    }

    /**
     * Retrieves monthly expense result with progress.
     */
    public function getMonthlyExpenseResult(): array
    {
        $currentMonth = now()->startOfMonth();
        $currentMonthEnd = now()->endOfMonth();
        $previousMonthStart = now()->copy()->subMonth()->startOfMonth();
        $previousMonthEnd = now()->copy()->subMonth()->endOfMonth();

        $currentTotal = $this->getTotalExpenses($currentMonth, $currentMonthEnd);
        $previousTotal = $this->getTotalExpenses($previousMonthStart, $previousMonthEnd);

        return [
            'current' => $currentTotal,
            'previous' => $previousTotal,
            'formatted_total' => core()->formatBasePrice($currentTotal),
            'progress' => $this->getPercentageChange($previousTotal, $currentTotal),
        ];
    }

    /**
     * Retrieves real-time treasury tracking.
     */
    public function getTreasuryTracking(): array
    {
        $today = now()->startOfDay();
        $todayEnd = now()->endOfDay();
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();

        $todayExpenses = $this->getTotalExpenses($today, $todayEnd);
        $monthExpenses = $this->getTotalExpenses($monthStart, $monthEnd);
        $totalExpenses = $this->getTotalExpenses($this->startDate, $this->endDate);

        // Calculate average per day this month
        $daysInMonth = now()->daysInMonth;
        $daysPassed = now()->day;
        $averagePerDay = $daysPassed > 0 ? $monthExpenses / $daysPassed : 0;

        return [
            'today' => [
                'amount' => $todayExpenses,
                'formatted' => core()->formatBasePrice($todayExpenses),
            ],
            'month' => [
                'amount' => $monthExpenses,
                'formatted' => core()->formatBasePrice($monthExpenses),
            ],
            'period' => [
                'amount' => $totalExpenses,
                'formatted' => core()->formatBasePrice($totalExpenses),
            ],
            'average_per_day' => [
                'amount' => $averagePerDay,
                'formatted' => core()->formatBasePrice($averagePerDay),
            ],
        ];
    }

    /**
     * Get monthly expenses over time for chart.
     */
    public function getMonthlyExpensesOverTime(): array
    {
        $period = $this->determinePeriod('month');
        
        return $this->getOverTimeStats(
            $this->startDate, 
            $this->endDate, 
            'depenses.montant', 
            'depenses.date', 
            'month'
        );
    }

    /**
     * Retrieves total expenses by date range
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     */
    public function getTotalExpenses($startDate, $endDate): float
    {
        $total = $this->depenseRepository
            ->resetModel()
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('montant');

        return (float) ($total ?? 0);
    }

    /**
     * Get over time stats for expenses
     */
    protected function getOverTimeStats($startDate, $endDate, $valueColumn, $dateColumn, $period): array
    {
        $timeIntervals = $this->getTimeInterval($startDate, $endDate, $dateColumn, $period);
        
        $stats = $this->depenseRepository
            ->resetModel()
            ->select(
                DB::raw($timeIntervals['group_column'] . ' as period'),
                DB::raw('SUM(montant) as total')
            )
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->pluck('total', 'period')
            ->toArray();

        $data = [];
        foreach ($timeIntervals['intervals'] as $interval) {
            $filter = is_array($interval['filter']) ? $interval['filter'] : [$interval['filter']];
            $total = 0;
            
            foreach ($filter as $f) {
                $total += $stats[$f] ?? 0;
            }

            $data[] = [
                'label' => ($interval['start'] ?? '') . ' - ' . ($interval['end'] ?? ''),
                'total' => (float) $total,
            ];
        }

        return $data;
    }

    /**
     * Determine period automatically or use provided period.
     */
    protected function determinePeriod($period): string
    {
        if ($period !== 'auto') {
            return $period;
        }

        $diffInMonths = $this->startDate->diffInMonths($this->endDate);

        if ($diffInMonths >= 12) {
            return 'year';
        } elseif ($diffInMonths >= 1) {
            return 'month';
        } else {
            return 'day';
        }
    }
}
