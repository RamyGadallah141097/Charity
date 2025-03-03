<?php

namespace Database\Seeders;

use App\Models\Guarantor;
use Illuminate\Database\Seeder;

class guarantorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Guarantor::insert([
            [
                'borrower_id' => 1, // Ensure user with ID 1 exists in the users table
                'name' => 'Michael Johnson',
                'phone' => 1122334455,
                'nationalID' => 998877665,
                'address' => '789 Road, City',
                'job' => 'Doctor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'borrower_id' => 2, // Ensure user with ID 2 exists in the users table
                'name' => 'Emily Davis',
                'phone' => 5566778899,
                'nationalID' => 112233445,
                'address' => '101 Street, City',
                'job' => 'Lawyer',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
