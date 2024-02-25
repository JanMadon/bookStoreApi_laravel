<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BookCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    protected $withBorrowedBy;

    public function __construct($resource, $withBorrowedBy = false)
    {
        parent::__construct($resource);
        $this->withBorrowedBy = $withBorrowedBy;
    }

    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function ($customer) {
                return new BookResource($customer, $this->withBorrowedBy);
            }),
        ];
    }
}
