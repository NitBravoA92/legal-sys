<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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

        DB::table('users')->insert([
            'id' => 1,
            'role' => 'ADMINISTRATOR',
            'name' => 'test',
            'lastname' => 'test',
            'phone' => '+58 0000000000',
            'email' => 'test_user@mail.com',
            'password' => Hash::make('test-user12345%'),
            'status' => 'active',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('users')->insert([
            'id' => 2,
            'role' => 'CUSTOMER',
            'name' => 'customer-name',
            'lastname' => 'customer-lastname',
            'phone' => '+1 000 0000000',
            'email' => 'customer-test@mail.com',
            'password' => Hash::make('customer12345%'),
            'status' => 'active',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
