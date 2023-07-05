<div class="worklist-wrapper">
  @foreach($projects as $project)
    @include('pages.worklist.article', ['project' => $project])
  @endforeach
</div>