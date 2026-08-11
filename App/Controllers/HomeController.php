<?php
namespace App\Controllers;

use App\Exceptions\ValidationException;
use App\Validators\FileValidator;

class HomeController
{
public function index()
{
  return require __DIR__ ."/../../Views/index.php";
}

public function transaction()
{
    return require __DIR__ ."/../../Views/Transaction.php";

}

public function validateFile()
{
 
  $file = $_FILES['csv_file'] ?? null;
  $validator = new FileValidator();
  try {
  $validator->validate($file);
 header("Location: /transaction");
 session_destroy();
 exit; }
  catch (ValidationException $e) 
  {
    
    $error=$e->getMessage();
    require __DIR__ . "/../../Views/index.php";

   

  }
  
 

}


}

