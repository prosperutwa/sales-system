<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BiovetTechCompanySettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('biovet_tech_company_settings')->insert([
            'company_name'        => 'biovet technology limited',
            'company_address'     => '123 Tech Street, Dar es Salaam, Tanzania',
            'company_phone'       => '+255784670288',
            'company_email'       => 'info@biovettech.co.tz',
            'company_logo'        => 'logo_biotech.png', // assume image stored in public storage
            'company_tin'         => '123456789',
            'default_currency'    => 'TZS',
            'tax_percentage'      => 18.00,
            'invoice_footer_note' => 'Thank you for your business!',
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);
    }
}
