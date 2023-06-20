<?php
namespace App\Http\Controllers;
use App\Models\Profile;
use App\Models\Job;
use App\Models\JobArticle;
use App\Models\Award;
use App\Services\Awards;
use App\Models\Publication;
use App\Services\Publications;
use App\Models\Team;
use App\Services\Teams;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class OfficeController extends BaseController
{
  protected $viewPath = 'pages.office.';

  /**
   * Show the profile page
   *
   * @return \Illuminate\Http\Response
   */
  public function profile()
  {
    return view(
      $this->viewPath . 'profile', 
      [
        'data' => Profile::publish()->with('publishedImages')->first()
      ]
    );
  }

  /**
   * Show the team page
   *
   * @return \Illuminate\Http\Response
   */
  public function team()
  {
    return view(
      $this->viewPath . 'team',
      [
        'data' => (new Teams())->get()
      ]
    );
  }

  /**
   * Show the publications page
   *
   * @return \Illuminate\Http\Response
   */
  public function publications()
  {
    return view(
      $this->viewPath . 'publications',
      [
        'data' => (new Publications())->get()
      ]
    );
  }
  
  /**
   * Show the awards page
   *
   * @return \Illuminate\Http\Response
   */
  public function awards()
  {
    return view(
      $this->viewPath . 'awards',
      [
        'data' => (new Awards())->get()
      ]
    );
  }
  
  /**
   * Show the jobs page
   *
   * @return \Illuminate\Http\Response
   */
  public function jobs()
  {
    return view(
      $this->viewPath . 'jobs', 
      [
        'data' => Job::publish()->with('publishedImages')->first(),
        'articles' => JobArticle::publish()->orderBy('order')->get()
      ]
    );
  }
}
