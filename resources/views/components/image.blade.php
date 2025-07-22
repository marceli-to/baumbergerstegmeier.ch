@props(['ratio' => null, 'caption' => null])
@if ($maxSizes && $image)
  <picture class="{{ $classes }}">
    @foreach($maxSizes as $minWidth => $maxSize)
      @if ($minWidth > 0)
        <source media="(min-width: {{ $minWidth }}px)" data-srcset="/img/crop/{{ $image->name }}/{{ $maxSize}}/{{ $image->coords }}{{ $ratio ? '/' . $ratio : ''}}">
      @endif
    @endforeach
    <img 
    src="/img/crop/{{ $image->name }}/{{ $maxSize }}/{{ $image->coords }}{{ $ratio ? '/' . $ratio : ''}}"
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

