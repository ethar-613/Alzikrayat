<section class="auth-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">
                <div class="auth-box">
                    <h1 class="mb-3">Create an Account</h1>
                    <p class="text-muted">Already a member? <a href="<?= e(url('/login')) ?>">Log in instead</a>.</p>

                    <form method="post" action="<?= e(url('/register')) ?>" data-validate-form novalidate>
                        <?= csrfField() ?>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="first_name">First name</label>
                                <input class="form-control <?= isset($errors['first_name']) ? 'is-invalid' : '' ?>" id="first_name" name="first_name" type="text" value="<?= old('first_name') ?>" required maxlength="50" pattern="[A-Za-z]+([ -][A-Za-z]+)*" autocomplete="given-name">
                                <?php if (isset($errors['first_name'])): ?><div class="invalid-feedback"><?= e($errors['first_name']) ?></div><?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="last_name">Last name</label>
                                <input class="form-control <?= isset($errors['last_name']) ? 'is-invalid' : '' ?>" id="last_name" name="last_name" type="text" value="<?= old('last_name') ?>" required maxlength="50" pattern="[A-Za-z]+([ -][A-Za-z]+)*" autocomplete="family-name">
                                <?php if (isset($errors['last_name'])): ?><div class="invalid-feedback"><?= e($errors['last_name']) ?></div><?php endif; ?>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="email">Email address</label>
                                <input class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" type="email" value="<?= old('email') ?>" required maxlength="100" autocomplete="email">
                                <?php if (isset($errors['email'])): ?><div class="invalid-feedback"><?= e($errors['email']) ?></div><?php endif; ?>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="password">Password</label>
                                <input class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" id="password" name="password" type="password" required minlength="8" maxlength="72" autocomplete="new-password">
                                <div class="form-text">Use at least 8 characters.</div>
                                <?php if (isset($errors['password'])): ?><div class="invalid-feedback"><?= e($errors['password']) ?></div><?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="location">Location <span class="text-muted">(optional)</span></label>
                                <input class="form-control <?= isset($errors['location']) ? 'is-invalid' : '' ?>" id="location" name="location" type="text" value="<?= old('location') ?>" maxlength="100" autocomplete="address-level2">
                                <?php if (isset($errors['location'])): ?><div class="invalid-feedback"><?= e($errors['location']) ?></div><?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="occupation">Occupation <span class="text-muted">(optional)</span></label>
                                <input class="form-control <?= isset($errors['occupation']) ? 'is-invalid' : '' ?>" id="occupation" name="occupation" type="text" value="<?= old('occupation') ?>" maxlength="100" autocomplete="organization-title">
                                <?php if (isset($errors['occupation'])): ?><div class="invalid-feedback"><?= e($errors['occupation']) ?></div><?php endif; ?>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="description">About you <span class="text-muted">(optional)</span></label>
                                <textarea class="form-control <?= isset($errors['description']) ? 'is-invalid' : '' ?>" id="description" name="description" rows="3" maxlength="2000"><?= old('description') ?></textarea>
                                <?php if (isset($errors['description'])): ?><div class="invalid-feedback"><?= e($errors['description']) ?></div><?php endif; ?>
                            </div>
                        </div>
                        <button class="btn btn-primary w-100 mt-4" type="submit">Create My Account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
