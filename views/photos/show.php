<?php
// Only show the delete button to the person who actually owns this photo
$isOwner = $currentUser !== null && (int) $currentUser['id'] === (int) $photo['user_id'];
?>
<section class="container section-padding">
    <div class="mb-4">
        <a href="<?= e(url('/photos')) ?>">&laquo; Back to gallery</a>
    </div>
    <div class="row g-4 align-items-start">
        <div class="col-lg-7">
            <div class="detail-image-frame">
                <img src="<?= e(uploadUrl((string) $photo['file_name'])) ?>" alt="<?= e((string) $photo['title']) ?>">
            </div>
        </div>
        <div class="col-lg-5">
            <h1><?= e((string) $photo['title']) ?></h1>
            <p class="text-muted">By <strong><a href="<?= e(url('/user/' . $photo['user_id'])) ?>"><?= e((string) $photo['first_name']) ?> <?= e((string) $photo['last_name']) ?></a></strong> - <?= e(date('M j, Y', strtotime((string) $photo['date_time']))) ?></p>
            <?php if (!empty($photo['description'])): ?>
                <p><?= nl2br(e((string) $photo['description'])) ?></p>
            <?php endif; ?>

            <div id="tags">
                <?php if (!empty($taggedUsers)): ?>
                    <p class="mb-2">
                        <strong>Tagged:</strong>
                        <?php foreach ($taggedUsers as $index => $taggedUser): ?><?= $index > 0 ? ', ' : ' ' ?><a href="<?= e(url('/user/' . $taggedUser['id'])) ?>"><?= e((string) $taggedUser['first_name']) ?> <?= e((string) $taggedUser['last_name']) ?></a><?php endforeach; ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($taggableUsers)): ?>
                    <details class="mb-3">
                        <summary class="text-muted" style="cursor: pointer;">Tag people in this photo</summary>
                        <form method="post" action="<?= e(url('/photo/' . $photo['id'] . '/tags')) ?>" class="mt-2">
                            <?= csrfField() ?>
                            <div class="tag-checklist border rounded p-2 mb-2">
                                <?php foreach ($taggableUsers as $user): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="tags[]" value="<?= e((string) $user['id']) ?>" id="show_tag_<?= e((string) $user['id']) ?>">
                                        <label class="form-check-label" for="show_tag_<?= e((string) $user['id']) ?>"><?= e((string) $user['first_name']) ?> <?= e((string) $user['last_name']) ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button class="btn btn-outline-secondary btn-sm" type="submit">Add Tags</button>
                        </form>
                    </details>
                <?php endif; ?>
            </div>

            <?php if ($isOwner): ?>
                <form method="post" action="<?= e(url('/photo/' . $photo['id'] . '/delete')) ?>" class="mt-3" onsubmit="return confirm('Delete this photo and its comments?');">
                    <?= csrfField() ?>
                    <button class="btn btn-outline-danger btn-sm" type="submit">Delete this photo</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <div class="row justify-content-center mt-5 pt-3" id="comments">
        <div class="col-lg-8">
            <h2 class="section-title mb-3">Comments (<?= e((string) count($comments)) ?>)</h2>

            <?php if ($comments === []): ?>
                <p class="text-muted">No comments yet. Be the first to leave one!</p>
            <?php else: ?>
                <div class="comment-list">
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment-item">
                            <div class="comment-avatar"><?= e(strtoupper(substr((string) $comment['first_name'], 0, 1))) ?></div>
                            <div>
                                <div><strong><a href="<?= e(url('/user/' . $comment['user_id'])) ?>"><?= e((string) $comment['first_name']) ?> <?= e((string) $comment['last_name']) ?></a></strong> <small class="text-muted"><?= e(date('M j, Y g:i a', strtotime((string) $comment['date_time']))) ?></small></div>
                                <p class="mb-0"><?= nl2br(e((string) $comment['comment'])) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($currentUser !== null): ?>
                <form class="mt-4" method="post" action="<?= e(url('/photo/' . $photo['id'] . '/comments')) ?>" data-validate-form novalidate>
                    <?= csrfField() ?>
                    <label class="form-label" for="comment">Add a comment</label>
                    <textarea class="form-control <?= isset($errors['comment']) ? 'is-invalid' : '' ?>" id="comment" name="comment" rows="3" maxlength="2000" required placeholder="Write a comment..."></textarea>
                    <?php if (isset($errors['comment'])): ?><div class="invalid-feedback"><?= e($errors['comment']) ?></div><?php endif; ?>
                    <button class="btn btn-primary mt-2" type="submit">Add Comment</button>
                </form>
            <?php else: ?>
                <div class="alert alert-light border mt-4 d-flex justify-content-between align-items-center">
                    <span>Want to join the conversation?</span>
                    <a href="<?= e(url('/login')) ?>">Log in to comment</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>