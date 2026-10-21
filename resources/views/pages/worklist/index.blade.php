@extends('layout.web')
@section('seo_title', match ($filter) {
  'state' => 'Werkliste: ' . $state->description,
  'category' => 'Werkliste: ' . $category->description,
  'category-state' => 'Werkliste: ' . $category->description . ', ' . $state->description,
  default => 'Werkliste',
})
@section('seo_description', match ($filter) {
  'year' => 'Alle Projekte von Baumberger Stegmeier Architektur nach Jahr: Neubauten, Umbauten, Projekte in Planung und Wettbewerbe.',
  'state' => 'Projekte von Baumberger Stegmeier Architektur mit dem Status «' . $state->description . '», nach Jahr geordnet.',
  'category' => 'Projekte von Baumberger Stegmeier Architektur im Programm «' . $category->description . '»: gebaut, in Planung und Wettbewerbe, nach Jahr geordnet.',
  'category-state' => 'Projekte von Baumberger Stegmeier Architektur im Programm «' . $category->description . '» mit dem Status «' . $state->description . '», nach Jahr geordnet.',
  default => 'Alle Projekte von Baumberger Stegmeier Architektur: gebaut, in Planung und Wettbewerbe; filterbar nach Jahr, Status und Programm.',
})
@if (!empty($searchTerm))
  @section('robots', 'noindex, follow')
@endif
@section('content')
<x-page-title />
<section class="content-worklist">
  @include('pages.worklist.filter')
  @if ($projects)
    @if ($filter == 'all')
      @include('pages.worklist.all')
    @endif
    @if ($filter == 'year')
      @include('pages.worklist.year')
    @endif
    @if ($filter == 'state')
      @include('pages.worklist.state')
    @endif
    @if ($filter == 'category')
      @include('pages.worklist.category')
    @endif
    @if ($filter == 'category-state')
    @include('pages.worklist.category-state')
  @endif
  @endif
</section>
@endsection