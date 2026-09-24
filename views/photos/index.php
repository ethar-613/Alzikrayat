<?php
$styleLabels = [
    'grid-3' => 'Three columns',
    'grid-4' => 'Four columns',
    'list' => 'List view',
    'featured' => 'Featured',
];
?>
<section class="inner-hero">
    <div class="container d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
        <h1 class="mb-0">Community Album</h1>
        <?php if ($currentUser !== null): ?>
            <a class="btn btn-primary" href="<?= e(url('/photo/create')) ?>" style="background-color:#8929f7;">Upload Photo</a>
        <?php endif; ?>
    </div>
</section>

<section class="container section-padding">
    <div class="gallery-toolbar d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>Display style: <strong><?= e($styleLabels[$style]) ?></strong></div>
        <div class="view-switcher btn-group" role="group" aria-label="Choose gallery display style">
            <?php foreach ($styleLabels as $styleKey => $label): ?>
                <a class="btn btn-sm btn-outline-secondary <?= $style === $styleKey ? 'active' : '' ?>" href="<?= e(url('/photos?view=' . $styleKey)) ?>"><?= e($label) ?></a>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if ($photos === []): ?>
        <div class="empty-state">
            <h2>No photos yet.</h2>
            <p>Be the first to share something with the community.</p>
            <?php if ($currentUser === null): ?>
                <a class="btn btn-primary" href="<?= e(url('/login')) ?>">Log in to upload</a>
            <?php else: ?>
                <a class="btn btn-primary" href="<?= e(url('/photo/create')) ?>">Upload a photo</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="gallery-grid gallery-style-<?= e($style) ?>">
            <?php foreach ($photos as $photo): ?>
                <div class="photo-card">
                    <div class="photo-card-image">
                        <img src="<?= e(uploadUrl((string) $photo['file_name'])) ?>" alt="<?= e((string) $photo['title']) ?>" loading="lazy">
                    </div>
                    <div class="photo-card-body">
                        <h2><a class="stretched-link" href="<?= e(url('/photo/' . $photo['id'])) ?>"><?= e((string) $photo['title']) ?></a></h2>
                        <p>By <a class="position-relative" style="z-index: 2;" href="<?= e(url('/user/' . $photo['user_id'])) ?>"><?= e((string) $photo['first_name']) ?> <?= e((string) $photo['last_name']) ?></a></p>
                        <?php if ($style === 'list' && !empty($photo['description'])): ?>
                            <p class="photo-list-description"><?= e((string) $photo['description']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>