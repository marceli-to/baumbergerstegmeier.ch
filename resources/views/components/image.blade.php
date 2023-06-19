@if ($maxSizes && $image)
  <picture class="{{ $classes }}">
    @foreach($maxSizes as $minWidth => $maxSize)
      @if ($minWidth > 0)
        <source media="(min-width: {{ $minWidth }}px)" data-srcset="/img/cache/{{ $image->name }}/{{ $maxSize}}/{{ $image->coords }}">
      @else
        <img 
          src="/img/cache/{{ $image->name }}/{{ $maxSize }}/{{ $image->coords }}"
          width="{{ $width }}" 
          height="{{ $height }}"
          title="{{ $image->caption }}"
          alt="{{ $image->caption }}"
          loading="lazy">
      @endif
    @endforeach
    {{ $slot }}
  </picture>
@endif

