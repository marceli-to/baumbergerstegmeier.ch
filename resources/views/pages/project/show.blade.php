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
    <a href="" class="hidden !lg:block lg:text-2xl">
      Info
    </a>
    <a href="" class="block !lg:hidden icon-chevron text-md is-active">
      Info
    </a>
  </div>
  
  <div class="project-text">
    <div class="lg:grid lg:grid-cols-12 lg:grid-gap">
      <div class="lg:span-6 hidden">
        {!! $project->text !!}
      </div>
      <a href="" class="block !lg:hidden icon-chevron mt-25x text-md is-active">Credits</a>
      <div class="lg:span-6 project-info hidden">
        {!! $project->info !!}
      </div>
    </div>
  </div>

  @if ($project->coverImage)
    <x-hero class="block !lg:hidden">
      <x-image 
        :classes="'aspect-ratio-3/2'"
        :maxSizes="[0 => 1200, 1000 => 1600]" 
        :image="$project->coverImage" 
        width="1200" 
        height="800">
      </x-image>
    </x-hero>
  @endif

</section>
@endsection