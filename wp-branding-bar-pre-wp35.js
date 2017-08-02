jQuery(document).ready(function($) {
  $('#upload-left-image-button').click(function() {
    tb_show('Upload a Left Image', 'media-upload.php?referer=wp-bb--settings&type=image&TB_iframe=true&post_id=0', false);
    right_val = $('#wp-bb--right-image-string').val();
    bar_val = $('#wp-bb--bar-background-string').val();
    window.send_to_editor = function(html) {
      var image_url = $('img',html).attr('src');
      $('#wp-bb--left-image-string').val(image_url);
      $('#wp-bb--right-image-string').val(right_val);
      $('#wp-bb--bar-background-string').val(bar_val);
      tb_remove();
      $('#upload-left-image-preview img').attr('src',image_url);
      $('#submit').trigger('click');
    };
    return false;
  });
  $('#upload-right-image-button').click(function() {
    tb_show('Upload a Right Image', 'media-upload.php?referer=wp-bb--settings&type=image&TB_iframe=true&post_id=0', false);
    left_val = $('#wp-bb--left-image-string').val();
    bar_val = $('#wp-bb--bar-background-string').val();
    window.send_to_editor = function(html) {
      var image_url = $('img',html).attr('src');
      $('#wp-bb--left-image-string').val(left_val);
      $('#wp-bb--right-image-string').val(image_url);
      $('#wp-bb--bar-background-string').val(bar_val);
      tb_remove();
      $('#upload-right-image-preview img').attr('src',image_url);
      $('#submit').trigger('click');
    };
    return false;
  });
  $('#upload-bar-background-button').click(function() {
    tb_show('Upload a Background Image', 'media-upload.php?referer=wp-bb--settings&type=image&TB_iframe=true&post_id=0', false);
    left_val = $('#wp-bb--left-image-string').val();
    right_val = $('#wp-bb--right-image-string').val();
    window.send_to_editor = function(html) {
      var image_url = $('img',html).attr('src');
      $('#wp-bb--left-image-string').val(left_val);
      $('#wp-bb--right-image-string').val(right_val);
      $('#wp-bb--bar-background-string').val(image_url);
      tb_remove();
      $('#wp-bb--bar-background-string').attr('src',image_url);
      $('#submit').trigger('click');
    };
    return false;
  });
});
