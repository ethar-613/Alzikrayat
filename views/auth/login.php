<!-- Login Page content -->

<section class="auth-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="auth-box">
                    <h1 class="mb-3">Log In</h1>
                    <p class="text-muted">New here? <a href="<?= e(url('/register')) ?>">Create an account</a>.</p>

                    <?php if ($lastLogin !== null && $lastLogin !== ''): ?>
                        <div class="alert alert-info">Last login from this computer was <strong><?= e($lastLogin) ?></strong>.</div>
                    <?php endif; ?>
                    <?php if (isset($errors['general'])): ?>
                        <div class="alert alert-danger"><?= e($errors['general']) ?></div>
                    <?php endif; ?>

                    <!-- this is the login form -->
                    <form method="post" action="<?= e(url('/login')) ?>" data-validate-form novalidate>
                        <?= csrfField() ?>
                        <div class="mb-3">
                            <label class="form-label" for="email">Email address</label>
                            <input class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" type="email" value="<?= old('email') ?>" required maxlength="100" autocomplete="email">
                            <?php if (isset($errors['email'])): ?><div class="invalid-feedback"><?= e($errors['email']) ?></div><?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="password">Password</label>
                            <input class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" id="password" name="password" type="password" required autocomplete="current-password">
                            <?php if (isset($errors['password'])): ?><div class="invalid-feedback"><?= e($errors['password']) ?></div><?php endif; ?>
                        </div>
                        <button class="btn btn-primary w-100" type="submit">Log In</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
