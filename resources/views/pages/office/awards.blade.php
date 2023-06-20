@extends('layout.web')
@section('content')
<x-page-title />
<section class="content-awards">
  @if ($data)
    <div class="md:grid lg:grid-cols-12 lg:grid-column-gap">
      @foreach($data as $column)
        <div class="mb-40x lg:span-4">
          @foreach($column as $key => $year)
            <h2>{{ $key }}</h2>
            @foreach($year as $award)
              <article class="award">
                @if ($award->publishedImage)
                  <x-image 
                    :maxSizes="[0 => 900, 1000 => 600]" 
                    :image="$award->publishedImage" 
                    width="1200" 
                    height="800">
                  </x-image>
                @endif
                {!! $award->text !!}
                {{-- 
                  <h3>{{ $award->title }}</h3>
                  <div>{!! $award->subtitle !!}</div>
                --}}
              </article>
            @endforeach
          @endforeach
        </div>
      @endforeach
    </div>
  @endif
</section>
@endsection