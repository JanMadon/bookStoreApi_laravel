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

    protected $withBorrowedBy;

    public function __construct($resource, $withBorrowedBy = false)
    {
        parent::__construct($resource);
        $this->withBorrowedBy = $withBorrowedBy;
    }

    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'year' => $this->year,
            'status' => $this->status,
        ];

        if($this->withBorrowedBy){
            $data['borrowedBy'] = $this->borrowedBy(
                $this->rentals->where('is_returned', false)->first()
            );
        };

        return $data;
    }

    private function borrowedBy($rentals)
    {

        if (!$rentals) {
            return [];
        }

        $customer = Customer::find($rentals->customer_id);
        return new CustomerResource($customer);
    }
}
