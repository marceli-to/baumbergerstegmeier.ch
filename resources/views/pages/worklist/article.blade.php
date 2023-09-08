<article class="worklist-item md:span-6 lg:span-3">
  @if ($project->feature)
    <a href="{{ route('page.project.show', ['state' => $project->states->first()->slug, 'category' => $project->categories->first()->slug, 'project' => $project->slug]) }}" 
      title="{{ $project->title }}">
      <figure>
        @if ($project->coverImage)
          <x-image 
            :classes="'aspect-ratio-3/2'"
            :maxSizes="[0 => 1000]" 
            :image="$project->coverImage" 
            width="1000" 
            height="667">
          </x-image>
        @else
          <picture class="aspect-ratio-3/2" style="background-color: #d1d1d1">
            <div class="placeholder"></div>
          </picture>
        @endif
      </figure>
    </a>
  @else
  <figure>
    @if ($project->coverImage)
      <x-image 
        :classes="'aspect-ratio-3/2'"
        :maxSizes="[0 => 1000]" 
        :image="$project->coverImage" 
        width="1000" 
        height="667">
      </x-image>
    @else
      <picture class="aspect-ratio-3/2" style="background-color: #d1d1d1">
        <div class="placeholder"></div>
      </picture>
    @endif
  </figure>
  @endif
  <div>
    <h3>
      @if ($project->feature)
        <a href="{{ route('page.project.show', ['state' => $project->states->first()->slug, 'category' => $project->categories->first()->slug, 'project' => $project->slug]) }}" 
          title="{{ $project->title }}"
          class="flex">
          {{-- <span class="inline-block">
            {{ $project->title }}<x-icons.arrow-right-up class="inline-block ml-12x" />
          </span> --}}

          <span class="inline-block">
            {!! AppHelper::projectTitle($project->title) !!}
          </span>

        </a>
      @else
        {{ $project->title }}
      @endif
    </h3>
    <p>
      @if ($project->location) {{ $project->location }}<br>@endif
      {{ $project->type }}
    </p>
  </div>
</article>

