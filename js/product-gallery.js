
jQuery(document).ready(function ($) {

    let mediaFrame;

    const galleryInput = $('#product_gallery_ids');
    const galleryPreview = $('#product-gallery-preview');

    $('#add-gallery-images').on('click', function (e) {

        e.preventDefault();

        if (mediaFrame) {
            mediaFrame.open();
            return;
        }

        mediaFrame = wp.media({
            title: 'Select Product Images',
            button: {
                text: 'Use These Images'
            },
            multiple: true
        });


        
mediaFrame.on('select', function () {

    const attachments = mediaFrame
        .state()
        .get('selection')
        .toJSON();

    // Existing gallery IDs
    let existingIds = galleryInput
        .val()
        .split(',')
        .filter(function (id) {
            return id !== '';
        });

    // Add newly selected IDs
    attachments.forEach(function (attachment) {

        const imageId = String(attachment.id);

        if (!existingIds.includes(imageId)) {
            existingIds.push(imageId);
        }

    });

    // Remove duplicates just in case
    existingIds = [...new Set(existingIds)];

    // Save IDs
    galleryInput.val(existingIds.join(','));

    // Add only the newly selected images to the preview
    attachments.forEach(function (attachment) {

        const imageId = String(attachment.id);

        // Don't add a duplicate preview
        if (
            galleryPreview.find(
                `.gallery-item[data-id="${imageId}"]`
            ).length
        ) {
            return;
        }

        galleryPreview.append(`
            <div class="gallery-item" data-id="${imageId}">

                <img
                    src="${attachment.url}"
                    style="width:100px;height:100px;object-fit:cover;"
                >

                <button
                    type="button"
                    class="remove-gallery-image"
                    data-id="${imageId}"
                >
                    Remove
                </button>

            </div>
        `);

    });

    console.log('Gallery IDs:', galleryInput.val());

});


        mediaFrame.open();

    });



    galleryPreview.on('click', '.remove-gallery-image', function (e) {

        e.preventDefault();

        const button = $(this);

        const imageId = String(button.data('id'));

        const galleryItem = button.closest('.gallery-item');


        let imageIds = galleryInput
            .val()
            .split(',')
            .filter(function (id) {
                return id !== '';
            });


        imageIds = imageIds.filter(function (id) {
            return String(id) !== imageId;
        });


        galleryInput.val(imageIds.join(','));

        galleryItem.remove();


        console.log('Removed image:', imageId);
        console.log('Updated gallery IDs:', galleryInput.val());

    });

});

