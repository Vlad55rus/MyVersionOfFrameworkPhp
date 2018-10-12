<?php
class BaseController extends Controller
{
  public function index()
  {
    echo '<h1>BASE INDEX</h1>';
    echo IO::postRaw();
  } 
}