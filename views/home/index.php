<!-- Home Page Content -->

<!-- display little description about the website and statistics from it (number of users, photos and comments)  and two buttons to navigate -->
<section class="hero-section">
    <div class="container">
        <h1>Alzikrayat</h1>
        <p class="lead">Photo sharing website to Keep the ordinary moments extraordinary. 
            you can Upload your photos, share them with everyone, and leave comments on photos you like.</p>
        <div class="d-flex flex-wrap gap-3 mt-3">
            <a class="btn btn-primary btn-lg" href="<?= e(url('/photos')) ?>" style="background-color:#8929f7;">View Gallery</a>
            <a class="btn btn-outline-secondary btn-lg" href="<?= e(url('/about')) ?>">About Us</a>
        </div>
        <div class="d-flex flex-wrap gap-4 mt-4 stat-row">
            <div><strong><?= e((string) $userCount) ?></strong> users</div>
            <div><strong><?= e((string) $photoCount) ?></strong> photos</div>
            <div><strong><?= e((string) $commentCount) ?></strong> comments</div>
        </div>
    </div>
</section>

<!-- here contain latest photos in the website as a glance -->
<section class="container section-padding">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-3">
        <h2 class="section-title mb-0">Latest Photos</h2>
        <a href="<?= e(url('/photos')) ?>">See all photos »</a>
    </div>

    <?php if ($latestPhotos === []): ?>
        <div class="empty-state mt-4">
            <h3>No photos yet.</h3>
            <p>Sign in and upload the first photo to the gallery.</p>
            <a class="btn btn-primary" href="<?= e(url('/register')) ?>">Create an account</a>
        </div>
    <?php else: ?>
        <div class="row g-4 mt-1">
            <?php foreach ($latestPhotos as $photo): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="photo-card">
                        <div class="photo-card-image">
                            <img src="<?= e(uploadUrl((string) $photo['file_name'])) ?>" alt="<?= e((string) $photo['title']) ?>">
                        </div>
                        <div class="photo-card-body">
                            <h3><a class="stretched-link" href="<?= e(url('/photo/' . $photo['id'])) ?>"><?= e((string) $photo['title']) ?></a></h3>
                            <p>By <a class="position-relative" style="z-index: 2;" href="<?= e(url('/user/' . $photo['user_id'])) ?>"><?= e((string) $photo['first_name']) ?> <?= e((string) $photo['last_name']) ?></a></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>