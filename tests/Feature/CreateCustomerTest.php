<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateCustomerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_create_customer_with_correct_data(): void
    {
        $customer = [
            'name' => 'Johny',
            'surname' => 'Bravo',
        ];
        $response = $this->post('/api/v1/customers', $customer);
        $customer['id'] = 1;

        $response->assertStatus(201)
            ->assertExactJson(['data' => $customer]);
    }

    public function test_create_customer_with_incorrect_data(): void
    {
        $customer = [
            'name' => '1',
            'surname' => '...',
        ];
        $response = $this->post('/api/v1/customers', $customer);

        $response->assertStatus(422);
    }
}
