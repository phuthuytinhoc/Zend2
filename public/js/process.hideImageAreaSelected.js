/**
 * Created with JetBrains PhpStorm.
 * User: FUHU
 * Date: 12/8/13
 * Time: 4:22 PM
 * To change this template use File | Settings | File Templates.
 */

jQuery('#lightbox').on('blur click',function(){
    jQuery('#uploadPreview').imgAreaSelect( {hide: true} );

});

jQuery('#bottomNavClose').click(function(){

    jQuery('#uploadPreview').imgAreaSelect( {hide: true} );
});