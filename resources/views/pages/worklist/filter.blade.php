<nav class="worklist-filter" data-worklist>
  <ul class="md:flex">
    <li>
      <a href="{{ route('page.worklist') }}" class="is-first {{ $filter == 'all' ? 'is-active' : '' }}" title="Alle" {{ $filter == 'all' ? 'data-btn-worklist-current' : '' }}>
        Alle
      </a>
    </li>
    <li>
      <a href="{{ route('page.worklist.year') }}" class="{{ $filter == 'year' ? 'is-active' : '' }}" title="Jahre" {{ $filter == 'year' ? 'data-btn-worklist-current' : '' }}>Jahr</a>
    </li>
    @if ($states)
      <li>
        <a href="javascript:;" data-btn-worklist-filter class="{{ $filter == 'state' || $filter == 'category-state' ? 'is-active' : '' }}" title="Status" {{ $filter == 'state' ? 'data-btn-worklist-current' : '' }}>Status</a>
        <ul class="hidden" data-worklist-items>
          @foreach($states as $state)
            <li>
              @if ($filter == 'category')
                <a href="{{ route('page.worklist.category.state', ['category' => $category->slug, 'state' => $state->slug]) }}" title="Status {{ $state->description }}">{{ $state->description }}</a>
              @else
              <a href="{{ route('page.worklist.state', ['state' => $state->slug]) }}" title="Status {{ $state->description }}">{{ $state->description }}</a>
              @endif
            </li>
          @endforeach
        </ul>
      </li>
    @endif
    @if ($categories)
      <li>
        <a href="javascript:;" data-btn-worklist-filter class="{{ $filter == 'category' || $filter == 'category-state' ? 'is-active' : '' }}" title="Programm" {{ $filter == 'category' ? 'data-btn-worklist-current' : '' }}>Programm</a>
        <ul class="hidden" data-worklist-items>
          @foreach($categories as $category)
            <li>
              @if ($filter == 'state')
                <a href="{{ route('page.worklist.category.state', ['category' => $category->slug, 'state' => $state->slug]) }}" title="Programm {{ $category->description }}">{{ $category->description }}</a>
              @else
                <a href="{{ route('page.worklist.category', ['category' => $category->slug]) }}" title="Programm {{ $category->description }}">{{ $category->description }}</a>
              @endif
            </li>
          @endforeach
        </ul>
      </li>
    @endif
    <li class="is-search">
      <a href="javascript:;" class="is-search !mb-0 flex justify-between items-start">
        <x-icons.search class="mr-7x lg:mr-10x" />
        <form action="{{ route('page.worklist')}}" method="GET">
          <input type="text" name="searchTerm" min="3" placeholder="Suche" value="{{ $searchTerm ?? '' }}" class="leading-none">
        </form>
      </a>
    </li>
  </ul>
</nav>