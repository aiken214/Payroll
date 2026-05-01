<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\SssContribution;
use App\Models\PhicContribution;
use App\Models\WtaxTable;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = CompanySetting::firstOrCreate([], ['company_name' => 'Company']);
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_address' => 'nullable|string|max:500',
            'working_days_per_month' => 'required|integer|min:20|max:31',
            'minimum_daily_wage' => 'required|numeric|min:0',
            'ot_rate_regular' => 'required|numeric',
            'ot_rate_rest_day' => 'required|numeric',
            'ot_rate_rest_day_ot' => 'required|numeric',
            'ot_rate_special_holiday' => 'required|numeric',
            'ot_rate_special_holiday_ot' => 'required|numeric',
            'ot_rate_legal_holiday' => 'required|numeric',
            'ot_rate_legal_holiday_ot' => 'required|numeric',
            'night_diff_rate' => 'required|numeric',
            'hdmf_employee_rate' => 'required|numeric',
            'hdmf_employer_rate' => 'required|numeric',
            'hdmf_max_compensation' => 'required|numeric',
        ]);

        $settings = CompanySetting::first();
        $settings->update($validated);

        return redirect()->route('settings.index')->with('success', 'Settings updated.');
    }

    public function contributions()
    {
        $sssTable = SssContribution::where('year', date('Y'))->orderBy('range_from')->get();
        $phicTable = PhicContribution::where('year', date('Y'))->orderBy('range_from')->get();
        $wtaxTable = WtaxTable::orderBy('frequency')->orderBy('min_range')->get();

        return view('settings.contributions', compact('sssTable', 'phicTable', 'wtaxTable'));
    }
}
