/**
 * Created with JetBrains PhpStorm.
 * User: FUHU
 * Date: 12/6/13
 * Time: 3:52 PM
 * To change this template use File | Settings | File Templates.
 */

// set info for cropping image using hidden fields
function setInfo(i, e) {
    jQuery('#x').val(e.x1);
    jQuery('#y').val(e.y1);
    jQuery('#w').val(e.width);
    jQuery('#h').val(e.height);

    }

jQuery(document).ready(function() {
    jQuery('#st-accordion').accordion();


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
    aspectRatio: '4:3',
    handles: true,
    onSelectEnd: setInfo
    });
});
