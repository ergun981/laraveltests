function fixThumbnailMargins() {
        $('.row-fluid .thumbnails').each(function () {
            var $thumbnails = $(this).children(),
            previousOffsetLeft = $thumbnails.first().offset().left;
            $thumbnails.removeClass('fir');
            $thumbnails.first().addClass('fir');
            $thumbnails.each(function () {
                var $thumbnail = $(this),
                offsetLeft = $thumbnail.offset().left;
                if (offsetLeft < previousOffsetLeft) {
                    $thumbnail.addClass('fir');
                }
                previousOffsetLeft = offsetLeft;
            });
        });
}


jQuery(document).ready(function($) {
    fixThumbnailMargins();
});