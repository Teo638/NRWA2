<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view students', 'create students', 'edit students', 'delete students',
            'view departments', 'create departments', 'edit departments', 'delete departments',
            'view faculty', 'create faculty', 'edit faculty', 'delete faculty',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        $viewerRole = Role::firstOrCreate(['name' => 'viewer']);
        $viewerRole->syncPermissions(['view students', 'view departments', 'view faculty']);

        $editorRole = Role::firstOrCreate(['name' => 'editor']);
        $editorRole->syncPermissions([
            'view students', 'create students',
            'view departments', 'create departments',
            'view faculty', 'create faculty'
        ]);

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        if (!$adminUser->hasRole('admin')) {
            $adminUser->assignRole($adminRole);
        }


        $editorUser = User::firstOrCreate(
            ['email' => 'editor@example.com'],
            [
                'name' => 'Editor User',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        if (!$editorUser->hasRole('editor')) {
            $editorUser->assignRole($editorRole);
        }

        $viewerUser = User::firstOrCreate(
            ['email' => 'viewer@example.com'],
            [
                'name' => 'Viewer User',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        if (!$viewerUser->hasRole('viewer')) {
            $viewerUser->assignRole($viewerRole);
        }

        $basicAuthUser = User::where('email', 'testbasic@example.com')->first();
        if ($basicAuthUser) {
            if (!$basicAuthUser->hasRole('admin')) {
                 $basicAuthUser->assignRole($adminRole);
            }
        } else {
             User::create(
                [
                    'name' => 'Test Korisnik',
                    'email' => 'testbasic@example.com',
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                ]
            )->assignRole($adminRole);
        }
    }
}