@extends('layout.web')
@section('content')
@if ($project->coverImage)
  <section class="hero hidden !lg:block">
    <x-image 
      :maxSizes="[0 => 1200, 1000 => 1600]" 
      :image="$project->coverImage" 
      width="1200" 
      height="800">
    </x-image>
  </section>
@endif
<section class="project">
  <header class="content-header">
    <h3>{{ $category->description }}</h3>
  </header>
  <h1>{{ $project->title }}</h1> 
</section>
@endsection