@props(['ratio' => null])
@if ($maxSizes && $image)
  <picture class="{{ $classes }}">
    @foreach($maxSizes as $minWidth => $maxSize)
      @if ($minWidth > 0)
        <source media="(min-width: {{ $minWidth }}px)" data-srcset="/img/cache/{{ $image->name }}/{{ $maxSize}}/{{ $image->coords }}{{ $ratio ? '/' . $ratio : ''}}">
      @endif
      @endforeach
    {{ $slot }}
    <img 
    src="/img/cache/{{ $image->name }}/{{ $maxSize }}/{{ $image->coords }}{{ $ratio ? '/' . $ratio : ''}}"
    width="{{ $width }}" 
    height="{{ $height }}"
    title="{{ $image->caption }}"
    alt="{{ $image->caption }}"
    loading="lazy">
  </picture>
@endif

