@extends('layout.web')
@section('content')
@if ($coverProject)
  <section class="hero is-landing">
    <a href="{{ route('page.project.show', ['state' => $coverProject->states()->first()->slug, 'category' => $coverProject->categories()->first()->slug, 'project' => $coverProject->slug]) }}" title="{{ $coverProject->title }}">
      <x-image 
        :classes="'is-teaser'"
        :maxSizes="[0 => 1200, 1000 => 1600]" 
        :image="$coverProject->coverImage" 
        width="1200" 
        height="800">
        <figcaption>
          {{ $coverProject->title }}
        </figcaption>
      </x-image>
    </a>
  </section>
@endif

@if ($teasers)
  <x-teasers.index>
    @foreach ($teasers as $items)
      <x-teasers.column :items="$items" />
    @endforeach
  </x-teasers.index>
@endif
@endsection