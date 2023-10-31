@props(['items', 'type'])
<div>
  @foreach($items as $item)
    @if ($item->isArticle)
      <x-teasers.article :article="$item" />
    @endif
    @if ($item->isProject)
      @if ($type === 'home')

        <a 
          href="{{ route('page.project.show', ['state' => $item->project->states() ? $item->project->states()->first()->slug : '', 'category' => $item->project->categories()->first() ? $item->project->categories()->first()->slug : 'null', 'project' => $item->project->slug]) }}" 
          title="{{ $item->project->title }}">
          <x-teasers.image :image="$item" />
        </a>
      @else
        @if (isset($item->image->name))
          <a 
            href="/img/cache/{{ $item->image->name }}/2000/{{ $item->image->coords }}" 
            data-fancybox="gallery-{{ $item->project->slug }}"
            data-caption="{{ $item->image->caption }} {{ $item->image->credits}}">
            <x-teasers.image :image="$item" />
            <legend class="hidden">
              <div class="ml-12x">{{ $item->image->caption }}@if ($item->image->credits) / {{ $item->image->credits }}@endif</div>
            </legend>
          </a>
        @endif
      @endif
    @endif
  @endforeach
</div>
