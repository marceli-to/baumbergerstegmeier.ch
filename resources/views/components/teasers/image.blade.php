@props(['image'])
@php 
  $class = 'teaser';
  if (!$image->image->has_coords)
  {
    $class .= $image->image->orientation == 'landscape' ? ' aspect-ratio-3/2' :  ' aspect-ratio-2/3';
  }
@endphp
<x-image 
  :classes="$class"
  :maxSizes="[0 => 600, 1000 => 900]" 
  :image="$image->image" 
  width="1200" 
  height="800">
  @if ($image->type == 'home')
    <figcaption>
      {{ $image->project->title }}@if ($image->project->location), {{ $image->project->location }}@endif
    </figcaption>
  @else
    <figcaption>
      {{ $image->image->caption }}
    </figcaption>
  @endif
</x-image>