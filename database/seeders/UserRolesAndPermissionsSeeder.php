<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRolesAndPermissionsSeeder extends Seeder
{
    protected string $guardName = 'sanctum';
    protected array $roleAndPermissions = [
        'amateur_blogger' => [
            'sanctum.article.create'
        ],

        'blogger' => [
            'sanctum.article.create',
            'sanctum.article.publish'
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->roleAndPermissions as $role => $permissions) {
            $role = Role::query()->firstOrCreate(['name' => $role, 'guard_name' => $this->guardName]);

            $permissionsToSeed = collect($permissions)->map(function ($permission) {
                return ['name' => $permission, 'guard_name' => $this->guardName];
            })->toArray();

            Permission::query()->insertOrIgnore($permissionsToSeed);

            $role->syncPermissions($permissions);
        }
    }
}
