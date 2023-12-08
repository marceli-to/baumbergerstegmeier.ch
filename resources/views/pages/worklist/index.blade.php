@extends('layout.web')
@section('seo_title', 'Werkliste')
@section('seo_description', 'Schwerpunkte bilden neben dem Wohnungsbau Schul- und Gesundheitsbauten wie auch Gewerbebauten. Darüber hinaus gilt unser Interesse dem Umbau und der Sanierung denkmalpflegerisch geschützter Gebäude.')
@section('content')
<x-page-title />
<section class="content-worklist">
  {{-- @include('pages.worklist.filter', ['class' => 'is-mobile js-sticky-filter']) --}}
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
  @endif
</section>
@endsection