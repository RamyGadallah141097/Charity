<?php

namespace Database\Seeders;

use App\Models\Media;
use Illuminate\Database\Seeder;

class MediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Media::insert([
            [
                'name' => 'Borrower Document 1',
                'path' => 'uploads/borrowers/document1.pdf',
                'type' => 0, // 0 = Borrower
                'borrower_id' => null, // Ensure this ID exists in borrowers table
                'guarantor_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Guarantor Photo 1',
                'path' => 'uploads/guarantors/photo1.jpg',
                'type' => 1, // 1 = Guarantor
                'borrower_id' => null,
                'guarantor_id' => null, // Ensure this ID exists in guarantors table
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
