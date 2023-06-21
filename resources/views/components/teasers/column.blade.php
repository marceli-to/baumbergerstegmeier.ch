@props(['items', 'type'])
<div>
  @foreach($items as $item)
    @if ($item->isArticle)
      <x-teasers.article :article="$item" />
    @endif
    @if ($item->isProject)

      @if ($type === 'home')
        <a 
          href="{{ route('page.project.show', ['state' => $item->project->states()->first()->slug, 'category' => $item->project->categories()->first()->slug, 'project' => $item->project->slug]) }}" 
          title="{{ $item->project->title }}">
          <x-teasers.image :image="$item" />
        </a>
      @else
        <x-teasers.image :image="$item" />
      @endif
    @endif
  @endforeach
</div>
