<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()->create([
            'first_name' => 'Selim',
            'last_name' => 'Altayev',
            'username' => 'admin',
            'email' => 'hithere@gmail.com',
            'dob' => '2000-01-01',
            'license_accepted' => true,
        ]);
    }
}
