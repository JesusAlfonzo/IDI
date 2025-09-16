<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos

        // Purchase
        Permission::create(['name' => 'create purchase']);
        Permission::create(['name' => 'edit purchase']);

        // Inventory
        Permission::create(['name' => 'edit inventory']);

        // Admin
        Permission::create(['name' => 'manage roles']);

        // Crear roles y asignar permisos

        // Rol y permisos para Usuarios Nuevos (Basic)

        $role = Role::create(['name' => 'basic']);

        // Rol y permisos para Administracion (Purchase)
        $role = Role::create(['name' => 'purchase']);
        $role->givePermissionTo('create purchase');
        $role->givePermissionTo('edit purchase');

        // Rol y permisos para Cordinardores (Admin)
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());

        // Rol y permisos para Inventario (Inventory)
        $role = Role::create(['name' => 'inventory']);
        $role->givePermissionTo('edit inventory');

        // Rol Administrador default
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
            ]
        );
        $adminUser->assignRole('admin');
    }
}
