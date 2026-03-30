<?php

namespace Webkul\Admin\Helpers\Reporting;

use Webkul\Quote\Repositories\QuoteRepository;

class Facture extends AbstractReporting
{
    /**
     * Create a helper instance.
     *
     * @return void
     */
    public function __construct(protected QuoteRepository $quoteRepository)
    {
        parent::__construct();
    }

    /**
     * Retrieves total factures count and progress.
     */
    public function getTotalFacturesProgress(): array
    {
        return [
            'previous' => $previous = $this->getTotalFactures($this->lastStartDate, $this->lastEndDate),
            'current'  => $current = $this->getTotalFactures($this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves total factures amount and progress.
     */
    public function getTotalFacturesAmountProgress(): array
    {
        $previous = $this->getTotalFacturesAmount($this->lastStartDate, $this->lastEndDate);
        $current = $this->getTotalFacturesAmount($this->startDate, $this->endDate);

        return [
            'previous'        => $previous,
            'current'         => $current,
            'formatted_total' => core()->formatBasePrice($current),
            'progress'        => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves total acomptes and progress.
     */
    public function getTotalAcomptesProgress(): array
    {
        $previous = $this->getTotalAcomptes($this->lastStartDate, $this->lastEndDate);
        $current = $this->getTotalAcomptes($this->startDate, $this->endDate);

        return [
            'previous'        => $previous,
            'current'         => $current,
            'formatted_total' => core()->formatBasePrice($current),
            'progress'        => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves total restant à payer and progress.
     */
    public function getTotalRestantProgress(): array
    {
        $previous = $this->getTotalRestant($this->lastStartDate, $this->lastEndDate);
        $current = $this->getTotalRestant($this->startDate, $this->endDate);

        return [
            'previous'        => $previous,
            'current'         => $current,
            'formatted_total' => core()->formatBasePrice($current),
            'progress'        => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves total factures count by date range.
     */
    public function getTotalFactures($startDate, $endDate): int
    {
        $query = $this->quoteRepository
            ->resetModel()
            ->where('type', 'facture')
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($this->userId) {
            $query->where('user_id', $this->userId);
        }

        return $query->count();
    }

    /**
     * Retrieves total factures amount (grand_total) by date range.
     */
    public function getTotalFacturesAmount($startDate, $endDate): float
    {
        $query = $this->quoteRepository
            ->resetModel()
            ->where('type', 'facture')
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($this->userId) {
            $query->where('user_id', $this->userId);
        }

        return (float) ($query->sum('grand_total') ?? 0);
    }

    /**
     * Retrieves total acomptes by date range.
     */
    public function getTotalAcomptes($startDate, $endDate): float
    {
        $query = $this->quoteRepository
            ->resetModel()
            ->where('type', 'facture')
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($this->userId) {
            $query->where('user_id', $this->userId);
        }

        return (float) ($query->sum('acompte') ?? 0);
    }

    /**
     * Retrieves total restant à payer (grand_total - acompte) by date range.
     */
    public function getTotalRestant($startDate, $endDate): float
    {
        $query = $this->quoteRepository
            ->resetModel()
            ->where('type', 'facture')
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($this->userId) {
            $query->where('user_id', $this->userId);
        }

        $totals = $query->selectRaw('COALESCE(SUM(grand_total), 0) as total, COALESCE(SUM(acompte), 0) as acomptes')
            ->first();

        return (float) ($totals->total - $totals->acomptes);
    }
}
