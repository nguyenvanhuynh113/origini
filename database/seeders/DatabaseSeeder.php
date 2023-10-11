<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $user1 = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com'
        ]);
        User::factory()->create([
            'name' => 'staff 1',
            'email' => 'Staff@gmail.com'
        ]);
        $role = Role::create(['name' => 'Super admin']);
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'User']);
        Role::create(['name' => 'Staff']);
        $user1->assignRole($role);
        $permission1 = Permission::create(['name' => 'View user']);
        $permission2 = Permission::create(['name' => 'Edit user']);
        $permission3 = Permission::create(['name' => 'Delete user']);
        $permission4 = Permission::create(['name' => 'Create user']);
        $permission5 = Permission::create(['name' => 'View role']);
        $permission6 = Permission::create(['name' => 'Edit role']);
        $permission7 = Permission::create(['name' => 'Delete role']);
        $permission8 = Permission::create(['name' => 'Create role']);
        $permission9 = Permission::create(['name' => 'View permission']);
        $permission10 = Permission::create(['name' => 'Edit permission']);
        $permission11 = Permission::create(['name' => 'Delete permission']);
        $permission12 = Permission::create(['name' => 'Create permission']);
        $permission13 = Permission::create(['name' => 'View category']);
        $permission14 = Permission::create(['name' => 'Edit category']);
        $permission15 = Permission::create(['name' => 'Delete category']);
        $permission16 = Permission::create(['name' => 'Create category']);
        $permission17 = Permission::create(['name' => 'View product']);
        $permission18 = Permission::create(['name' => 'Edit product']);
        $permission19 = Permission::create(['name' => 'Delete product']);
        $permission20 = Permission::create(['name' => 'Create product']);
        $role->syncPermissions([$permission1, $permission2, $permission3, $permission4, $permission5, $permission6,
            $permission7, $permission8, $permission9, $permission10, $permission11, $permission12, $permission13
            , $permission14, $permission15, $permission16, $permission17, $permission18, $permission19, $permission20]);

        Payment::create([
            'payment_type' => 'Thanh toán online',
            'payment_name' => 'VNPAY'
        ]);
        Payment::create([
            'payment_type' => 'Phương thức khác',
            'payment_name' => 'Thanh toán khi nhận hàng'
        ]);


    }
}
