/**
 *
 * Crop Image While Uploading With jQuery
 *
 * Copyright 2013, Resalat Haque
 * http://www.w3bees.com/
 *
 */

// set info for cropping image using hidden fields
function setInfo_cover(i, e) {
    jQuery('#a').val(e.x1);
    jQuery('#b').val(e.y1);
    jQuery('#c').val(e.width);
    jQuery('#d').val(e.height);
}

jQuery(document).ready(function() {
    var p = jQuery("#uploadPreview_cover");

    // prepare instant preview
    jQuery("#uploadImage_cover").change(function(){
        // fadeOut or hide preview
        p.fadeOut();

        // prepare HTML5 FileReader
        var oFReader = new FileReader();
        oFReader.readAsDataURL(document.getElementById("uploadImage_cover").files[0]);

        oFReader.onload = function (oFREvent) {
            p.attr('src', oFREvent.target.result).fadeIn();
        };
    });

    // implement imgAreaSelect plug in (http://odyniec.net/projects/imgareaselect/)
    jQuery('img#uploadPreview_cover').imgAreaSelect({
        // set crop ratio (optional)
//        aspectRatio: '1:1',
        handles: true,
        onSelectEnd: setInfo_cover
    });
});