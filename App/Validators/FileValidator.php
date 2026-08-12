<?php
declare(strict_types= 1);

namespace App\Validators;
use App\Exceptions\ValidationException;
use finfo;

class FileValidator
{
        private const ALLOWED_MIMES = [
        'text/csv',
        'text/plain',
        'application/csv',
        'text/comma-separated-values',
        'application/vnd.ms-excel'
    ];
    private const MAX_SIZE = 5 * 1024 * 1024;
   public function validate(array $file): void
{
    if (!isset($file['error']) || !isset($file['tmp_name']) || !isset($file['size'])) {
        throw new ValidationException('No uploaded file or malformed structure');
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new ValidationException('Upload unsuccessful');
    }

    if ($file['size'] > self::MAX_SIZE) {
        throw new ValidationException('File size is too large');
    }

    if (!is_uploaded_file($file['tmp_name'])) {
        throw new ValidationException('Invalid upload source');
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $realMime = $finfo->file($file['tmp_name']);

    if (!in_array($realMime, self::ALLOWED_MIMES, true)) {
        throw new ValidationException('Wrong MIME type');
    }
}

public function getValidatedFile()
{
 
  $file = $_FILES['csv_file'] ?? null;
  $validator = new FileValidator();
  try {
  $validator->validate($file);


 return $file;
  }
  catch (ValidationException $e) 
  {
    
    $error=$e->getMessage();
    require __DIR__ . "/../../Views/index.php";

   

  }
  
 

}}