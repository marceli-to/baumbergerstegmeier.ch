@extends('layout.web')
@section('seo_title', $project->title)
@section('content')

{{-- @if (rtrim(request()->headers->get('referer'), '/') == route('page.home'))
  <a 
    href="{{ route('page.home') }}" 
    class="hidden !md:block mb-3x">
    <x-icons.cross-sm />
  </a>
@else
  @if ($has_category)
    <a 
      href="{{ route('page.project.showLandingByCategory', ['state' => $state->slug, 'category' => $category->slug]) }}" 
      class="hidden !md:block mb-3x">
      <x-icons.cross-sm />
    </a>
  @else
    <a 
      href="{{ route('page.project.showLandingByState', ['state' => $state->slug]) }}" 
      class="hidden !md:block mb-3x">
      <x-icons.cross-sm />
    </a>
  @endif
@endif --}}

@if (Str::startsWith(request()->headers->get('referer'), url('/werkliste')) || rtrim(request()->headers->get('referer'), '/') == route('page.home'))
  <a 
    href="javascript:history.back()" 
    class="hidden !md:block mb-3x">
    <x-icons.cross-sm />
  </a>
@else
  <a 
    href="{{ route('page.worklist') }}" 
    class="hidden !md:block mb-3x">
    <x-icons.cross-sm />
  </a>
  <a href="{{ route('page.worklist') }}" class="back-button">Back to Worklist</a>
@endif


@if ($project->coverImage)
  <x-hero class="hidden !md:block">
    <a 
      href="/img/cache/{{ $project->coverImage->name }}/2000/{{ $project->coverImage->coords }}" 
      data-fancybox="gallery-{{ $project->slug }}"
      data-caption="{{ $project->coverImage->caption }}">
      <x-image 
        :classes="'aspect-ratio-3/2'"
        :maxSizes="[0 => 1200, 1000 => 1800]" 
        :image="$project->coverImage" 
        caption="{{ $project->title }}"
        width="1200" 
        height="800"
        ratio="3x2">
      </x-image>
    </a>
  </x-hero>
@endif
<section class="content-project">
  <header class="content-header items-end flex justify-between">
    <h3>{{ $has_category ? $category->description : $state->description }}</h3>
    <a 
      href="{{ request()->headers->get('referer') }}" 
      class="block md:hidden">
      <x-icons.cross-xs />
    </a>
    @if (rtrim(request()->headers->get('referer'), '/') != route('page.home'))
      @if (isset($browse['prev']) && isset($browse['next']))
        <nav class="project-browse">
          @if (isset($browse['prev']))
            <a href="{{ $browse['prev']['route'] }}" 
              class="block mr-8x lg:mr-9x" 
              title="{{ $browse['prev']['project']->title }}">
              <x-icons.chevron-prev-large />
            </a>
          @endif
          @if (isset($browse['next']))
            <a href="{{ $browse['next']['route'] }}" 
              class="block ml-8x lg:ml-9x" 
              title="{{ $browse['next']['project']->title }}">
              <x-icons.chevron-next-large />
            </a>
          @endif
        </nav>
      @endif
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
    <a href="javascript:;" class="block !md:hidden icon-chevron mb-5x lg:mb-25x text-md" title="Info anzeigen/verbergen" data-btn-toggle-info>
      Info
    </a>
  </div>
  <div class="project-text">
    <div class="md:grid md:grid-cols-12 md:grid-gap">
      <div class="project-text__info md:span-6 pb-25x hidden" data-info>
        {!! $project->text !!}
        <a href="javascript:;" class="block !md:hidden icon-chevron mt-25x text-md" title="Credits anzeigen/verbergen" data-btn-toggle-credits>
          Credits
        </a>
      </div>
      <div class="md:span-6 project-credits hidden" data-credits>
        {!! $project->info !!}
      </div>
    </div>
  </div>
  @if ($teasers)
    <x-teasers.index class="lg:mt-0">
      @foreach ($teasers as $items)
        <x-teasers.column :items="$items" :type="'project'" />
      @endforeach
    </x-teasers.index>
  @endif
  @if (rtrim(request()->headers->get('referer'), '/') != route('page.home'))
    @if (isset($browse['next']))
      <a href="{{ $browse['next']['route'] }}" 
        class="project-teaser" 
        title="{{ $browse['next']['project']->title }}">
        <span>Nächstes Projekt</span>
        <h3>{{ $browse['next']['project']->title }}</h3>
        <x-image 
          :classes="'aspect-ratio-3/2'"
          :maxSizes="[0 => 1200]" 
          :image="$browse['next']['project']->coverImage" 
          caption="{{ $browse['next']['project']->title }}"
          width="1200" 
          height="800"
          ratio="3x2">
        </x-image>
      </a>
    @endif
  @endif
</section>
@endsection