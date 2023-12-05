@extends('layout.web')
@section('content')
<x-page-title />
<section class="content-profile">
  @if (isset($data->publishedImages))
    <div class="md:grid md:grid-cols-12 md:grid-column-gap mb-20x lg:mb-40x">
      @foreach($data->publishedImages as $image)
        <div class="md:span-6 {{ $loop->index == 1 ? 'hidden !md:block' : ''}}">
          <x-image 
            :maxSizes="[0 => 1200, 1000 => 1600]" 
            :image="$image" 
            caption="Profil"
            width="1200" 
            height="800">
          </x-image>
        </div>
      @endforeach
    </div>
  @endif
  
  @if (isset($data->title_bsa))
    <h2>{{ $data->title_bsa }}</h2>
  @endif

  @if (isset($data->text_bsa))
    <article class="content-columns">
      {!! $data->text_bsa !!}
    </article>
  @endif

  @if (isset($data->title_bsemi))
    <h2>{{ $data->title_bsemi }}</h2>
  @endif

  @if (isset($data->text_bsemi))
    <article class="md:grid md:grid-cols-12 md:grid-column-gap">
      <div class="md:span-6">{!! $data->text_bsemi !!}</div>
    </article>
  @endif

@endsection