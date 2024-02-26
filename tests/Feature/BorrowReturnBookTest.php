<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Customer;
use App\Models\Rental;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BorrowReturnBookTest extends TestCase
{
   
    use DatabaseMigrations;

    public function test_borrow_book(): void
    {
        $book = Book::factory()->create(['status'=>'available']);
        $customer = Customer::factory()->create();

        $body = [
            'customerId' => $customer->id,
            'action' => 'borrow'
        ];

        $response = $this->patch("/api/v1/books/$book->id", $body);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'message'=>'Book borrowed successfully'
            ]);
    }

    public function test_return_book(): void
    {
        $book = Book::factory()->create(['status'=>'rentaled']);
        $customer = Customer::factory()->create();
        Rental::create([
            'book_id' => $book->id,
            'customer_id' =>  $customer->id,
            'is_returned' => false
        ]);

        $body = [
            'customerId' => $customer->id,
            'action' => 'return'
        ];

        $response = $this->patch("/api/v1/books/$book->id", $body);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'message'=>'Book returned successfully'
            ]);
    }
}
