<?php

declare(strict_types=1);

namespace App\Models;

use App\Exceptions\ValidationException;
use PDO;

class Transaction
{
    private PDO $pdo;
    private array $file;

    public function __construct(Db $db, array $file = [])
    {
        $this->pdo = $db->getConnection();
        $this->file = $file;
    }

    public function uploadToDatabase(): void
    {
        $filePath = $this->file['tmp_name'] ?? null;

        if (!$filePath || !file_exists($filePath)) {
            throw new ValidationException('No valid file uploaded.');
        }

        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            throw new ValidationException('Failed to open uploaded file.');
        }

        // Skip CSV header
        fgetcsv($handle);

        $query = "
            INSERT INTO transactions (transaction_date, check_number, description, amount)
            VALUES (:transaction_date, :check_number, :description, :amount)
        ";
        $stmt = $this->pdo->prepare($query);

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 4) {
                continue;
            }

            // Convert date format for MySQL (e.g., 01/04/2021 -> 2021-01-04)
            $formattedDate = date('Y-m-d', strtotime(trim($row[0])));
            $checkNumber   = trim($row[1]) !== '' ? trim($row[1]) : null;
            $description   = trim($row[2]);
            // Strip '$' and ',' to safely cast string to float
            $cleanAmount   = (float) str_replace(['$', ','], '', trim($row[3]));

            $stmt->execute([
                ':transaction_date' => $formattedDate,
                ':check_number'     => $checkNumber,
                ':description'      => $description,
                ':amount'           => $cleanAmount,
            ]);
        }

        fclose($handle);
    }

    public function getTransaction(): array
    {
        $stmt = $this->pdo->query("
            SELECT 
                transaction_date AS date, 
                check_number AS checkNumber, 
                description, 
                amount 
            FROM transactions 
            ORDER BY transaction_date DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function calculateTotals(array $transactions): array
    {
        $totals = [
            'netTotal'     => 0.0,
            'totalIncome'  => 0.0,
            'totalExpense' => 0.0,
        ];

        foreach ($transactions as $t) {
            $amount = (float) $t['amount'];
            $totals['netTotal'] += $amount;

            if ($amount >= 0) {
                $totals['totalIncome'] += $amount;
            } else {
                $totals['totalExpense'] += $amount;
            }
        }

        return $totals;
    }
}