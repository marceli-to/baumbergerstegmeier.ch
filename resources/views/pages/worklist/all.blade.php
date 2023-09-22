<div class="worklist-wrapper">
  <div>
    @if ($projects->count() == 0)
      <p class="md:span-12 lg:span-12">Für deine Anfrage konnte leider kein Ergebnis gefunden werden.</p>
    @else
      @foreach($projects as $project)
        @include('pages.worklist.article', ['project' => $project])
      @endforeach
    @endif
  </div>
</div>