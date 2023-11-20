@extends('layout.web')
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