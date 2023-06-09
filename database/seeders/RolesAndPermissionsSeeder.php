<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions

        //user
        Permission::create(['name' => 'update-user']);
        Permission::create(['name' => 'create-user']);
        Permission::create(['name' => 'view-user']);

        //category
        Permission::create(['name' => 'update-category']);
        Permission::create(['name' => 'create-category']);
        Permission::create(['name' => 'view-category']);

        //product
        Permission::create(['name' => 'update-product']);
        Permission::create(['name' => 'create-product']);
        Permission::create(['name' => 'view-product']);

        //purchase
        Permission::create(['name' => 'create-purchase']);
        Permission::create(['name' => 'view-purchase']);
        
        //supplier
        Permission::create(['name' => 'update-supplier']);
        Permission::create(['name' => 'create-supplier']);
        Permission::create(['name' => 'view-supplier']);

        //reports
        Permission::create(['name' => 'view-reports']);

        //cart
        Permission::create(['name' => 'view-cart']);

        //sale
        Permission::create(['name' => 'view-sale']);

        // create roles and assign created permissions
        $role = Role::create(['name' => 'Administrador']);
        $role->givePermissionTo(Permission::all());

        $role = Role::create(['name' => 'Encargado'])
            ->givePermissionTo(['update-category', 'create-category', 'view-category',
                                'update-product', 'view-product', 'create-product', 
                                'create-purchase', 'view-purchase',
                                'update-supplier', 'create-supplier', 'view-supplier',
                                'view-reports', 'view-sale', 'view-cart']);

        $role = Role::create(['name' => 'Ventas'])
            ->givePermissionTo(['view-category',
                                'view-cart',
                                'view-product', 
                                'view-purchase',
                                'view-supplier',
                                'view-sale']);
    }
}
