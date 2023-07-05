@extends('layout.web')
@section('content')
<x-page-title :class="'hidden !md:block'" :type="'h2'" />
@if ($project->coverImage)
  <x-hero class="hidden !md:block">
    <x-image 
      :classes="'aspect-ratio-3/2'"
      :maxSizes="[0 => 1200, 1000 => 1600]" 
      :image="$project->coverImage" 
      width="1200" 
      height="800">
    </x-image>
  </x-hero>
@endif
<section class="content-project">
  <header class="content-header md:items-end md:flex md:justify-between">
    <h3>{{ $category->description }}</h3>
    @if (isset($browse['prev']) && isset($browse['next']))
      <nav class="project-browse">
        @if (isset($browse['prev']))
          <a href="{{ route('page.project.show', ['state' => $browse['prev']->states()->first()->slug, 'category' => $browse['prev']->categories()->first()->slug, 'project' => $browse['prev']->slug]) }}" 
            class="block mr-7x" 
            title="{{ $browse['prev']->title }}">
            <x-icons.chevron-prev-large />
          </a>
        @endif
        @if (isset($browse['next']))
          <a href="{{ route('page.project.show', ['state' => $browse['next']->states()->first()->slug, 'category' => $browse['next']->categories()->first()->slug, 'project' => $browse['next']->slug]) }}" 
            class="block ml-7" 
            title="{{ $browse['next']->title }}">
            <x-icons.chevron-next-large />
          </a>
        @endif
      </nav>
    @endif
  </header>
  <div class="md:flex md:justify-between">
    <h1>{{ $project->title }}@if ($project->location), {{ $project->location }}@endif</h1>
    <div>
      <a href="javascript:;" class="hidden btn-info" title="Info anzeigen/verbergen" data-btn-toggle-both>
        <x-icons.cross class="icon-project-cross" />
        <span>Info</span>
      </a>
    </div>
    <a href="javascript:;" class="block !md:hidden icon-chevron mb-25x text-md" title="Info anzeigen/verbergen" data-btn-toggle-info>
      Info
    </a>
  </div>
  <div class="project-text">
    <div class="lg:grid lg:grid-cols-12 lg:grid-gap">
      <div class="project-text__info lg:span-6 hidden" data-info>
        {!! $project->text !!}
        <a href="javascript:;" class="block !lg:hidden icon-chevron mt-25x text-md" title="Credits anzeigen/verbergen" data-btn-toggle-credits>
          Credits
        </a>
      </div>
      <div class="lg:span-6 project-credits hidden" data-credits>
        {!! $project->info !!}
      </div>
    </div>
  </div>
  @if ($teasers)
    <x-teasers.index class="mt-25x lg:mt-0">
      @foreach ($teasers as $items)
        <x-teasers.column :items="$items" :type="'project'" />
      @endforeach
    </x-teasers.index>
  @endif
  @if (isset($browse['next']))
    <a href="{{ route('page.project.show', ['state' => $browse['next']->states()->first()->slug, 'category' => $browse['next']->categories()->first()->slug, 'project' => $browse['next']->slug]) }}" 
      class="project-teaser" 
      title="{{ $browse['next']->title }}">
      <span>Nächstes Projekt</span>
      <h3>{{ $browse['next']->title }}</h3>
      <x-image 
        :classes="'aspect-ratio-3/2'"
        :maxSizes="[0 => 1200]" 
        :image="$browse['next']->coverImage" 
        width="1200" 
        height="800">
      </x-image>
    </a>
  @endif
</section>
@endsection