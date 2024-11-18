@extends("layouts.app")

@section("content")
    <h1 class="mb-10 text-2xl">Books</h1>
    <form method="GET" action="{{ route('books.index') }}" class="mb-4 flex items-center space-x-2">
      <input type="hidden" name="filter" value="{{ request('filter') }}" />
      <input  
        type="text" 
        name="title" 
        placeholder="search by title" 
        value="{{ request('title') }}" 
        class="input"
      />
      <button class="btn">Search</button>
      <a href="{{ route('books.index') }}" class="btn">clear</a>

    </form>

    <div class="filter-container mb-4 flex">
      @php
        $filters = [
          '' => "Latest",
          'popular_last_month' => 'Popular last month',
          'popular_last_6months' => 'Popular last 6 months',
          'highest_rated_last_month' => 'Highest rated last month',
          'highest_rated_last_6months' => 'Highest rated last 6 months',
        ]
      @endphp
      @foreach ($filters as $key => $label )
        <a 
        href="{{ route('books.index', [...request()->query(),'filter'=>$key]) }}" 
        class="{{ request('filter') === $key || (request('filter') === null && $key === '') ? 'filter-item-active' : 'filter-item' }}" data-filter="{{ $key }}">
          {{ $label }}
        </a>
      @endforeach
    </div>

    <ul>
        @forelse ( $books as $book )
        <li class="mb-4">
            <div class="book-item">
              <div
                class="flex flex-wrap items-center justify-between">
                <div class="w-full flex-grow sm:w-auto">
                  <a href="{{ route('books.show', $book) }}" class="book-title">{{ $book->title }}</a>
                  <span class="book-author">By {{ $book->author }}</span>
                </div>
                <div>
                  <div class="book-rating">
                    {{ number_format($book->reviews->avg('rating'), 1) }}
                  </div>
                  <div class="book-review-count">
                    out of {{$book->reviews_count}} reviews
                  </div>
                </div>
              </div>
            </div>
          </li>
        @empty
        <li class="mb-4">
          <div class="empty-book-item">
            <p class="empty-text">No books found</p>
            <a href="{{ route('books.index') }}" class="reset-link">Reset criteria</a>
          </div>
        </li

        @endforelse

        @if($books->count())
          <nav>{{$books->links()}}</nav>
        @endif
    </ul>
@endsection
