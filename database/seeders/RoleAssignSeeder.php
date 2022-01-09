<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleAssignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // superOperator
        $user = User::findOrFail(1);
        $role = Role::where('name', 'superOperator')->first();
        $user->assignRole($role);

        // operator
        $user = User::findOrFail(2);
        $role = Role::where('name', 'operator')->first();
        $user->assignRole($role);
    }
}
