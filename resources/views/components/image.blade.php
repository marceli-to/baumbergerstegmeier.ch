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
    title="{{ $caption }}"
    alt="{{ $image->caption }}"
    loading="lazy">
    {{ $slot }}
  </picture>
@endif

