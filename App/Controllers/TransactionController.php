<?php

namespace App\Controllers;

class TransactionController
{
    public function showForm(): void
    {
        echo '<form action="" method="POST" enctype="multipart/form-data">
            <input type="file" name="csv_file">
            <button type="submit">Upload</button>
          </form>';
    }

    public function upload(): void
    {
        var_dump($_FILES);
    }
}