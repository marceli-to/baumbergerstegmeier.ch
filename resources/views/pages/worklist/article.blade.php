<article class="worklist-item md:span-6 lg:span-3">
  @if ($project->feature)
    <a href="{{ route('page.project.show', ['state' => $project->states->first()->slug, 'category' => $project->categories->first()->slug, 'project' => $project->slug]) }}" 
      title="{{ $project->title }}">
      <figure>
        @if ($project->workListImage)
          <x-image 
            :classes="''"
            :maxSizes="[0 => 1000]" 
            :image="$project->workListImage" 
            width="1000" 
            height="667"
            ratio="3x2">
          </x-image>
        @else
          <picture style="background-color: #d1d1d1">
            <img src="/assets/img/placeholder-3x2.png" width="300" height="200">
          </picture>
        @endif
      </figure>
    </a>
  @else
  <figure>
    @if ($project->workListImage)
      <x-image 
        :classes="''"
        :maxSizes="[0 => 1000]" 
        :image="$project->workListImage" 
        width="1000" 
        height="667"
        ratio="3x2">
      </x-image>
    @else
      <picture class="" style="background-color: #d1d1d1">
        <img src="/assets/img/placeholder-3x2.png" width="300" height="200">
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
          <span class="hidden !lg:inline-block">
            {!! AppHelper::projectTitle($project->worklist_title_desktop) !!}
          </span>
          <span class="inline-block !lg:hidden">
            {!! AppHelper::projectTitle($project->worklist_title_mobile) !!}
          </span>
        </a>
      @else
        <span class="hidden !lg:inline-block">
          {{ $project->worklist_title_desktop }}
        </span>
        <span class="inline-block !lg:hidden">
          {{ $project->worklist_title_mobile }}
        </span>
      @endif
    </h3>
    <p>
      @if ($project->location) <span class="hidden !lg:block">{{ $project->location }}<br></span>@endif
      {{ $project->type }}
    </p>
  </div>
</article>

