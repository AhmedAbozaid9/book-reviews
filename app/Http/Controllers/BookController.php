<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $title = $request->input('title');
        $filter = $request->input('filter', '');
        $books = Book::when(
            $title,
            fn($query) => $query->title($title)
        );

        $books = match ($filter) {

            'popular-last-month' => $books->popularLastMonth(),
            'popular-last-6months' => $books->popularLast6Months(),
            'highest-rated-last-month' => $books->highestRatedLastMonth(),
            'highest-rated-last6-months' => $books->highestRatedLast6Months(),
            default => $books->latest()->withAvgRating()->withReviewsCount(),
        };

        // $books = $books->get();
        $cachedKey = "books-{$title}-{$filter}";
        $books = Cache::remember($cachedKey, 3600, fn() => $books->paginate(10));
        return view('books.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $id)
    {
        $cacheKey = "book-{$id}";
        $book = Cache::remember($cacheKey, 3600, fn() => Book::with(["reviews" => fn($query) => $query->latest()])->withAvgRating()->findOrFail($id));
        return view('books.show', ['book' => $book]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        //
    }
}
