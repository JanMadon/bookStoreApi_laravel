<?php

namespace App\Http\Resources;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'status' => $this->status,
            'returnedBy1' => $this->rentals->load('customer'),
            'returnedBy' => $this->returnedBy(
                $this->rentals->where('is_returned', false)->first()
            ),
        ];
    }

    private function returnedBy($rentals)
    {

        if (!$rentals) {
            return [];
        }

        $customer = Customer::find($rentals->customer_id);
        return new CustomerResource($customer);
    }
}
