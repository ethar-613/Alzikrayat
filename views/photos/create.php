<section class="container section-padding">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="mb-4">Upload a Photo</h1>
            <form method="post" action="<?= e(url('/photo/store')) ?>" enctype="multipart/form-data" data-validate-form novalidate>
                <?= csrfField() ?>
                <div class="mb-3">
                    <label class="form-label" for="photo">Photo file</label>
                    <input class="form-control <?= isset($errors['photo']) ? 'is-invalid' : '' ?>" id="photo" name="photo" type="file" required accept="image/jpeg,image/png,image/gif,image/webp">
                    <div class="form-text">JPEG, PNG, GIF, or WebP - maximum 8 MB</div>
                    <?php if (isset($errors['photo'])): ?><div class="invalid-feedback"><?= e($errors['photo']) ?></div><?php endif; ?>
                </div>
                <div class="upload-preview d-none" id="uploadPreview">
                    <p class="mb-1"><small class="text-muted">Pick a filter below - it will be applied to the photo you upload, not just this preview.</small></p>
                    <canvas id="previewCanvas" aria-label="Preview of the selected image"></canvas>
                    <div class="filter-controls mt-2" role="group" aria-label="Image preview filters">
                        <button type="button" class="btn btn-sm btn-outline-secondary filter-button active" data-filter="natural">Natural</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary filter-button" data-filter="mono">Monochrome</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary filter-button" data-filter="warm">Warm</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary filter-button" data-filter="soft">Soft focus</button>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="title">Title</label>
                    <input class="form-control <?= isset($errors['title']) ? 'is-invalid' : '' ?>" id="title" name="title" type="text" value="<?= old('title') ?>" required maxlength="200" placeholder="e.g. Sunset at the beach">
                    <?php if (isset($errors['title'])): ?><div class="invalid-feedback"><?= e($errors['title']) ?></div><?php endif; ?>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="description">Description <span class="text-muted">(optional)</span></label>
                    <textarea class="form-control" id="description" name="description" rows="4" maxlength="5000" placeholder="Write something about this photo..."><?= old('description') ?></textarea>
                </div>
                <?php if (!empty($taggableUsers)): ?>
                    <div class="mb-3">
                        <label class="form-label">Tag people <span class="text-muted">(optional)</span></label>
                        <div class="tag-checklist border rounded p-2">
                            <?php foreach ($taggableUsers as $user): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="tags[]" value="<?= e((string) $user['id']) ?>" id="tag_<?= e((string) $user['id']) ?>">
                                    <label class="form-check-label" for="tag_<?= e((string) $user['id']) ?>"><?= e((string) $user['first_name']) ?> <?= e((string) $user['last_name']) ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="d-flex justify-content-end gap-2">
                    <a class="btn btn-outline-secondary" href="<?= e(url('/photos')) ?>">Cancel</a>
                    <button class="btn btn-primary" type="submit">Upload Photo</button>
                </div>
            </form>
        </div>
    </div>
</section>