@extends('layout.web')
@section('content')
<x-page-title :class="'hidden !lg:block'" :type="'h2'" />
@if ($project->coverImage)
  <x-hero class="hidden !lg:block">
    <x-image 
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
  <h1>{{ $project->title }}</h1> 
</section>
@endsection