<section class="container section-padding upload-page">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="upload-shell">
                <div class="upload-intro">
                    <span class="eyebrow">Add to the archive</span>
                    <h1>What would you like to remember?</h1>
                    <p>Give your photo a little context. A good title can bring an entire day back.</p>
                    <div class="upload-rule"><span></span><span></span><span></span></div>
                </div>
                <div class="upload-form-panel">
                    <form method="post" action="<?= e(url('/photo/store')) ?>" enctype="multipart/form-data" data-validate-form novalidate>
                        <?= csrfField() ?>
                        <div class="mb-4">
                            <label class="form-label" for="photo">Photo file</label>
                            <input class="form-control form-control-lg <?= isset($errors['photo']) ? 'is-invalid' : '' ?>" id="photo" name="photo" type="file" required accept="image/jpeg,image/png,image/gif,image/webp">
                            <div class="form-hint">JPEG, PNG, GIF, or WebP · maximum 8 MB</div>
                            <?php if (isset($errors['photo'])): ?><div class="invalid-feedback"><?= e($errors['photo']) ?></div><?php endif; ?>
                        </div>
                        <div class="upload-preview d-none" id="uploadPreview">
                            <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                <span class="eyebrow mb-0">A little preview</span>
                                <span class="form-hint">Filters affect the preview only.</span>
                            </div>
                            <canvas id="previewCanvas" aria-label="Preview of the selected image"></canvas>
                            <div class="filter-controls mt-3" role="group" aria-label="Image preview filters">
                                <button type="button" class="filter-button active" data-filter="natural">Natural</button>
                                <button type="button" class="filter-button" data-filter="mono">Monochrome</button>
                                <button type="button" class="filter-button" data-filter="warm">Warm</button>
                                <button type="button" class="filter-button" data-filter="soft">Soft focus</button>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="title">Title</label>
                            <input class="form-control form-control-lg <?= isset($errors['title']) ? 'is-invalid' : '' ?>" id="title" name="title" type="text" value="<?= old('title') ?>" required maxlength="200" placeholder="For example: The road home">
                            <?php if (isset($errors['title'])): ?><div class="invalid-feedback"><?= e($errors['title']) ?></div><?php endif; ?>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="description">Story behind the photo <span class="optional-label">Optional</span></label>
                            <textarea class="form-control" id="description" name="description" rows="5" maxlength="5000" placeholder="Where were you? What should you remember about this moment?"><?= old('description') ?></textarea>
                        </div>
                        <div class="d-flex flex-column flex-sm-row justify-content-end gap-3">
                            <a class="btn btn-light border" href="<?= e(url('/photos')) ?>">Cancel</a>
                            <button class="btn btn-coral btn-lg px-4" type="submit">Publish memory <span aria-hidden="true">↗</span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>