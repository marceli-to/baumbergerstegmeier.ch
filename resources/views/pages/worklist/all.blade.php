<div class="worklist-wrapper">
  <div>
    @foreach($projects as $project)
      @include('pages.worklist.article', ['project' => $project])
    @endforeach
  </div>
</div>