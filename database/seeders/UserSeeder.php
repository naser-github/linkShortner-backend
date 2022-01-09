<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Super Operator
        $user = new User();
        $user->name = 'Super Operator';
        $user->email = 'superoperator@daraz.com';
        $user->password = Hash::make('123456');
        $user->user_status = 'active';
        $user->save();

        $user_profile = new UserProfile();
        $user_profile->fk_user_id = $user->id;
        $user_profile->user_phone = null;
        $user_profile->save();

        // Operator
        $user = new User();
        $user->name = 'Operator';
        $user->email = 'operator1@daraz.com';
        $user->password = Hash::make('123456');
        $user->user_status = 'active';
        $user->save();

        $user_profile = new UserProfile();
        $user_profile->fk_user_id = $user->id;
        $user_profile->user_phone = null;
        $user_profile->save();
    }
}
