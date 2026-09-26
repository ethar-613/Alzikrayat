<!-- error 500 - Server Error page -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Something went wrong - <?= e(APP_NAME) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= e(asset('css/app.css')) ?>" rel="stylesheet">
</head>
<body class="error-page">
    <div class="container">
        <h1>Something went wrong.</h1>
        <p><?= e($errorDetail) ?></p>
        <a class="btn btn-primary" href="<?= e(url('/')) ?>">Return home</a>
    </div>
</body>
</html>