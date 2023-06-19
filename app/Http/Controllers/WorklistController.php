<?php
namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class WorklistController extends BaseController
{
  protected $viewPath = 'pages.worklist.';

  /**
   * Show the worklist page
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return view($this->viewPath . 'index');
  }
}
