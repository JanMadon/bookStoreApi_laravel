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
    /**
     * @OA\Get(
     *     path="/api/v1/books",
     *     summary="Get a list of books with pagination data",
     *     tags={"Books"},
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         required=false,
     *         description="Search by book(title, author) or customer(name, surname)",
     *         @OA\Schema(type="string", enum={"book", "customer"}),
     *     ),
     *     @OA\Parameter(
     *         name="phrase",
     *         in="query",
     *         required=false,
     *         description="Search query",
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/v1/books/{bookId}",
     *     summary="Get a single book with customer who it have (if have)",
     *     tags={"Books"},
     *     @OA\Parameter(
     *         name="bookId",
     *         in="path",
     *         required=true,
     *         description="ID of the book",
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Customer not found"),
     * )
     */
    public function show(Book $book)
    {
        return new BookResource($book, true);
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/books/{bookId}",
     *     summary="Borrow or return book",
     *     tags={"Books"},
     *     @OA\Parameter(
     *         name="bookId",
     *         in="path",
     *         required=true,
     *         description="ID of the book",
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="JSON data representing customer and action(borrow/return)",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="customerId", type="string", example="1"),
     *             @OA\Property(property="action", type="string", example="borrow"), 
     *         ),
     *     ),
     *     
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=422, description="The book already has this status"),
     * )
     */
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

        return response()->json(['message' => 'Action not recognized'], 500);
    }
}
