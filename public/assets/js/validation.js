/**
 * Lightweight client-side validation layer.
 *
 * HTML5 attributes remain active; this script adds friendly feedback for
 * empty fields and prevents accidental duplicate submissions.
 * 
 * it also handle filter image canvas feature
 * and Dark/Light feature
 */


(function () {
    'use strict';

    /* here it check for the client-side validity of data
       it take every form that have the property [data-validate-form] to check its input when submit
       then check every input, textarea, select.  with checkValidity() that automatically validate against 
       any HTML5 attribute present on the field itself.
       and use firstInvalid to store only the first invalid field to direct user attention to the nearest issue to fix first
       rather than overwlem them with multiple messages at once */
    document.querySelectorAll('[data-validate-form]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            var firstInvalid = null;

            form.querySelectorAll('input, textarea, select').forEach(function (field) {
                field.classList.remove('is-invalid');
                if (!field.checkValidity() && !firstInvalid) {
                    firstInvalid = field;
                }
            });

            /* if found invalid field it will pervent form submitted, 
               and add css class "is-invalid" that turn the field to red, and focus on it
               so the user can see immediatly where the problem in.
               if everything is valid then disable submit button and change it to 'Saving..' */

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

        // when user still typing and the field is valid now remove the red color immediatly as feedback means its good now
        form.querySelectorAll('input, textarea, select').forEach(function (field) {
            field.addEventListener('input', function () {
                if (field.checkValidity()) {
                    field.classList.remove('is-invalid');
                }
            });
        });
    });


    
    /* Novelty feature: a canvas-based photo filter.
       The chosen filter is shown as a live preview, and is then "baked"
       into the actual file before it is uploaded, so the stored photo
       keeps the filter -- not just the on-screen preview. */
    var photoInput = document.getElementById('photo');
    var preview = document.getElementById('uploadPreview');
    var canvas = document.getElementById('previewCanvas');
    var uploadForm = photoInput ? photoInput.closest('form') : null;
    var previewImage = new Image();
    var originalPixels = null;
    var selectedFilter = 'natural';

    // Returns a new ImageData with the filter's pixel math applied.
    // Used both for the small on-screen preview and the full-size export.
    function applyFilter(imageData, filterName) {
        var pixels = new ImageData(new Uint8ClampedArray(imageData.data), imageData.width, imageData.height);

        for (var index = 0; index < pixels.data.length; index += 4) {
            var red = pixels.data[index];
            var green = pixels.data[index + 1];
            var blue = pixels.data[index + 2];

            // this is the grayscale filter that using the true luminosity equation here
            if (filterName === 'mono') {
                var gray = (red * 0.299) + (green * 0.587) + (blue * 0.114);
                pixels.data[index] = gray;
                pixels.data[index + 1] = gray;
                pixels.data[index + 2] = gray;
            // the warm filter that increase the red more than the green and reduced the blue 
            // used Math.min Math.max to prevent overflow/underflow  
            } else if (filterName === 'warm') {
                pixels.data[index] = Math.min(255, red + 18);
                pixels.data[index + 1] = Math.min(255, green + 7);
                pixels.data[index + 2] = Math.max(0, blue - 12);
            // for soft filter reduce contrast and increase brightness
            } else if (filterName === 'soft') {
                pixels.data[index] = Math.min(255, (red * 0.82) + 34);
                pixels.data[index + 1] = Math.min(255, (green * 0.82) + 34);
                pixels.data[index + 2] = Math.min(255, (blue * 0.82) + 34);
            }
        }

        return pixels;
    }
    // to draw preview for the image on upload page
    function drawPreview(filterName) {
        if (!canvas || !originalPixels) {
            return;
        }

        canvas.getContext('2d').putImageData(applyFilter(originalPixels, filterName), 0, 0);
    }

    if (photoInput && preview && canvas) {
        photoInput.addEventListener('change', function () {
            selectedFilter = 'natural';
            document.querySelectorAll('[data-filter]').forEach(function (item) {
                item.classList.toggle('active', item.dataset.filter === 'natural');
            });

            var file = photoInput.files && photoInput.files[0];
            if (!file || !file.type.match(/^image\//)) {
                preview.classList.add('d-none');
                originalPixels = null;
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
                selectedFilter = button.dataset.filter;
                drawPreview(selectedFilter);
            });
        });
    }

    // Redraws the chosen filter at the photo's full resolution (the on-screen
    // canvas is shrunk down for speed) and replaces the file input's file
    // with the filtered version, then submits the form for real.
    function submitWithFilterApplied(form) {
        if (selectedFilter === 'natural' || !previewImage.naturalWidth) {
            form.submit();
            return;
        }

        var fullCanvas = document.createElement('canvas');
        fullCanvas.width = previewImage.naturalWidth;
        fullCanvas.height = previewImage.naturalHeight;

        var context = fullCanvas.getContext('2d');
        context.drawImage(previewImage, 0, 0);
        var fullImageData = context.getImageData(0, 0, fullCanvas.width, fullCanvas.height);
        context.putImageData(applyFilter(fullImageData, selectedFilter), 0, 0);

        fullCanvas.toBlob(function (blob) {
            if (blob) {
                var filteredFile = new File([blob], 'photo-' + selectedFilter + '.jpg', { type: 'image/jpeg' });
                var fileList = new DataTransfer();
                fileList.items.add(filteredFile);
                photoInput.files = fileList.files;
            }
            form.submit();
        }, 'image/jpeg', 0.92);
    }

    if (uploadForm) {
        uploadForm.addEventListener('submit', function (event) {
            if (!uploadForm.checkValidity()) {
                return; // the generic validation handler above will report this
            }

            event.preventDefault();
            submitWithFilterApplied(uploadForm);
        });
    }

    /* Dark / Light mode toggle
       Bootstrap 5 color modes are driven entirely by the data-bs-theme
       attribute on <html>. The server already reads the "theme" cookie and
       sets that attribute on first render (views/layout/main.php), so there
       is no flash of the wrong theme -- this click handler only needs to
       flip it afterwards and remember the choice for next time. */
    var themeToggleButton = document.getElementById('themeToggle');
    var themeToggleIcon = document.getElementById('themeToggleIcon');

    if (themeToggleButton && themeToggleIcon) {
        themeToggleButton.addEventListener('click', function () {
            var htmlElement = document.documentElement;
            var nextTheme = htmlElement.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';

            htmlElement.setAttribute('data-bs-theme', nextTheme);
            themeToggleIcon.textContent = nextTheme === 'dark'
                ? themeToggleButton.dataset.darkIcon
                : themeToggleButton.dataset.lightIcon;

            var oneYearInSeconds = 60 * 60 * 24 * 365;
            document.cookie = 'theme=' + nextTheme + '; path=/; max-age=' + oneYearInSeconds + '; samesite=lax';
        });
    }
})();
