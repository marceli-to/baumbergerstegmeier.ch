<?php
namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends BaseController
{
  protected $viewPath = 'pages.contact.';

  /**
   * Show the contact page
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return view($this->viewPath . 'index', ['data' => Contact::publish()->with('publishedImages')->first()]);
  }
}
