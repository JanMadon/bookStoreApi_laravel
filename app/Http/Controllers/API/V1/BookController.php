<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Search\BookSearch;
use App\Service\BorrowReturnBookService;
use Illuminate\Http\Request;


class BookController extends Controller
{

    public function index(Request $request, BookSearch $booksQuery)
    {
        $type = $request->query('type');
        $query = $request->query('q');

        if ($query && $type) {
            $booksQuery = $booksQuery->search($request);
        } else {
            $booksQuery = Book::with('rentals');
        }
        $books = $booksQuery->paginate(20);

        return new BookCollection($books, true);
    }

    public function show(Book $book)
    {
        return new BookResource($book, true);
    }

    public function updateStatus(UpdateBookRequest $request, Book $book, BorrowReturnBookService $bookService)
    {

        if ($request->action === 'borrow') {
            return $bookService->borrowBook($book, $request->customerId) ?
                response()->json(['message' => 'Book borrowed successfully']) :
                response()->json(['message' => 'Book is not available for borrowing'], 422);

        } elseif ($request->action === 'return') {
            return $bookService->returnBook($book, $request->customerId) ?
                response()->json(['message' => 'Book returned successfully']) :
                response()->json(['message' => 'Book is not currently rented'], 422);
        }

        return response()->json(['message' => 'Action not recognized'], 400);
    }
}
