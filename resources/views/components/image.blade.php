@props(['ratio' => null, 'caption' => null])
@if ($maxSizes && $image)
  @php
    $formats = \App\Support\ImageSupport::modernFormats();
    $breakpoints = $breakpoints();
    $base = end($breakpoints);
  @endphp
  <picture class="{{ $classes }}">
    {{-- Per breakpoint, widest first: the modern formats, then the JPEG/PNG. The browser takes the first source that matches. --}}
    @foreach($breakpoints as $minWidth => $maxSize)
      @foreach ($formats as $format)
        <source @if ($minWidth > 0) media="(min-width: {{ $minWidth }}px)" @endif type="image/{{ $format }}" srcset="{{ $url($maxSize, $format) }}">
      @endforeach
      @if ($minWidth > 0)
        <source media="(min-width: {{ $minWidth }}px)" srcset="{{ $url($maxSize) }}">
      @endif
    @endforeach
    <img
    src="{{ $url($base) }}"
    width="{{ $width }}"
    height="{{ $height }}"
    @if ($image->description)
      aria-label="{{ $image->description . ' – ' . $caption ?? $image->caption ?? $caption }}"
      alt="{{ $image->description . ' – ' . $caption ?? $image->caption ?? $caption }}"
    @else
      aria-label="{{ $image->caption ?? $caption }}"
      alt="{{ $image->caption ?? $caption }}"
    @endif
    loading="lazy">
    {{ $slot }}
  </picture>
@endif
