<?php

namespace App\Services;

use App\Models\CompanySetting;
use App\Models\PhicContribution;
use App\Models\SssContribution;
use App\Models\WtaxTable;

class ContributionService
{
    public function computeSss(float $monthlySalary, int $year = null): float
    {
        $year = $year ?? date('Y');

        $bracket = SssContribution::where('year', $year)
            ->where('range_from', '<=', $monthlySalary)
            ->where('range_to', '>=', $monthlySalary)
            ->first();

        if (!$bracket) {
            $bracket = SssContribution::where('year', $year)
                ->orderBy('range_to', 'desc')
                ->first();
        }

        return $bracket ? $bracket->total_ee : 0;
    }

    public function computeSssEmployer(float $monthlySalary, int $year = null): float
    {
        $year = $year ?? date('Y');

        $bracket = SssContribution::where('year', $year)
            ->where('range_from', '<=', $monthlySalary)
            ->where('range_to', '>=', $monthlySalary)
            ->first();

        if (!$bracket) {
            $bracket = SssContribution::where('year', $year)
                ->orderBy('range_to', 'desc')
                ->first();
        }

        return $bracket ? $bracket->total_er : 0;
    }

    public function computePhic(float $monthlySalary, int $year = null): float
    {
        $year = $year ?? date('Y');

        $brackets = PhicContribution::where('year', $year)->orderBy('range_from')->get();

        foreach ($brackets as $bracket) {
            if ($monthlySalary >= $bracket->range_from && $monthlySalary <= $bracket->range_to) {
                if ($bracket->premium_rate && $bracket->premium_rate < 1) {
                    return $monthlySalary * $bracket->premium_rate;
                }
                return $bracket->monthly_premium;
            }
        }

        $lastBracket = $brackets->last();
        if ($lastBracket && $monthlySalary >= $lastBracket->range_from) {
            return $lastBracket->monthly_premium;
        }

        return 0;
    }

    public function computeHdmf(float $monthlySalary): float
    {
        $settings = CompanySetting::first();
        $rate = $settings ? $settings->hdmf_employee_rate : 0.02;
        $maxComp = $settings ? $settings->hdmf_max_compensation : 5000;

        $basis = min($monthlySalary, $maxComp);
        $contribution = $basis * $rate;

        return min($contribution, 200);
    }

    public function computeWtax(float $taxableIncome, string $frequency = 'semi_monthly'): float
    {
        $brackets = WtaxTable::where('frequency', $frequency)
            ->orderBy('min_range')
            ->get();

        $tax = 0;

        foreach ($brackets as $bracket) {
            $maxRange = $bracket->max_range ?? PHP_FLOAT_MAX;

            if ($taxableIncome >= $bracket->min_range && $taxableIncome <= $maxRange) {
                $excess = $taxableIncome - $bracket->excess_over;
                $tax = $bracket->base_tax + ($excess * $bracket->tax_rate);
                break;
            }
        }

        return max(0, $tax);
    }
}
