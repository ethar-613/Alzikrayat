<?php
$styleLabels = [
    'grid-3' => 'Three columns',
    'grid-4' => 'Four columns',
    'list' => 'List view',
    'featured' => 'Featured',
];
?>
<section class="inner-hero inner-hero-gallery">
    <div class="container d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-4">
        <div>
            <span class="eyebrow">The community album</span>
            <h1 class="display-heading mb-2">Recent memories</h1>
            <p class="lead-copy mb-0">A collection of ordinary moments worth keeping close.</p>
        </div>
        <?php if ($currentUser !== null): ?>
            <a class="btn btn-coral btn-lg" href="<?= e(url('/photo/create')) ?>">Share a memory <span aria-hidden="true">↗</span></a>
        <?php endif; ?>
    </div>
</section>

<section class="container section-padding pt-5">
    <div class="gallery-toolbar d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <span class="eyebrow">Display style</span>
            <strong><?= e($styleLabels[$style]) ?></strong>
        </div>
        <div class="view-switcher" role="group" aria-label="Choose gallery display style">
            <?php foreach ($styleLabels as $styleKey => $label): ?>
                <a class="<?= $style === $styleKey ? 'active' : '' ?>" href="<?= e(url('/photos?view=' . $styleKey)) ?>"><?= e($label) ?></a>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if ($photos === []): ?>
        <div class="empty-state mt-4">
            <div class="empty-icon">✦</div>
            <h2>The gallery is waiting for its first frame.</h2>
            <p>Share something meaningful and give the album a beginning.</p>
            <?php if ($currentUser === null): ?>
                <a class="btn btn-dark" href="<?= e(url('/login')) ?>">Log in to share</a>
            <?php else: ?>
                <a class="btn btn-dark" href="<?= e(url('/photo/create')) ?>">Upload a photo</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="gallery-grid gallery-style-<?= e($style) ?> mt-4">
            <?php foreach ($photos as $photo): ?>
                <a href="<?= e(url('/photo/' . $photo['id'])) ?>" class="photo-card">
                    <div class="photo-card-image">
                        <img src="<?= e(uploadUrl((string) $photo['file_name'])) ?>" alt="<?= e((string) $photo['title']) ?>" loading="lazy">
                    </div>
                    <div class="photo-card-body">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <h2><?= e((string) $photo['title']) ?></h2>
                                <p>By <?= e((string) $photo['first_name']) ?> <?= e((string) $photo['last_name']) ?></p>
                            </div>
                            <span class="arrow-chip" aria-hidden="true">↗</span>
                        </div>
                        <?php if ($style === 'list' && !empty($photo['description'])): ?>
                            <div class="photo-list-description"><?= e((string) $photo['description']) ?></div>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>