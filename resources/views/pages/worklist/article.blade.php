@if ($project->coverImage)
  <article class="worklist-item md:span-6 lg:span-3">
    <figure>
      <x-image 
        :classes="'aspect-ratio-3/2'"
        :maxSizes="[0 => 1000]" 
        :image="$project->coverImage" 
        width="1000" 
        height="667">
      </x-image>
    </figure>
    <div>
      <h2>{{ $project->title }}</h2>
      <p>
        @if ($project->location) {{ $project->location }}<br>@endif
        {{ $project->type }}
      </p>
    </div>
  </article>
@endif