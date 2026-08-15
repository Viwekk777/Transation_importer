<?php

declare(strict_types=1);

if (!function_exists('formatDate')) {
    function formatDate(string $date): string
    {
        return date('M j, Y', strtotime($date));
    }
}

if (!function_exists('formatDollarAmount')) {
    function formatDollarAmount(float $amount): string
    {
        $isNegative = $amount < 0;
        return ($isNegative ? '-' : '') . '$' . number_format(abs($amount), 2);
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Transactions</title>
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
                text-align: center;
            }

            table tr th, table tr td {
                padding: 5px;
                border: 1px #eee solid;
            }

            tfoot tr th, tfoot tr td {
                font-size: 20px;
            }

            tfoot tr th {
                text-align: right;
            }

            .upload-container {
                margin-bottom: 20px;
            }
        </style>
    </head>
    <body>
        <div class="upload-container">
            <form action="/" method="POST" enctype="multipart/form-data">
                <input type="file" name="csv_file" id="csv_file" required />
                <button type="submit">Upload CSV</button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Check #</th>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($transactions)): ?>
                    <?php foreach ($transactions as $transaction): ?>
                        <tr>
                            <td><?= formatDate($transaction['date']) ?></td>
                            <td><?= htmlspecialchars((string) ($transaction['checkNumber'] ?? '')) ?></td>
                            <td><?= htmlspecialchars($transaction['description']) ?></td>
                            <td>
                                <?php if ($transaction['amount'] < 0): ?>
                                    <span style="color: red;">
                                        <?= formatDollarAmount((float) $transaction['amount']) ?>
                                    </span>
                                <?php elseif ($transaction['amount'] > 0): ?>
                                    <span style="color: green;">
                                        <?= formatDollarAmount((float) $transaction['amount']) ?>
                                    </span>
                                <?php else: ?>
                                    <?= formatDollarAmount((float) $transaction['amount']) ?>
                                <?php endif ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No transactions found. Upload a CSV file to get started.</td>
                    </tr>
                <?php endif ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Total Income:</th>
                    <td><?= formatDollarAmount($totals['totalIncome'] ?? 0) ?></td>
                </tr>
                <tr>
                    <th colspan="3">Total Expense:</th>
                    <td><?= formatDollarAmount($totals['totalExpense'] ?? 0) ?></td>
                </tr>
                <tr>
                    <th colspan="3">Net Total:</th>
                    <td><?= formatDollarAmount($totals['netTotal'] ?? 0) ?></td>
                </tr>
            </tfoot>
        </table>
    </body>
</html>