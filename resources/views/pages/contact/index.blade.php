@extends('layout.web')
@section('content')
<x-page-title />
<section class="content-contact lg:grid lg:grid-cols-12 lg:grid-column-gap">
  <div class="lg:span-6">
    @if (isset($contact->publishedImages[0]))
      <x-image 
        :maxSizes="[0 => 1200, 1000 => 1600]" 
        :image="$contact->publishedImages[0]" 
        width="1200" 
        height="800">
      </x-image>
    @endif
    @if (isset($contact->address))
      <address class="mt-20x lg:mt-16x">
        {!! $contact->address !!}
      </address>
    @endif
  </div>
  <div class="lg:span-6 mt-25x lg:mt-0">
    @if (isset($contact->publishedImages[1]))
      @if (isset($contact->maps_uri))
        <a href="{{ $contact->maps_uri }}" target="_blank" title="Auf Google Maps anzeigen">
          <x-image 
            :maxSizes="[0 => 1200, 1000 => 1600]" 
            :image="$contact->publishedImages[1]" 
            width="1200" 
            height="800">
          </x-image>
        </a>
      @else
        <x-image 
          :maxSizes="[0 => 1200, 1000 => 1600]" 
          :image="$contact->publishedImages[1]" 
          width="1200" 
          height="800">
        </x-image>
      @endif
    @endif
    @if (isset($contact->maps_uri))
      <div class="mt-8x lg:mt-16x">
        <p>
          <a href="{{ $contact->maps_uri }}" class="icon-arrow-right-up" target="_blank" title="Auf Google Maps anzeigen">
            Auf Google Maps anzeigen
          </a>
        </p>
      </div>
    @endif
  </div>
  <div class="lg:span-12 mt-40x lg:mt-16x">
    {!! $contact->description !!}
    @if ($contact->imprint || $contact->privacy)
      <div class="mt-32x">
        @if ($contact->imprint)
          <div>
            <a href="javascript:;" class="icon-chevron-down" title="Impressum anzeigen/verbergen" data-btn-toggle>Impressum</a>
            <div class="hidden pt-16x" data-hidden>
              {!! $contact->imprint !!}
            </div>
          </div>
        @endif
        @if ($contact->privacy)
          <div>
            <a href="javascript:;" class="icon-chevron-down" title="Datenschutzerklärung anzeigen/verbergen" data-btn-toggle>Datenschutzerklärung</a>
            <div class="hidden pt-16x" data-hidden>
              {!! $contact->privacy !!}
            </div>
          </div>
        @endif
      </div>
    @endif
  </div>
</section>
@endsection