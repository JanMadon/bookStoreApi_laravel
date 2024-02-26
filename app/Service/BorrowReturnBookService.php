<?php

namespace App\Service;

use App\Models\Book;
use App\Models\Rental;
use Exception;
use Illuminate\Support\Facades\Log;
use stdClass;

class BorrowReturnBookService
{

    public function borrowBook(Book $book, int $customerId): bool
    {
        if ($this->isBookAvailable($book)) {
            try {
                Rental::create([
                    'customer_id' => $customerId,
                    'book_id' => $book->id,
                    'is_returned' => false
                ]);

                $book->update(['status' => 'rentaled']);
            } catch (Exception $e) {
                Log::error($e->getMessage());
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    public function returnBook(Book $book, int $customerId): bool
    {
        if (!$this->isBookAvailable($book)) {
            try {
                $rental = Rental::where('customer_id', $customerId)
                    ->where('book_id', $book->id)
                    ->where('is_returned', false)
                    ->firstOrfail();

                $rental->update(['is_returned' => true]);
                $book->update(['status' => 'available']);

            } catch (Exception $e) {
                Log::error($e->getMessage());
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    private function isBookAvailable(Book $book): bool
    {
        $lastRental = $book->rentals()->latest()->orderBy('id', 'desc')->first();

        if ($lastRental === null) {
            $lastRental = new stdClass();
            $lastRental->is_returned = true;
        }

        $status = $book->status;

        if ($lastRental->is_returned && $status === 'available') {
            return true;
        } elseif (!$lastRental->is_returned && $status === 'rentaled') {
            return false;
        } else {
            Log::error("status of the book unknown, id: $book->id");
            // TODO administrator notification
            return false;
        }
    }
}
