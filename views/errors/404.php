<!-- error 404 - Not Found page -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page not found - <?= e(APP_NAME) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= e(asset('css/app.css')) ?>" rel="stylesheet">
</head>
<body class="error-page">
    <div class="container">
        <h1>404</h1>
        <p class="lead"><?= e($message) ?></p>
        <a class="btn btn-primary" href="<?= e(url('/')) ?>">Return home</a>
        <a class="btn btn-outline-secondary" href="<?= e(url('/photos')) ?>">Go to gallery</a>
    </div>
</body>
</html>
