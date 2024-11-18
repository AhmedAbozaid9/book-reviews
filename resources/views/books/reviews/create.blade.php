@extends("layouts.app")

@section("content")
  <h1 class="mb-10 text-2xl">Add Review for {{$book->title}}</h1>
  <form action="{{ route('books.reviews.store', $book) }}">
    @csrf
    <label for="review">Review</label>
    <textarea name="review" id="review" required class="input mb-4"></textarea>
    <label for="rating">Rating</label>
  </form>
@endsection
