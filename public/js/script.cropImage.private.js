/**
 *
 * Crop Image While Uploading With jQuery
 *
 * Copyright 2013, Resalat Haque
 * http://www.w3bees.com/
 *
 */

// set info for cropping image using hidden fields
function setInfo(i, e) {
    jQuery('#x').val(e.x1);
    jQuery('#y').val(e.y1);
    jQuery('#w').val(e.width);
    jQuery('#h').val(e.height);
}

jQuery(document).ready(function() {
    var p = jQuery("#uploadPreview");

    // prepare instant preview
    jQuery("#uploadImage").change(function(){
        // fadeOut or hide preview
        p.fadeOut();

        // prepare HTML5 FileReader
        var oFReader = new FileReader();
        oFReader.readAsDataURL(document.getElementById("uploadImage").files[0]);

        oFReader.onload = function (oFREvent) {
            p.attr('src', oFREvent.target.result).fadeIn();
        };
    });

    // implement imgAreaSelect plug in (http://odyniec.net/projects/imgareaselect/)
    jQuery('img#uploadPreview').imgAreaSelect({
        // set crop ratio (optional)
        aspectRatio: '1:1',
        handles: true,
        onSelectEnd: setInfo
    });
});