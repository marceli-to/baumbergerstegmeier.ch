@extends('layout.web')
@section('content')
@if ($coverProject)
  <x-hero :class="'lg:mt-25x'">
    <a href="{{ $coverProject->getShowRoute() }}" title="{{ $coverProject->title }}">
      <x-image 
        :classes="'aspect-ratio-3/2 teaser'"
        :maxSizes="[0 => 1200, 1000 => 1600]" 
        :image="$coverProject->coverImage" 
        caption="{{ $coverProject->title }}"
        width="1200" 
        height="800"
        ratio="3x2">
        <figcaption>
          {{ $coverProject->title }}@if ($coverProject->location), {{ $coverProject->location }}@endif
        </figcaption>
      </x-image>
    </a>
  </x-hero>
@endif

@if ($teasers)
  <x-teasers.index class="md:mt-36x">
    @foreach ($teasers as $items)
      <x-teasers.column :items="$items" :type="'home'" />
    @endforeach
  </x-teasers.index>
@endif
@endsection