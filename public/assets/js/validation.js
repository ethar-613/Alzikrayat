/**
 * Lightweight client-side validation layer.
 *
 * HTML5 attributes remain active; this script adds friendly feedback for
 * empty fields and prevents accidental duplicate submissions.
 */
(function () {
    'use strict';

    document.querySelectorAll('[data-validate-form]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            var firstInvalid = null;

            form.querySelectorAll('input, textarea, select').forEach(function (field) {
                field.classList.remove('is-invalid');
                if (!field.checkValidity() && !firstInvalid) {
                    firstInvalid = field;
                }
            });

            if (firstInvalid) {
                event.preventDefault();
                firstInvalid.classList.add('is-invalid');
                firstInvalid.focus();
            } else {
                var submitButton = form.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.dataset.originalLabel = submitButton.innerHTML;
                    submitButton.innerHTML = 'Saving…';
                }
            }
        });

        form.querySelectorAll('input, textarea, select').forEach(function (field) {
            field.addEventListener('input', function () {
                if (field.checkValidity()) {
                    field.classList.remove('is-invalid');
                }
            });
        });
    });

    /* Novelty feature: a small canvas filter preview for the selected upload.
       The original file is still submitted unchanged; this is a visual aid. */
    var photoInput = document.getElementById('photo');
    var preview = document.getElementById('uploadPreview');
    var canvas = document.getElementById('previewCanvas');
    var previewImage = new Image();
    var originalPixels = null;

    function drawPreview(filterName) {
        if (!canvas || !originalPixels) {
            return;
        }

        var context = canvas.getContext('2d');
        var pixels = new ImageData(new Uint8ClampedArray(originalPixels.data), originalPixels.width, originalPixels.height);

        for (var index = 0; index < pixels.data.length; index += 4) {
            var red = pixels.data[index];
            var green = pixels.data[index + 1];
            var blue = pixels.data[index + 2];

            if (filterName === 'mono') {
                var gray = (red * 0.299) + (green * 0.587) + (blue * 0.114);
                pixels.data[index] = gray;
                pixels.data[index + 1] = gray;
                pixels.data[index + 2] = gray;
            } else if (filterName === 'warm') {
                pixels.data[index] = Math.min(255, red + 18);
                pixels.data[index + 1] = Math.min(255, green + 7);
                pixels.data[index + 2] = Math.max(0, blue - 12);
            } else if (filterName === 'soft') {
                pixels.data[index] = Math.min(255, (red * 0.82) + 34);
                pixels.data[index + 1] = Math.min(255, (green * 0.82) + 34);
                pixels.data[index + 2] = Math.min(255, (blue * 0.82) + 34);
            }
        }

        context.putImageData(pixels, 0, 0);
    }

    if (photoInput && preview && canvas) {
        photoInput.addEventListener('change', function () {
            var file = photoInput.files && photoInput.files[0];
            if (!file || !file.type.match(/^image\//)) {
                preview.classList.add('d-none');
                return;
            }

            var reader = new FileReader();
            reader.addEventListener('load', function () {
                previewImage.onload = function () {
                    var maxWidth = 900;
                    var scale = Math.min(1, maxWidth / previewImage.naturalWidth);
                    canvas.width = Math.round(previewImage.naturalWidth * scale);
                    canvas.height = Math.round(previewImage.naturalHeight * scale);
                    var context = canvas.getContext('2d');
                    context.drawImage(previewImage, 0, 0, canvas.width, canvas.height);
                    originalPixels = context.getImageData(0, 0, canvas.width, canvas.height);
                    preview.classList.remove('d-none');
                };
                previewImage.src = reader.result;
            });
            reader.readAsDataURL(file);
        });

        document.querySelectorAll('[data-filter]').forEach(function (button) {
            button.addEventListener('click', function () {
                document.querySelectorAll('[data-filter]').forEach(function (item) {
                    item.classList.remove('active');
                });
                button.classList.add('active');
                drawPreview(button.dataset.filter);
            });
        });
    }
})();