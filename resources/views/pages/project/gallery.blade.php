@extends('layout.web')
@section('seo_title', $project->title)
@section('content')
@if ($images)
  @foreach($images as $image)
    <x-teasers.image :image="$image" />
  @endforeach
@endif
@endsection