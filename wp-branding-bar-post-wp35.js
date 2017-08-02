jQuery(document).ready(function($) {
  var custom_uploader;

  $('#upload-left-image-button').click(function(e) {
    e.preventDefault();
    //If the uploader object has already been created, reopen the dialog
    if (custom_uploader) {
      custom_uploader.open();
      return;
    }    
    //Extend the wp.media object
    custom_uploader = wp.media.frames.file_frame = wp.media({
        title: 'Choose Image',
        button: {
            text: 'Choose Image'
        },
        multiple: false,
    });
    //When a file is selected, grab the URL set it as the text field's value
    custom_uploader.on('select', function() {
      var attachment = custom_uploader.state().get('selection').first().toJSON();
      $('#wp-bb--left-image-string').val(attachment.url);
      $('#upload-left-image-preview img').attr('src',attachment.url);
      $('#submit').trigger('click');
    });
    //Open the uploader dialog
    custom_uploader.open();
  });
  $('#upload-right-image-button').click(function(e) {
    e.preventDefault();
    //If the uploader object has already been created, reopen the dialog
    if (custom_uploader) {
      custom_uploader.open();
      return;
    }
    //Extend the wp.media object
    custom_uploader = wp.media.frames.file_frame = wp.media({
        title: 'Choose Image',
        button: {
            text: 'Choose Image'
        },
        multiple: false,
    });
    //When a file is selected, grab the URL and set it as the text field's value
    custom_uploader.on('select', function() {
        var attachment = custom_uploader.state().get('selection').first().toJSON();
      $('#wp-bb--right-image-string').val(attachment.url);
      $('#upload-right-image-preview img').attr('src',attachment.url);
      $('#submit').trigger('click');
    });
    //Open the uploader dialog
    custom_uploader.open();
  });
  $('#upload-bar-background-button').click(function(e) {
    e.preventDefault();
    //If the uploader object has already been created, reopen the dialog
    if (custom_uploader) {
      custom_uploader.open();
      return;
    }
    //Extend the wp.media object
    custom_uploader = wp.media.frames.file_frame = wp.media({
        title: 'Choose Image',
        button: {
            text: 'Choose Image'
        },
        multiple: false,
    });
    //When a file is selected, grab the URL and set it as the text field's value
    custom_uploader.on('select', function() {
      var attachment = custom_uploader.state().get('selection').first().toJSON();
      $('#wp-bb--bar-background-string').val(attachment.url);
      $('#upload-bar-background-preview img').attr('src',attachment.url);
      $('#submit').trigger('click');
    });
    //Open the uploader dialog
    custom_uploader.open();
  });
});
