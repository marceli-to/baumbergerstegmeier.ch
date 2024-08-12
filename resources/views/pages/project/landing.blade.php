@extends('layout.web')
@section('seo_title', $state->description ?? $category->description)
@section('content')
<section class="content-project content-project--landing">
  <h1>{{ $is_category ? $category->description : $state->description }}</h1>
  @if ($teasers)
    <x-teasers.index class="is-mobile lg:mt-0">
      @foreach($teasers as $item)
        <a 
          href="{{ $item->project->getShowRoute() }}" 
          title="{{ $item->project->title }}">
          <x-teasers.image :image="$item" />
        </a>
      @endforeach
  </x-teasers.index>
  @endif
  @if ($teaser_columns)
    <x-teasers.index class="is-desktop lg:mt-0">
      @foreach ($teaser_columns as $items)
        <div>
          @foreach($items as $item)
            {{-- <a 
              href="{{ $item->project->getShowRoute() }}" 
              title="{{ $item->project->title }}">
              <x-teasers.image :image="$item" />
            </a> --}}
            <a 
              href="{{ $is_category ? route('page.project.showByStateAndCategory', ['category' => $category->slug, 'state' => $state->slug]) : route('page.project.showByState', ['state' => $state->slug]) }}" 
              title="{{ $item->project->title }}">
              <x-teasers.image :image="$item" />
            </a>
          @endforeach
        </div>
      @endforeach
    </x-teasers.index>
  @endif
</section>
@endsection