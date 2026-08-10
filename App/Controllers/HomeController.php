<?php
namespace App\Controllers;

class HomeController
{
public function index()
{
  return require_once __DIR__ ."/../../Views/index.php";
}
}
