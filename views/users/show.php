<section class="inner-hero">
    <div class="container">
        <h1 class="mb-1"><?= e((string) $profileUser['first_name']) ?> <?= e((string) $profileUser['last_name']) ?></h1>
        <?php if (!empty($profileUser['occupation'])): ?>
            <p class="mb-1 text-muted"><?= e((string) $profileUser['occupation']) ?></p>
        <?php endif; ?>
        <?php if (!empty($profileUser['location'])): ?>
            <p class="mb-1 text-muted">📍 <?= e((string) $profileUser['location']) ?></p>
        <?php endif; ?>
        <?php if (!empty($profileUser['description'])): ?>
            <p class="mb-0 mt-2"><?= nl2br(e((string) $profileUser['description'])) ?></p>
        <?php endif; ?>
    </div>
</section>

<section class="container section-padding">
    <h2 class="section-title mb-3">Photos by <?= e((string) $profileUser['first_name']) ?> (<?= e((string) count($ownPhotos)) ?>)</h2>

    <?php if ($ownPhotos === []): ?>
        <div class="empty-state mb-5">
            <p class="mb-0">This user has not uploaded any photos yet.</p>
        </div>
    <?php else: ?>
        <div class="gallery-grid gallery-style-grid-3 mb-5">
            <?php foreach ($ownPhotos as $photo): ?>
                <a href="<?= e(url('/photo/' . $photo['id'])) ?>" class="photo-card">
                    <div class="photo-card-image">
                        <img src="<?= e(uploadUrl((string) $photo['file_name'])) ?>" alt="<?= e((string) $photo['title']) ?>" loading="lazy">
                    </div>
                    <div class="photo-card-body">
                        <h3><?= e((string) $photo['title']) ?></h3>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($taggedPhotos !== []): ?>
        <h2 class="section-title mb-3">Tagged in (<?= e((string) count($taggedPhotos)) ?>)</h2>
        <div class="gallery-grid gallery-style-grid-3">
            <?php foreach ($taggedPhotos as $photo): ?>
                <div class="photo-card">
                    <div class="photo-card-image">
                        <img src="<?= e(uploadUrl((string) $photo['file_name'])) ?>" alt="<?= e((string) $photo['title']) ?>" loading="lazy">
                    </div>
                    <div class="photo-card-body">
                        <h3><a class="stretched-link" href="<?= e(url('/photo/' . $photo['id'])) ?>"><?= e((string) $photo['title']) ?></a></h3>
                        <p>By <a class="position-relative" style="z-index: 2;" href="<?= e(url('/user/' . $photo['user_id'])) ?>"><?= e((string) $photo['first_name']) ?> <?= e((string) $photo['last_name']) ?></a></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
