@extends('layout.web')
@section('content')
<x-page-title :class="'hidden !lg:block'" :type="'h2'" />
@if ($project->coverImage)
  <x-hero class="hidden !lg:block">
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
  
  <header class="content-header">
    <h3>{{ $category->description }}</h3>
  </header>
  
  <div class="mb-10x lg:flex lg:justify-between">
    <h1>{{ $project->title }}</h1>
    <div>
      <a href="javascript:;" class="hidden btn-info" title="Info anzeigen/verbergen" data-btn-toggle-both>
        <x-icons.cross class="icon-project-cross" />
        <span>Info</span>
      </a>
    </div>
    <a href="javascript:;" class="block !lg:hidden icon-chevron text-md" title="Info anzeigen/verbergen" data-btn-toggle-info>
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

  {{-- @if ($project->coverImage)
    <x-hero class="block !lg:hidden">
      <x-image 
        :classes="'aspect-ratio-3/2'"
        :maxSizes="[0 => 1200, 1000 => 1600]" 
        :image="$project->coverImage" 
        width="1200" 
        height="800">
      </x-image>
    </x-hero>
  @endif --}}

  @if ($teasers)
    <x-teasers.index>
      @foreach ($teasers as $items)
        <x-teasers.column :items="$items" :type="'project'" />
      @endforeach
    </x-teasers.index>
  @endif

</section>
@endsection