@extends('layout.web')
@section('content')
<x-page-title />
<section class="content-awards">
  @if ($data_2_columns)
    <div class="sm:grid sm:grid-cols-12 sm:grid-column-gap !lg:hidden">
      @foreach($data_2_columns as $column)
        <div class="mb-40x sm:span-6">
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
              </article>
            @endforeach
          @endforeach
        </div>
      @endforeach
    </div>
  @endif
  @if ($data_3_columns)
    <div class="hidden lg:grid lg:grid-cols-12 lg:grid-column-gap">
      @foreach($data_3_columns as $column)
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