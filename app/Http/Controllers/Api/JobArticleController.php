<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\JobArticle;
use App\Http\Resources\DataCollection;
use App\Http\Requests\JobArticleStoreRequest;
use Illuminate\Http\Request;

class JobArticleController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return new DataCollection(JobArticle::orderBy('order')->get());
  }

  /**
   * Display the specified resource.
   *
   * @param  JobArticle $jobArticle
   * @return \Illuminate\Http\Response
   */
  public function find(JobArticle $jobArticle)
  {
    return response()->json(['jobArticle' => JobArticle::find($jobArticle->id)]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\JobArticleStoreRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(JobArticleStoreRequest $request)
  {
    $jobArticle = JobArticle::create([
      'title' => $request->input('title'),
      'description' => $request->input('description'),
      'teaser_title' => $request->input('teaser_title'),
      'teaser_description' => $request->input('teaser_description'),
      'publish' => $request->input('publish')
    ]);
    return response()->json(['jobArticleId' => $jobArticle->id]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\JobArticleStoreRequest  $request
   * @param  JobArticle $jobArticle
   * @return \Illuminate\Http\Response
   */
  public function update(JobArticleStoreRequest $request, JobArticle $jobArticle)
  {
    $jobArticle = JobArticle::findOrFail($jobArticle->id);
    $jobArticle->title = $request->input('title');
    $jobArticle->description = $request->input('description');
    $jobArticle->teaser_title = $request->input('teaser_title');
    $jobArticle->teaser_description = $request->input('teaser_description');
    $jobArticle->publish = $request->input('publish');
    $jobArticle->save();
    return response()->json('successfully updated');
  }

  /**
   * Toggle the status a given job
   *
   * @param  JobArticle $jobArticle
   * @return \Illuminate\Http\Response
   */
  public function toggle(JobArticle $jobArticle)
  {
    $jobArticle->publish = !$jobArticle->publish;
    $jobArticle->save();
    return response()->json($jobArticle->publish);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  JobArticle $jobArticle
   * @return \Illuminate\Http\Response
   */
  public function destroy(JobArticle $jobArticle)
  {
    $jobArticle->delete();
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
    $jobArticles = $request->get('jobArticles');
    foreach($jobArticles as $jobArticle)
    {
      $p = JobArticle::find($jobArticle['id']);
      $p->order = $jobArticle['order'];
      $p->save(); 
    }
    return response()->json('successfully updated');
  }

}