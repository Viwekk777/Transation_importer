<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>
    <body>
        <form action="" method="post" enctype="multipart/form-data" name="transaction_file">
            <input type="file" name="csv_file" id="csv_file">
            <label for="csv_file">Upload File</label>
            <button type="submit">Submit</button>
            

<div id="error"></div>

<?php if (isset($error)): ?>
    <script>
        const errorMessage = <?= json_encode($error) ?>;

        document.getElementById('error').innerHTML =
            `<div style="color:red;">${errorMessage}</div>`;

        setTimeout(() => {
            window.location.href = "/";
        }, 3000);
    </script>
<?php endif; ?>

</form>
</body>


</html>
<?php
