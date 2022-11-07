<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <?= $css ?? '' ?>
</head>

<body>
    <div class="container">
        <?php require_once 'header.php' ?>
        <div class="content">
            <?= $content ?>
        </div>
        <?php require_once 'footer.php' ?>
    </div>
</body>

</html>