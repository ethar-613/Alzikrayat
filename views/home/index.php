<section class="hero-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="eyebrow">A little archive of life</span>
                <h1 class="display-heading">Some moments deserve more than a camera roll.</h1>
                <p class="lead-copy">Alzikrayat is a warm, shared album for the places, people, and passing details you want to remember.</p>
                <div class="d-flex flex-wrap gap-3 mt-4">
                    <a class="btn btn-coral btn-lg px-4" href="<?= e(url('/photos')) ?>">Explore the gallery <span aria-hidden="true">↗</span></a>
                    <a class="btn btn-light btn-lg px-4 border" href="<?= e(url('/about')) ?>">How it works</a>
                </div>
                <div class="hero-stats d-flex flex-wrap gap-4 mt-5">
                    <div><strong><?= e((string) $photoCount) ?></strong><span>memories shared</span></div>
                    <div><strong><?= e((string) $userCount) ?></strong><span>people here</span></div>
                    <div><strong>∞</strong><span>stories to tell</span></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-collage">
                    <div class="hero-photo hero-photo-main"></div>
                    <div class="hero-photo hero-photo-small"></div>
                    <div class="collage-note"><span class="note-dot"></span><span>Made for the memories between the milestones.</span></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container section-padding">
    <div class="section-heading d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
        <div>
            <span class="eyebrow">From the community</span>
            <h2 class="section-title mb-0">Recently remembered</h2>
        </div>
        <a class="text-link" href="<?= e(url('/photos')) ?>">View every memory <span aria-hidden="true">→</span></a>
    </div>

    <?php if ($latestPhotos === []): ?>
        <div class="empty-state mt-4">
            <div class="empty-icon">✦</div>
            <h3>There is room for your first story.</h3>
            <p>Sign in and share a photo to start the community gallery.</p>
            <a class="btn btn-dark" href="<?= e(url('/register')) ?>">Create an account</a>
        </div>
    <?php else: ?>
        <div class="row g-4 mt-1">
            <?php foreach ($latestPhotos as $photo): ?>
                <div class="col-md-6 col-lg-4">
                    <a href="<?= e(url('/photo/' . $photo['id'])) ?>" class="photo-card photo-card-home">
                        <div class="photo-card-image">
                            <img src="<?= e(uploadUrl((string) $photo['file_name'])) ?>" alt="<?= e((string) $photo['title']) ?>">
                        </div>
                        <div class="photo-card-body">
                            <div class="d-flex justify-content-between gap-3">
                                <div>
                                    <h3><?= e((string) $photo['title']) ?></h3>
                                    <p>By <?= e((string) $photo['first_name']) ?> <?= e((string) $photo['last_name']) ?></p>
                                </div>
                                <span class="arrow-chip" aria-hidden="true">↗</span>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section class="container section-padding pt-0">
    <div class="quote-panel">
        <span class="quote-mark">“</span>
        <p>We take photos because we know that one day, the smallest details will be the ones we miss most.</p>
        <span class="quote-credit">The Alzikrayat principle</span>
    </div>
</section>