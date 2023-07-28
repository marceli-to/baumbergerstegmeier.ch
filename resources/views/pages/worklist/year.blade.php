<div class="worklist-wrapper">
  @foreach($projects as $year => $project_year)
    <h2>{{ $year }}</h2>
    <div>
      @foreach($project_year as $project)
        @include('pages.worklist.article', ['project' => $project])
      @endforeach
    </div>
  @endforeach
</div>