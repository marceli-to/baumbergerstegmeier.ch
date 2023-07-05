@extends('layout.web')
@section('content')
<x-page-title />
<section class="content-worklist">
  @include('pages.worklist.filter')
  @if ($projects)
    @if ($filter == 'all')
      @include('pages.worklist.all')
    @endif
  @endif
</section>
@endsection