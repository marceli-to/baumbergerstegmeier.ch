<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Image;
use App\Http\Resources\DataCollection;
use App\Http\Requests\ArticleStoreRequest;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return new DataCollection(Article::with('images', 'publishedImage')->get());
  }

  /**
   * Display the specified resource.
   *
   * @param  Article $article
   * @return \Illuminate\Http\Response
   */
  public function find(Article $article)
  {
    return response()->json(['article' => Article::with('images')->find($article->id)]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\ArticleStoreRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(ArticleStoreRequest $request)
  {
    $article = Article::create([
      'category' => $request->input('category'),
      'title' => $request->input('title'),
      'text' => $request->input('text'),
      'link' => $request->input('link'),
      'publish' => $request->input('publish')
    ]);
    $this->handleImages($article, $request->input('images'));
    return response()->json(['articleId' => $article->id]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\ArticleStoreRequest  $request
   * @param  Article $article
   * @return \Illuminate\Http\Response
   */
  public function update(ArticleStoreRequest $request, Article $article)
  {
    $article = Article::findOrFail($article->id);
    $article->category = $request->input('category');
    $article->title = $request->input('title');
    $article->text = $request->input('text');
    $article->link = $request->input('link');
    $article->publish = $request->input('publish');
    $article->save();
    $this->handleImages($article, $request->input('images'));
    return response()->json('successfully updated');
  }

  /**
   * Toggle the status a given job
   *
   * @param  Article $article
   * @return \Illuminate\Http\Response
   */
  public function toggle(Article $article)
  {
    $article->publish = !$article->publish;
    $article->save();
    return response()->json($article->publish);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  Article $article
   * @return \Illuminate\Http\Response
   */
  public function destroy(Article $article)
  {
    $article->delete();
    return response()->json('successfully deleted');
  }

  /**
   * Update the order the projects
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */

  public function order(Request $request)
  {
    $articles = $request->get('articles');
    foreach($articles as $article)
    {
      $p = Article::find($article['id']);
      $p->order = $article['order'];
      $p->save(); 
    }
    return response()->json('successfully updated');
  }

  /**
   * Handle associated images
   *
   * @param Article $article
   * @param Array $images
   * @return void
   */  

  protected function handleImages(Article $article, $images = NULL)
  {
    foreach($images as $image)
    {
      $i = Image::findOrFail($image['id']);
      $i->imageable_id = $article->id;
      $i->imageable_type = Article::class;
      $i->save();
    }
  }

}