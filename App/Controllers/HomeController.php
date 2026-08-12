<?php
namespace App\Controllers;

use App\Exceptions\ValidationException;
use App\Validators\FileValidator;
use App\Models\Transaction;
use App\Models\Db;

class HomeController
{
      public function __construct(private Db $db)
    {
    }
 
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
        $validator = new FileValidator();

        $file = $validator->getValidatedFile();

        $transaction = new Transaction($this->db, $file);

        $transaction->uploadToDatabase();

        header("Location: /transaction");
        exit;
    }


}


