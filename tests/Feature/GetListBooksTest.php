<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Customer;
use App\Models\Rental;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class GetListBooksTest extends TestCase
{
    use DatabaseMigrations;
    
    public function test_get_list_books_pagination(): void
    {
        Book::factory()->count(60)->create();
        $response = $this->get('/api/v1/books');
        $response->assertStatus(200)
            ->assertJsonCount(20, 'data');
    }

    public function test_get_list_books_with_customer_data(): void
    {
        $book = Book::factory()->create();
        $customer = Customer::factory()->create();
        Rental::create([
            'book_id' => $book->id,
            'customer_id' =>  $customer->id,
            'is_returned' => false
        ]);

        $response = $this->get('/api/v1/books');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'author',
                    'status',
                    'borrowedBy' => [
                        'id',
                        'name',
                        'surname'
                    ]
                ],
            ],
            'links',
            'meta',
        ]);
    }

    public function test_get_list_books_search_by_book_title(): void
    {
        $title = 'Second Book';
        Book::factory()->create(['title' => 'First Book']);
        Book::factory()->create(['title' => 'Second Book']);
        Book::factory()->create(['title' => 'Third Book']);

        $response = $this->get("/api/v1/books?type=book&q=$title");
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJson([
                'data' => [
                    [
                        'title' => $title,
                    ],
                ],
            ]);
    }

    public function test_get_list_books_search_by_book_author(): void
    {
        $author = 'Zbyszek';
        Book::factory()->create(['author' => 'Maniek']);
        Book::factory()->create(['author' => 'Zbyszek']);
        Book::factory()->create(['author' => 'Wladek']);

        $response = $this->get("/api/v1/books?type=book&q=$author");
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJson([
                'data' => [
                    [
                        'author' => $author,
                    ],
                ],
            ]);
    }

    public function test_get_list_books_search_by_customer_name(): void
    {
        $name = 'Zbyszek';
        $book1 = Book::factory()->create(['title' => 'First Book']);
        Book::factory()->create(['title' => 'Second Book']);
        Book::factory()->create(['title' => 'Third Book']);
        $customer = Customer::factory()->create(['name' => $name]);
        Rental::create([
            'book_id' => $book1->id,
            'customer_id' =>  $customer->id,
            'is_returned' => false
        ]);

        $response = $this->get("/api/v1/books?type=customer&q=$name");
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJson([
                'data' => [
                    [
                        'title' => 'First Book',
                    ],
                ],
            ]);
    }
}
