<?php

namespace Tests\Feature;

use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UpdateCustomerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_update_not_found_customer(): void
    {
        $response = $this->put('/api/v1/books/999');

        $response->assertStatus(405);
    }

    public function test_update_customer_with_correct_data(): void
    {
        $customer = Customer::create([
            'name' => 'Johny',
            'surname' => 'Bravo',
        ]);
        $costomerId = $customer->id; 

        $updateCustomer = [
            'name' => 'new Johny',
            'surname' => 'new Bravo',
        ];

        $response = $this->put("/api/v1/customers/$costomerId", $updateCustomer);

        $customer = new CustomerResource( Customer::find($costomerId) );

        $response->assertStatus(200)
            ->assertExactJson(['data' => $customer]);
    }
}
