<?php

namespace App\Search;

use App\Models\Book;
use App\Models\Customer;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BookSearch
{

    public function search(Request $request)
    {
        $query = $request->input('q');
        $type = $request->input('type');

        if ($type === 'book') {
            $results = $this->searchByBooks($query);
        } elseif ($type === 'customer') {
            $results = $this->searchByCustomers($query);
        } else {
            $results = [];
        }

        return $results;
    }

    private function searchByBooks($query)
    {
        return Book::where('title', 'like', "%$query%")
            ->orWhere('author', 'like', "%$query%");
    }

    private function searchByCustomers($query)
    {
        return Book::whereHas('rentals', function ($rentals) use ($query) {
            $rentals->where('is_returned', false)
                ->whereHas('customer', function ($subquery) use ($query) {
                    $subquery->where('name', 'like', "%$query%")
                        ->orWhere('surname', 'like', "%$query%");
                });
        });
    }
}
