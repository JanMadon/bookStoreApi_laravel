<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class DeleteCustomerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_delete_customer(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->delete("/api/v1/customers/$customer->id");
        $response->assertStatus(204);

        $response = $this->get("/api/v1/customers/$customer->id");
        $response->assertStatus(404);
    }

}
