@extends('layout.web')
@section('seo_title', 'Publikationen')
@section('seo_description', 'Zahlreiche Veröffentlichungen in der Fach- und Tagespresse, wie Hochparterre, werk, bauen+wohnen, TEC21, NZZ, Tagesanzeige u. v. m. ')
@section('content')
<x-page-title />
<section class="content-publications">

  @if ($data_2_columns)
    <div class="sm:grid sm:grid-cols-12 sm:grid-column-gap !lg:hidden">
      @foreach($data_2_columns as $key => $column)
        <div class="sm:span-6">
          @foreach($column as $key => $year)
            <div class="mb-36x lg:mb-48x">
              <h2>{{ $key }}</h2>
              @foreach($year as $publication)
                <article class="publication">
                  @if ($publication->publishedImage)
                    <x-image 
                      :maxSizes="[0 => 900, 1000 => 600]" 
                      :image="$publication->publishedImage" 
                      caption="{{ $publication->title }}"
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
                  {{-- @if ($publication->description)
                    {!! nl2br($publication->description) !!}
                  @endif --}}
                  @if ($publication->publishedFile)
                  <div>
                      <a href="/storage/uploads/{{ $publication->publishedFile->name }}" target="_blank" class="icon-file" title="Download {{ $publication->publishedFile->caption }}">
                        {{ $publication->publishedFile->caption }}
                      </a>
                  </div>
                  @endif

                  @if ($publication->description)
                    @if ($publication->link)
                    <div>
                      <a href="{{ $publication->link }}" target="{{ $publication->link_target }}" title="">
                        {!! nl2br($publication->description) !!}<x-icons.arrow-right-up class="ml-8x" />
                      </a>
                    </div>
                    @else
                      {!! nl2br($publication->description) !!}
                    @endif
                  @endif

                  {{-- @if ($publication->link)
                  <div>
                    <a href="{{ $publication->link }}" target="_blank" title="">
                      {{ $publication->link }}<x-icons.arrow-right-up class="ml-8x" />
                    </a>
                  </div>
                  @endif --}}



                </article>
              @endforeach
            </div>
          @endforeach
        </div>
      @endforeach
    </div>
  @endif

  @if ($data_3_columns)
    <div class="hidden lg:grid lg:grid-cols-12 lg:grid-column-gap">
      @foreach($data_3_columns as $key => $column)
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
                      caption="{{ $publication->title }}"
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
                    @if ($publication->link)
                      <a href="{{ $publication->link }}" target="{{ $publication->link_target }}" title="">
                        {!! nl2br($publication->description) !!}<x-icons.arrow-right-up class="ml-8x" />
                      </a>
                    @else
                      {!! nl2br($publication->description) !!}
                    @endif
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