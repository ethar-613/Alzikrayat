<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Alzikrayat is a thoughtful photo-sharing space for meaningful memories.">
    <title><?= e($title) ?> · <?= e(APP_NAME) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= e(asset('css/app.css')) ?>" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light site-navbar">
        <div class="container">
            <a class="navbar-brand brand-mark" href="<?= e(url('/')) ?>">
                <span class="brand-symbol">A</span>
                <span>Alzikrayat</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavigation" aria-controls="mainNavigation" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavigation">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item"><a class="nav-link" href="<?= e(url('/')) ?>">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= e(url('/photos')) ?>">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= e(url('/about')) ?>">About us</a></li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <?php if ($currentUser !== null): ?>
                        <span class="welcome-note d-none d-xl-inline">Hi <?= e($currentUser['first_name']) ?></span>
                        <a class="btn btn-coral btn-sm px-3" href="<?= e(url('/photo/create')) ?>">Share a memory</a>
                        <form method="post" action="<?= e(url('/logout')) ?>" class="m-0">
                            <?= csrfField() ?>
                            <button class="btn btn-outline-dark btn-sm" type="submit">Logout</button>
                        </form>
                    <?php else: ?>
                        <span class="welcome-note d-none d-md-inline">Please Login</span>
                        <a class="btn btn-dark btn-sm px-3" href="<?= e(url('/login')) ?>">Log in</a>
                        <a class="btn btn-outline-dark btn-sm" href="<?= e(url('/register')) ?>">Join us</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <main>
        <?php if ($successMessage !== null): ?>
            <div class="container pt-4">
                <div class="alert alert-success border-0 shadow-sm" role="alert"><?= e($successMessage) ?></div>
            </div>
        <?php endif; ?>
        <?php if ($errorMessage !== null): ?>
            <div class="container pt-4">
                <div class="alert alert-danger border-0 shadow-sm" role="alert"><?= e($errorMessage) ?></div>
            </div>
        <?php endif; ?>
        <?= $content ?>
    </main>

    <footer class="site-footer">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="footer-brand">Alzikrayat</div>
                <div class="small text-muted">Keep the ordinary moments extraordinary.</div>
            </div>
            <div class="small text-muted">Handcrafted MVC photo sharing · <?= date('Y') ?></div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= e(asset('js/validation.js')) ?>"></script>
</body>
</html>