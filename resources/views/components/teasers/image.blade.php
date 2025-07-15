@props(['image'])
@if ($image->image)
  @php 
    $class = 'teaser';
    if ($image->image !== null && !$image->image->has_coords)
    {
      $class .= $image->image->orientation == 'landscape' ? ' aspect-ratio-3/2' : ' aspect-ratio-2/3';
    }
    $class .= ' ' . $image->image->orientation;
  @endphp
  <x-image 
    :classes="$class"
    :maxSizes="[0 => 600, 1000 => 900]" 
    :image="$image->image" 
    width="1200" 
    {{-- caption="{{ $image->type == 'home' ? $image->project->title : $image->image->caption }}" --}}
    caption="{{ $image->type == 'home' ? $image->project->title : ($image->image->caption ?: ($image->project->title . ', ' . $image->project->location ?? '')) }}"
    height="800">
    @if ($image->type == 'home' || $image->type == 'landing')
      <figcaption>
        {{ $image->project->title }}@if ($image->project->location), <nobr>{{ $image->project->location }}</nobr>@endif
      </figcaption>
    @else
      <figcaption>
        {{ $image->image->caption }}
      </figcaption>
    @endif
  </x-image>
@endif