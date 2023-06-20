@extends('layout.web')
@section('content')
<x-page-title />
<section class="content-publications">
  @if ($data)
    <div class="md:grid lg:grid-cols-12 lg:grid-column-gap">
      @foreach($data as $column)
        <div class="lg:span-4">
          @foreach($column as $key => $year)
            <div class="mb-36x lg:mb-48x">
              <h2>{{ $key }}</h2>
              @foreach($year as $publication)
                <article class="publication">
                  @if ($publication->publishedImage)
                    <x-image 
                      :maxSizes="[0 => 900, 1000 => 600]" 
                      :image="$publication->publishedImage" 
                      width="1200" 
                      height="800">
                    </x-image>
                  @endif
                  @if ($publication->title)
                    <h3>{{ $publication->title }}</h3>
                  @endif
                  @if ($publication->subtitle)
                    <h4>
                      <span></span>
                      {{ $publication->subtitle }}
                    </h4>
                  @endif
                  @if ($publication->description)
                    {!! nl2br($publication->description) !!}
                  @endif
                  @if ($publication->publishedFile)
                  <div>
                      <a href="/storage/uploads/{{ $publication->publishedFile->name }}" target="_blank" class="icon-file" title="Download {{ $publication->publishedFile->caption }}">
                        {{ $publication->publishedFile->caption }}
                      </a>
                  </div>
                  @endif
                </article>
              @endforeach
            </div>
          @endforeach
        </div>
      @endforeach
    </div>
  @endif
</section>
@endsection