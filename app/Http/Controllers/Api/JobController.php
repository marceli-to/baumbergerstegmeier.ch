<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Image;
use App\Http\Resources\DataCollection;
use App\Http\Requests\JobStoreRequest;
use Illuminate\Http\Request;

class JobController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return new DataCollection(Job::with('images')->get());
  }

  /**
   * Display the specified resource.
   *
   * @param  Job $job
   * @return \Illuminate\Http\Response
   */
  public function find(Job $job)
  {
    return response()->json(['job' => Job::with('images')->find($job->id)]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\JobStoreRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(JobStoreRequest $request)
  {
    $job = Job::create([
      'text' => $request->input('text'),
    ]);
    $this->handleFlag($job, 'isPublished', $request->input('publish'));
    $this->handleImages($job, $request->input('images'));
    return response()->json(['jobId' => $job->id]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\JobStoreRequest  $request
   * @param  Job $job
   * @return \Illuminate\Http\Response
   */
  public function update(JobStoreRequest $request, Job $job)
  {
    $job = Job::findOrFail($job->id);
    $job->text = $request->input('text');
    $job->save();
    $this->handleFlag($job, 'isPublished', $request->input('publish'));
    $this->handleImages($job, $request->input('images'));
    return response()->json('successfully updated');
  }

  /**
   * Toggle the status a given job
   *
   * @param  Job $job
   * @return \Illuminate\Http\Response
   */
  public function toggle(Job $job)
  {
    if ($job->hasFlag('isPublished'))
    {
      $job->unflag('isPublished');
    }
    else
    {
      $job->flag('isPublished');
    } 
    return response()->json($job->hasFlag('isPublished'));
  }


  /**
   * Remove the specified resource from storage.
   *
   * @param  Job $job
   * @return \Illuminate\Http\Response
   */
  public function destroy(Job $job)
  {
    $job->delete();
    return response()->json('successfully deleted');
  }

  /**
   * Handle flags of a job
   *
   * @param Job $job
   * @param String $flag
   * @param Integer $value
   * @return Boolean
   */  
  protected function handleFlag(Job $job, $flag, $value)
  {
    if ($value == 1)
    {
      $job->flag($flag);
    }
    else
    {
      $job->unflag($flag);
    }
    return $job->hasFlag($flag);
  }
  
  /**
   * Handle associated images
   *
   * @param Job $job
   * @param Array $images
   * @return void
   */  

  protected function handleImages(Job $job, $images = NULL)
  {
    foreach($images as $image)
    {
      $i = Image::findOrFail($image['id']);
      $i->imageable_id = $job->id;
      $i->imageable_type = Job::class;
      $i->save();
    }
  }
}