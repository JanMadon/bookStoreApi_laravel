<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Rental;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RentalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Rental::truncate();

        $maxNumberOfRentals = 100;

        for($i = 0; $i < $maxNumberOfRentals; $i++){
            $rental = Rental::factory()->create();

            if(!$rental->is_returned){
                $book = Book::find($rental->book_id);
                $book->status = 'rentaled';
                $book->save();
            }
        }
    }
}
