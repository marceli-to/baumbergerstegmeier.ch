@extends('layout.web')
@section('seo_title', 'Kontakt')
@section('seo_description', 'Hier finden Sie unsere Adresse sowie E-Mail-Adressen für Medienanfragen und Bewerbungen.')
@section('content')
<x-page-title />
<section class="content-contact md:grid md:grid-cols-12 md:grid-column-gap">
  <div class="md:span-6">
    @if (isset($data->publishedImages[0]))
      <x-image 
        :maxSizes="[0 => 1200, 1000 => 1600]" 
        :image="$data->publishedImages[0]" 
        caption="Kontakt"
        width="1200" 
        height="800">
      </x-image>
    @endif
    @if (isset($data->address))
      <address class="mt-20x md:mt-16x">
        {!! $data->address !!}
      </address>
    @endif
  </div>
  <div class="md:span-6 mt-25x md:mt-0">
    @if (isset($data->publishedImages[1]))
      @if (isset($data->maps_uri))
        <a href="{{ $data->maps_uri }}" target="_blank" title="Auf Google Maps anzeigen">
          <x-image 
            :maxSizes="[0 => 1200, 1000 => 1600]" 
            :image="$data->publishedImages[1]" 
            width="1200" 
            caption="Kontakt"
            height="800">
          </x-image>
        </a>
      @else
        <x-image 
          :maxSizes="[0 => 1200, 1000 => 1600]" 
          :image="$data->publishedImages[1]" 
          width="1200" 
          caption="Kontakt"
          height="800">
        </x-image>
      @endif
    @endif
    @if (isset($data->maps_uri))
      <div class="mt-8x md:mt-16x">
        <p>
          <a href="{{ $data->maps_uri }}" class="icon-arrow-right-up" target="_blank" title="Auf Google Maps anzeigen">
            Auf Google Maps anzeigen
          </a>
        </p>
      </div>
    @endif
  </div>
  <div class="md:span-12 mt-40x md:mt-16x">
    {!! $data->description ?? '' !!}
    @if (isset($data->imprint) || isset($data->privacy))
      <div class="mt-32x">
        @if (isset($data->imprint))
          <div>
            <a href="javascript:;" class="icon-chevron" title="Impressum anzeigen/verbergen" data-btn-toggle>Impressum</a>
            <div class="hidden pt-16x mb-20x lg:mb-40x text-sm lg:text-md" data-hidden>
              {!! $data->imprint !!}
            </div>
          </div>
        @endif
        @if (isset($data->privacy))
          <div>
            <a href="javascript:;" class="icon-chevron" title="Datenschutzerklärung anzeigen/verbergen" data-btn-toggle>Datenschutzerklärung</a>
            <div class="hidden pt-16x text-sm lg:text-md md:max-w-50" data-hidden>
              {!! $data->privacy !!}
            </div>
          </div>
        @endif
      </div>
    @endif
  </div>
</section>
@endsection