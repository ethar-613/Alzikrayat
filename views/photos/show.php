<?php
// Only show the delete button to the person who actually owns this photo
$isOwner = $currentUser !== null && (int) $currentUser['id'] === (int) $photo['user_id'];
?>
<section class="container section-padding photo-detail-page">
    <div class="mb-4">
        <a class="back-link" href="<?= e(url('/photos')) ?>">← Back to gallery</a>
    </div>
    <div class="row g-5 align-items-start">
        <div class="col-lg-7">
            <div class="detail-image-frame">
                <img src="<?= e(uploadUrl((string) $photo['file_name'])) ?>" alt="<?= e((string) $photo['title']) ?>">
            </div>
        </div>
        <div class="col-lg-5">
            <span class="eyebrow">A shared memory</span>
            <h1 class="detail-title"><?= e((string) $photo['title']) ?></h1>
            <div class="detail-meta">Captured by <strong><?= e((string) $photo['first_name']) ?> <?= e((string) $photo['last_name']) ?></strong> · <?= e(date('M j, Y', strtotime((string) $photo['date_time']))) ?></div>
            <?php if (!empty($photo['description'])): ?>
                <p class="detail-description"><?= nl2br(e((string) $photo['description'])) ?></p>
            <?php endif; ?>
            <?php if ($isOwner): ?>
                <form method="post" action="<?= e(url('/photo/' . $photo['id'] . '/delete')) ?>" class="mt-4" onsubmit="return confirm('Delete this photo and its comments?');">
                    <?= csrfField() ?>
                    <button class="btn btn-outline-danger btn-sm" type="submit">Delete this photo</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <div class="row justify-content-center mt-5 pt-4" id="comments">
        <div class="col-lg-8">
            <div class="comments-heading d-flex justify-content-between align-items-end gap-3">
                <div>
                    <span class="eyebrow">The conversation</span>
                    <h2 class="section-title mb-0">Notes from the gallery</h2>
                </div>
                <span class="comment-count"><?= e((string) count($comments)) ?></span>
            </div>

            <?php if ($comments === []): ?>
                <div class="comment-empty">No notes yet. Be the first person to leave one.</div>
            <?php else: ?>
                <div class="comment-list">
                    <?php foreach ($comments as $comment): ?>
                        <article class="comment-item">
                            <div class="comment-avatar"><?= e(strtoupper(substr((string) $comment['first_name'], 0, 1))) ?></div>
                            <div>
                                <div class="comment-author"><?= e((string) $comment['first_name']) ?> <?= e((string) $comment['last_name']) ?> <span><?= e(date('M j, Y · g:i a', strtotime((string) $comment['date_time']))) ?></span></div>
                                <p><?= nl2br(e((string) $comment['comment'])) ?></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($currentUser !== null): ?>
                <form class="comment-form mt-4" method="post" action="<?= e(url('/photo/' . $photo['id'] . '/comments')) ?>" data-validate-form novalidate>
                    <?= csrfField() ?>
                    <label class="form-label" for="comment">Leave a note</label>
                    <textarea class="form-control <?= isset($errors['comment']) ? 'is-invalid' : '' ?>" id="comment" name="comment" rows="3" maxlength="2000" required placeholder="Say something kind or curious..."></textarea>
                    <?php if (isset($errors['comment'])): ?><div class="invalid-feedback"><?= e($errors['comment']) ?></div><?php endif; ?>
                    <button class="btn btn-dark mt-3" type="submit">Add comment <span aria-hidden="true">→</span></button>
                </form>
            <?php else: ?>
                <div class="login-comment-prompt mt-4">
                    <span>Want to join the conversation?</span>
                    <a href="<?= e(url('/login')) ?>">Log in to comment →</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>