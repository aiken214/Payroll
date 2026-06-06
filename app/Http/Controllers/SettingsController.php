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
            'dti_permit_number' => 'nullable|string|max:255',
            'company_logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'watermark_logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'working_days_per_month' => 'required|integer|min:20|max:31',
            'minimum_daily_wage' => 'required|numeric|min:0',
            'sick_leave_per_year' => 'required|integer|min:0|max:30',
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

        if ($request->hasFile('company_logo')) {
            $path = $request->file('company_logo')->store('logos', 'public');
            $validated['company_logo'] = $path;
        }

        if ($request->hasFile('watermark_logo')) {
            $path = $request->file('watermark_logo')->store('logos', 'public');
            $validated['watermark_logo'] = $path;
        }

        unset($validated['company_logo_remove'], $validated['watermark_logo_remove']);
        $settings->update($validated);

        return redirect()->route('settings.index')->with('success', 'Settings updated.');
    }

    public function contributions(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $availableYears = SssContribution::selectRaw('DISTINCT year')->orderByDesc('year')->pluck('year');

        $sssTable = SssContribution::where('year', $year)->orderBy('range_from')->get();
        $phicTable = PhicContribution::where('year', $year)->orderBy('range_from')->get();
        $wtaxTable = WtaxTable::orderBy('frequency')->orderBy('min_range')->get();

        return view('settings.contributions', compact('sssTable', 'phicTable', 'wtaxTable', 'year', 'availableYears'));
    }
}
