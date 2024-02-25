<?php

namespace App\Http\Resources;

use App\Models\Book;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    protected $withBookList;

    public function __construct($resource, $withBookList = false)
    {
        parent::__construct($resource);
        $this->withBookList = $withBookList;
    }

    public function toArray(Request $request): array
    {
        
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
        ];

        if($this->withBookList){
            info($this->withBookList);
            $data['bookList'] = $this->getBookList();
        };

        return $data;

    }

    public function getBookList()
    {
        $customerId = $this->id;
        $bookList = Book::whereHas('rentals.customer', function ($query) use ($customerId) {
            $query->where('id', $customerId);
        })->get();
        $this->withBookList = false;
        return new BookCollection($bookList);
    }
}
