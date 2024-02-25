<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Customer;
use App\Models\Rental;
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
        do {
            $randomBook = Book::inRandomOrder()->first();
            $isUnavailable = Rental::where('book_id', $randomBook->id)
                ->where('is_returned', false)
                ->exists();
        } while ($isUnavailable);

        return [
            'customer_id' => Customer::inRandomOrder()->first()->id,
            'book_id' => $randomBook->id,
            'is_returned' => fake()->boolean(75),
        ];
    }
}
