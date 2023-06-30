@extends('layout.web')
@section('content')
@if ($coverProject)
  <x-hero :class="'lg:mt-25x'">
    <a href="{{ route('page.project.show', ['state' => $coverProject->states()->first()->slug, 'category' => $coverProject->categories()->first()->slug, 'project' => $coverProject->slug]) }}" title="{{ $coverProject->title }}">
      <x-image 
        :classes="'aspect-ratio-3/2 teaser'"
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
    @if (isset($teasers['col-0']))
      <x-teasers.column :items="$teasers['col-0']" :type="'home'" />
    @endif
    @if (isset($teasers['col-1']))
      <x-teasers.column :items="$teasers['col-1']" :type="'home'" />
    @endif
    @if (isset($teasers['col-2']))
      <x-teasers.column :items="$teasers['col-2']" :type="'home'" />
    @endif
  </x-teasers.index>
@endif
@endsection