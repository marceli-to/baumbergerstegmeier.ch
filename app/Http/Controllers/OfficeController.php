<?php
namespace App\Http\Controllers;
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
    return view($this->viewPath . 'profile');
  }

  /**
   * Show the team page
   *
   * @return \Illuminate\Http\Response
   */
  public function team()
  {
    return view($this->viewPath . 'team');
  }

  /**
   * Show the publications page
   *
   * @return \Illuminate\Http\Response
   */
  public function publications()
  {
    return view($this->viewPath . 'publications');
  }
  
  /**
   * Show the awards page
   *
   * @return \Illuminate\Http\Response
   */
  public function awards()
  {
    return view($this->viewPath . 'awards');
  }
  
  /**
   * Show the jobs page
   *
   * @return \Illuminate\Http\Response
   */
  public function jobs()
  {
    return view($this->viewPath . 'jobs');
  }
}
