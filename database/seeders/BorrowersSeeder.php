<?php

namespace Database\Seeders;

use App\Models\Borrower;
use Illuminate\Database\Seeder;

class BorrowersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Borrower::insert([
            [
                'name' => 'John Doe',
                'phone' => 123456789,
                'nationalID' => 987654321,
                'address' => '123 Street, City',
                'job' => 'Engineer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Alice Smith',
                'phone' => 987654321,
                'nationalID' => 123456789,
                'address' => '456 Avenue, City',
                'job' => 'Teacher',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
