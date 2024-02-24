<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rental>
 */
class RentalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $radomBook = Book::inRandomOrder()->first();

        return [
            'customer_id' => Customer::inRandomOrder()->first()->id,
            'book_id' => $radomBook->id,
            'is_returned' => $radomBook->status === 'available' ? true : false,
        ];
    }
}
