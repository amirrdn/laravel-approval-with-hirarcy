<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'view dashboard',
            'manage users',
            'manage roles',
            'manage posts',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $adminRole = Role::firstOrCreate(['name' => 'Administrator', 'guard_name' => 'web']);
        $userRole  = Role::firstOrCreate(['name' => 'User', 'guard_name' => 'web']);
        
        $adminRole->syncPermissions($permissions);
        $userRole->syncPermissions(['view dashboard']);

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'id'       => (string) Str::uuid(),
                'name'     => 'Admin',
                'password' => Hash::make('123456'),
            ]
        );

        if (!$adminUser->hasRole('Administrator')) {
            $adminUser->assignRole($adminRole);
        }

        $managerUser = User::firstOrCreate(
            ['email' => 'manager@gmail.com'],
            [
                'id'       => (string) Str::uuid(),
                'name'     => 'Manager',
                'password' => Hash::make('123456'),
            ]
        );


        $regularUser = User::firstOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'id'       => (string) Str::uuid(),
                'name'     => 'User',
                'password' => Hash::make('123456'),
            ]
        );

        $regularUser = User::firstOrCreate(
            ['email' => 'yudi@gmail.com'],
            [
                'id'       => (string) Str::uuid(),
                'name'     => 'Yudi',
                'password' => Hash::make('123456'),
            ]
        );

        $dummyUsers = [
            ['email' => 'budi@gmail.com', 'name' => 'Budi'],
            ['email' => 'dian@gmail.com', 'name' => 'Dian'],
            ['email' => 'roni@gmail.com', 'name' => 'Roni'],
            ['email' => 'siti@gmail.com', 'name' => 'Siti'],
            ['email' => 'andi@gmail.com', 'name' => 'Andi'],
        ];

        foreach ($dummyUsers as $dummy) {
            $user = User::firstOrCreate(
                ['email' => $dummy['email']],
                [
                    'id'       => (string) Str::uuid(),
                    'name'     => $dummy['name'],
                    'password' => Hash::make('123456'),
                ]
            );

            if (!$user->hasRole('User')) {
                $user->assignRole($userRole);
            }
        }

        if (!$regularUser->hasRole('User')) {
            $regularUser->assignRole($userRole);
        }
    }
}
