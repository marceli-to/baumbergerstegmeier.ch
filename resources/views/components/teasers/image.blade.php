@props(['image'])
<x-image 
  :classes="'is-teaser'"
  :maxSizes="[0 => 600, 1000 => 900]" 
  :image="$image->image" 
  width="1200" 
  height="800">
  @if ($image->type == 'home')
    <figcaption>
      {{ $image->project->title }}
    </figcaption>
  @else
    <figcaption>
      {{ $image->image->caption }}
    </figcaption>
  @endif
</x-image>