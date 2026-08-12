<?php

declare(strict_types=1);

namespace App\Models;
//use Db;

use App\Exceptions\ValidationException;
use PDO;

class Transaction
{
    private PDO $pdo;
    private array $file;
    public function __construct(Db $db , array $file)
    {
        $this->pdo = $db->getConnection();
        $this->file = $file;
    }
    public function uploadToDatabase(): void
    {
        $file = $this->file['tmp_name'];
        $handle = fopen($file,'r');
        if ($handle === false) 
        {
            throw new ValidationException('Failed to open the file');
        }
        fgetcsv($handle);
        $query = "
        INSERT INTO transactions
        (transaction_date, check_number, description, amount)
        VALUES
        (:transaction_date, :check_number, :description, :amount)
        ";
        $stmt = $this->pdo->prepare($query);
        while (($row=fgetcsv($handle))!==false)
            {
                $stmt->execute([
                    ':transaction_date'=> $row[0],
                    ':check_number' => $row[1] !== '' ? $row[1] : null,
                    ':description'=> $row[2],
                    ':amount'=> $row[3],


                ]);
            }
     
            fclose($handle);
            echo'Uploaded Successfully ';

      


        }



    

 
}