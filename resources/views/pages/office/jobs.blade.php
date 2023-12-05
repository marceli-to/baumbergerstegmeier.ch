@extends('layout.web')
@section('content')
<x-page-title />
<section class="content-jobs">
  @if (isset($data->publishedImages))
    <div class="lg:grid lg:grid-cols-12 lg:grid-column-gap mb-20x lg:mb-40x">
      @foreach($data->publishedImages as $image)
        <div class="lg:span-6 {{ $loop->index == 1 ? 'hidden !lg:block' : ''}}">
          <x-image 
            :maxSizes="[0 => 1200, 1000 => 1600]" 
            :image="$image" 
            width="1200" 
            caption="Jobs"
            height="800">
          </x-image>
        </div>
      @endforeach
    </div>
  @endif

  <div class="lg:grid lg:grid-cols-12 lg:grid-column-gap mb-20x lg:mb-40x">
    @if ($articles->count() > 0)
      @foreach($articles as $article)
        <article class="lg:span-6">
          <h2>{{ $article->title }}</h2>
          <div>
            {!! $article->description !!}
          </div>
        </article>
      @endforeach
    @else
      <article class="lg:span-6">
        {!! $data->text ?? '' !!}
      </article>
    @endif
  </div>
@endsection