<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BiovetTechAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $userId = DB::table('biovet_tech_users')->insertGetId([
            'first_name'  => 'Admin',
            'last_name'   => 'User',
            'email'       => 'admin@biovettech.co.tz',
            'phonenumber' => '0755000000',
            'status'      => 'active',
            'role'        => 'admin',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // Create auth record
        DB::table('biovet_tech_auth')->insert([
            'user_id'   => $userId,
            'username'  => 'admin.admin',
            'password'  => Hash::make('admin'), // hashed password
            'status'    => 1,
            'created_at'=> now(),
            'updated_at'=> now(),
        ]);
    }
}
