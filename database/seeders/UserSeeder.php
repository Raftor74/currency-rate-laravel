<?php

namespace Database\Seeders;

use App\Models\User;
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
        User::factory()->create([
            'name' => config('credentials.default_user.username'),
            'email' => config('credentials.default_user.email'),
            'password' => Hash::make(config('credentials.default_user.password')),
        ]);
    }
}
