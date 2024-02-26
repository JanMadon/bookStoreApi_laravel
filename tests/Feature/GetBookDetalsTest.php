<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Customer;
use App\Models\Rental;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class GetBookDetalsTest extends TestCase
{
    use DatabaseMigrations;

    public function test_record_not_found(): void
    {
        $response = $this->get('/api/v1/books/1');

        $response->assertStatus(404);
    }

    public function test_record_get_book(): void
    {
        $book = Book::factory()->create();
        $customer = Customer::factory()->create();
        Rental::create([
            'book_id' => $book->id,
            'customer_id' =>  $customer->id,
            'is_returned' => false
        ]);

        $response = $this->get("/api/v1/books/$book->id");

        $response->assertStatus(200)
            ->assertExactJson(
                [
                    'data' => [
                        'id' => $book->id,
                        'title' => $book->title,
                        'author' => $book->author,
                        'year' => (int) $book->year,
                        'status' => $book->status,
                        'borrowedBy' => [
                            'id' => $customer->id,
                            'name' => $customer->name,
                            'surname' => $customer->surname
                            
                        ],
                    ]

                ]
            );
    }
}
