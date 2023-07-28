<div class="worklist-wrapper">
  <h2>{{ $category->description }}</h2>
  <div>
    @foreach($projects as $project)
      @include('pages.worklist.article', ['project' => $project])
    @endforeach
  </div>
</div>