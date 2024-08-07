@extends('layout.web')
@section('seo_title', $state->description ?? $category->description)
@section('content')
<section class="content-project content-project--landing">
  <h1>{{ $is_category ? $category->description : $state->description }}</h1>
  @if ($teasers)
    <x-teasers.index class="lg:mt-0">
      @foreach ($teasers as $items)
        <div>
          @foreach($items as $item)
            <a 
              href="{{ $item->project->getShowRoute() }}" 
              title="{{ $item->project->title }}">
              <x-teasers.image :image="$item" />
            </a>
          @endforeach
        </div>
      @endforeach
    </x-teasers.index>
  @endif
</section>
@endsection