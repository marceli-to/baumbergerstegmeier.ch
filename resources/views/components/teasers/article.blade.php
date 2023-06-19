@props(['article'])
<article class="is-teaser">
  <header class="content-header">
    <h3>{{ $article->article->category }}</h3>
  </header>
  <h2>{{ $article->article->title }}</h2>

  <x-image 
    :maxSizes="[0 => 600, 1000 => 900]" 
    :image="$article->article->publishedImage" 
    width="1200" 
    height="800">
  </x-image>

  <div>
    {!! $article->article->text !!}
  </div>
</article>