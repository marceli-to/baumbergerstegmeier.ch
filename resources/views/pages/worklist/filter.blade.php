<nav class="worklist-filter" data-worklist>
  <ul class="lg:flex">
    <li>
      <a href="javascript:;" class="is-first is-active" title="Alle" data-btn-worklist-current>
        Alle
      </a>
    </li>
    <li>
      <a href="javascript:;" title="Jahre">Jahr</a>
    </li>
    @if ($states)
      <li>
        <a href="javascript:;" data-btn-worklist-filter>Status</a>
        <ul class="hidden" data-worklist-items>
          @foreach($states as $state)
            <li>
              <a href="javascript:;" title="Status {{ $state->description }}">{{ $state->description }}</a>
            </li>
          @endforeach
        </ul>
      </li>
    @endif
    @if ($categories)
    <li>
      <a href="javascript:;" data-btn-worklist-filter>Programm</a>
      <ul class="hidden" data-worklist-items>
        @foreach($categories as $category)
          <li>
            <a href="javascript:;" title="Programm {{ $category->description }}">{{ $category->description }}</a>
          </li>
        @endforeach
      </ul>
    </li>
    @endif
    <li class="is-search">
      <a href="javascript:;" class="is-search !mb-0 flex justify-between items-center lg:items-start">
        <x-icons.search class="mr-7x lg:mr-10x" />
        Suche
      </a>
    </li>
  </ul>
</nav>