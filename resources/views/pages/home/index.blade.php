@extends('layout.web')
@section('content')
@if ($coverProject)
  <x-hero :class="'lg:mt-25x'">
    <a href="{{ route('page.project.show', ['state' => $coverProject->states()->first()->slug, 'category' => $coverProject->categories()->first()->slug, 'project' => $coverProject->slug]) }}" title="{{ $coverProject->title }}">
      <x-image 
        :classes="'teaser'"
        :maxSizes="[0 => 1200, 1000 => 1600]" 
        :image="$coverProject->coverImage" 
        width="1200" 
        height="800">
        <figcaption>
          {{ $coverProject->title }}
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