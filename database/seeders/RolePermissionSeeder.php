<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'view employees', 'create employees', 'edit employees', 'delete employees',
            'view payroll', 'create payroll', 'process payroll', 'approve payroll',
            'view loans', 'create loans', 'edit loans', 'delete loans',
            'view reports', 'print payroll', 'print payslip',
            'manage settings', 'manage users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->syncPermissions($permissions);

        $hrManager = Role::firstOrCreate(['name' => 'HR Manager']);
        $hrManager->syncPermissions([
            'view employees', 'create employees', 'edit employees',
            'view payroll', 'create payroll', 'process payroll', 'approve payroll',
            'view loans', 'create loans', 'edit loans',
            'view reports', 'print payroll', 'print payslip',
        ]);

        $payrollOfficer = Role::firstOrCreate(['name' => 'Payroll Officer']);
        $payrollOfficer->syncPermissions([
            'view employees',
            'view payroll', 'create payroll', 'process payroll',
            'view loans', 'create loans', 'edit loans',
            'view reports', 'print payroll', 'print payslip',
        ]);

        $viewer = Role::firstOrCreate(['name' => 'Viewer']);
        $viewer->syncPermissions([
            'view employees', 'view payroll', 'view loans', 'view reports',
        ]);
    }
}
