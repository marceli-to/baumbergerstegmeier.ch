<div class="worklist-wrapper">
  <h2 class="md:flex md:items-center">
    <a 
      href="{{ route('page.worklist.state', ['state' => $state->slug]) }}" 
      title="Filter entfernen"
      class="flex items-center">
      <x-icons.cross-xs class="mr-8x md:hidden" />
      <x-icons.cross-sm class="mr-12x hidden !md:block" />
      {{ $category->description }}
    </a>
    <a 
      href="{{ route('page.worklist.category', ['category' => $category->slug]) }}" 
      title="Filter entfernen"
      class="flex items-center md:ml-32x">
      <x-icons.cross-xs class="mr-8x md:hidden" />
      <x-icons.cross-sm class="mr-12x hidden !md:block" />
      {{ $state->description }}
    </a>
  </h2>
  <div>
    @foreach($projects as $project)
      @include('pages.worklist.article', ['project' => $project])
    @endforeach
  </div>
</div>