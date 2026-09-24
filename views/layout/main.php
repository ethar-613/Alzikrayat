<!DOCTYPE html>
<?php $currentTheme = isset($_COOKIE['theme']) && $_COOKIE['theme'] === 'dark' ? 'dark' : 'light'; ?>
<html lang="en" data-bs-theme="<?= e($currentTheme) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?> - <?= e(APP_NAME) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= e(asset('css/app.css')) ?>" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark site-navbar">
        <div class="container">
            <a class="navbar-brand" href="<?= e(url('/')) ?>">
                <img src="<?= e(asset('images/logo.png')) ?>" alt="logo" width="30" height="30" class="d-inline-block align-text-top me-2">
                Alzikrayat
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavigation" aria-controls="mainNavigation" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavigation">
                <ul class="navbar-nav nav-pills me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item"><a class="nav-link" href="<?= e(url('/')) ?>">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= e(url('/photos')) ?>">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= e(url('/about')) ?>">About us</a></li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <button type="button" id="themeToggle" class="btn btn-outline-light btn-sm" aria-label="Switch between light and dark mode" data-light-icon="🌙" data-dark-icon="☀️">
                        <span id="themeToggleIcon"><?= $currentTheme === 'dark' ? '☀️' : '🌙' ?></span>
                    </button>
                    <?php if ($currentUser !== null): ?>
                        <span class="d-none d-xl-inline me-2">Hi <a class="text-decoration-none text-dark rounded-pill" id="userNameNav" href="<?= e(url('/user/' . $currentUser['id'])) ?>" >
                            <?= e($currentUser['first_name']) ?>
                            </a></span>
                        <a class="btn btn-primary btn-sm shareBTN" id="sharingbtn" href="<?= e(url('/photo/create')) ?>" >Share Memory :></a>
                        <form method="post" action="<?= e(url('/logout')) ?>" class="m-0">
                            <?= csrfField() ?>
                            <button class="btn btn-outline-danger btn-sm" type="submit">Logout</button>
                        </form>
                    <?php else: ?>
                        <span class="d-none d-md-inline me-2">Please Login</span>
                        <a class="btn btn-primary btn-sm" href="<?= e(url('/login')) ?>">Log in</a>
                        <a class="btn btn-secondary btn-sm" href="<?= e(url('/register')) ?>">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <main>
        <?php if ($successMessage !== null): ?>
            <div class="container pt-4">
                <div class="alert alert-success" role="alert"><?= e($successMessage) ?></div>
            </div>
        <?php endif; ?>
        <?php if ($errorMessage !== null): ?>
            <div class="container pt-4">
                <div class="alert alert-danger" role="alert"><?= e($errorMessage) ?></div>
            </div>
        <?php endif; ?>
        <?= $content ?>
    </main>

    <footer class="site-footer" >
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
            <div>Alzikrayat - Photo Sharing App</div>
            <div>Ethar Emad - Information Technology department</div>
            <div class="small ">Advanced Web Technologies course project, 2026 Sep</div>

        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= e(asset('js/validation.js')) ?>"></script>
</body>
</html>
