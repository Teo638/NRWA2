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

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

       

        $viewerRole = Role::create(['name' => 'viewer']);
        $viewerRole->givePermissionTo(['view students', 'view departments', 'view faculty']);

        
        $editorRole = Role::create(['name' => 'editor']);
        $editorRole->givePermissionTo([
            'view students', 'create students',
            'view departments', 'create departments',
            'view faculty', 'create faculty'
        ]);

       
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all()); 

        
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        )->assignRole($adminRole);

        User::firstOrCreate(
            ['email' => 'editor@example.com'],
            [
                'name' => 'Editor User',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        )->assignRole($editorRole);

        User::firstOrCreate(
            ['email' => 'viewer@example.com'],
            [
                'name' => 'Viewer User',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        )->assignRole($viewerRole);

        
        $basicAuthUser = User::where('email', 'testbasic@example.com')->first();
        if ($basicAuthUser) {
            $basicAuthUser->assignRole($adminRole); 
        } else {
            
             User::firstOrCreate(
                ['email' => 'testbasic@example.com'],
                [
                    'name' => 'Test Korisnik',
                    'password' => Hash::make('password123'), 
                    'email_verified_at' => now(),
                ]
            )->assignRole($adminRole);
        }
    }
}