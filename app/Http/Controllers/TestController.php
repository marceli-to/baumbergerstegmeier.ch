<?php
namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;
use App\Models\Project;
use Illuminate\Http\Request;

class TestController extends BaseController
{
  /**
   * Show the homepage
   *
   * @return \Illuminate\Http\Response
   */

  public function index()
  {
    $projects = Project::with('categories', 'states')->get();
  }

}
