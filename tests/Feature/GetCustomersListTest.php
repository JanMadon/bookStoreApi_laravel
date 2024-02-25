<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetCustomersListTest extends TestCase
{
    use DatabaseMigrations;

    public function test_get_customers_list(): void
    {
        Customer::factory()->count(50)->create();

        $response = $this->get('/api/v1/customers');
        $response->assertStatus(200)
            ->assertJsonCount(50, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'surname'
                    ]
                ]

            ]);
    }
}
