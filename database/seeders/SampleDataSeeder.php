<?php

namespace Database\Seeders;

use App\Models\CompanySetting;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        CompanySetting::firstOrCreate([], [
            'company_name' => 'SilverNetSolutions',
            'company_address' => 'Tagum City, Davao del Norte',
            'working_days_per_month' => 26,
            'minimum_daily_wage' => 610,
        ]);

        $admin = User::firstOrCreate(
            ['email' => 'admin@payroll.com'],
            ['name' => 'System Admin', 'password' => bcrypt('password')]
        );
        $admin->assignRole('Admin');

        $hr = User::firstOrCreate(
            ['email' => 'hr@payroll.com'],
            ['name' => 'HR Manager', 'password' => bcrypt('password')]
        );
        $hr->assignRole('HR Manager');

        $payroll = User::firstOrCreate(
            ['email' => 'payroll@payroll.com'],
            ['name' => 'Payroll Officer', 'password' => bcrypt('password')]
        );
        $payroll->assignRole('Payroll Officer');

        $operation = Department::firstOrCreate(['name' => 'Operation'], ['code' => 'OPS']);
        $admin_dept = Department::firstOrCreate(['name' => 'Administration'], ['code' => 'ADM']);
        $finance = Department::firstOrCreate(['name' => 'Finance'], ['code' => 'FIN']);

        $technician = Position::firstOrCreate(['title' => 'Technician']);
        $lineman = Position::firstOrCreate(['title' => 'Lineman']);
        $supervisor = Position::firstOrCreate(['title' => 'Supervisor']);
        $clerk = Position::firstOrCreate(['title' => 'Clerk']);
        $manager = Position::firstOrCreate(['title' => 'Manager']);

        $employees = [
            ['240201', 'Ron', 'Ibañez', 'Operation', 'Technician', 11000, '1100229944', '123456789012', '908765432112'],
            ['240502', 'Jumar', 'Angway', 'Operation', 'Lineman', 11000, '1100229945', '123456789013', '908765432113'],
            ['251103', 'Jumie', 'Famisaran', 'Operation', 'Lineman', 11000, '1100229946', '123456789014', '908765432114'],
            ['240304', 'Silver', 'Villacacan', 'Administration', 'Supervisor', 18000, '1100229947', '123456789015', '908765432115'],
            ['240405', 'Pedro', 'Juanito', 'Operation', 'Technician', 13000, '1100229948', '123456789016', '908765432116'],
            ['240506', 'Maria', 'Santos', 'Finance', 'Clerk', 12000, '1100229949', '123456789017', '908765432117'],
            ['240607', 'Jun Jun', 'Dela Cruz', 'Operation', 'Lineman', 11000, '1100229950', '123456789018', '908765432118'],
            ['240708', 'Pepito', 'Manaloto', 'Operation', 'Technician', 11500, '1100229951', '123456789019', '908765432119'],
            ['240809', 'Marites', 'Garcia', 'Administration', 'Clerk', 12500, '1100229952', '123456789020', '908765432120'],
            ['240910', 'Mengay', 'Reyes', 'Finance', 'Clerk', 12000, '1100229953', '123456789021', '908765432121'],
            ['241011', 'Cardo', 'Dalisay', 'Operation', 'Supervisor', 16000, '1100229954', '123456789022', '908765432122'],
            ['241112', 'Yen', 'Mendoza', 'Administration', 'Manager', 25000, '1100229955', '123456789023', '908765432123'],
        ];

        $departments = Department::all()->keyBy('name');
        $positions = Position::all()->keyBy('title');

        foreach ($employees as $emp) {
            $settings = CompanySetting::first();
            $isMinWage = ($emp[5] / $settings->working_days_per_month) <= $settings->minimum_daily_wage;

            Employee::firstOrCreate(
                ['employee_number' => $emp[0]],
                [
                    'first_name' => $emp[1],
                    'last_name' => $emp[2],
                    'email' => strtolower($emp[1]) . '.' . strtolower($emp[2]) . '@company.com',
                    'department_id' => $departments[$emp[3]]->id,
                    'position_id' => $positions[$emp[4]]->id,
                    'employment_status' => 'Permanent/Regular',
                    'monthly_salary' => $emp[5],
                    'salary_type' => 'CASH',
                    'sss_number' => $emp[6],
                    'phic_number' => $emp[7],
                    'hdmf_number' => $emp[8],
                    'is_minimum_wage_earner' => $isMinWage,
                    'is_active' => true,
                    'date_hired' => '2024-01-15',
                ]
            );
        }
    }
}
