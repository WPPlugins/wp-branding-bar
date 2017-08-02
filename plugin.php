<?php
/*
Plugin Name: WP Branding Bar
Plugin URI: http://wordpress.org/extend/plugins/wp-branding-bar/
Description: WP Branding Bar creates an adjustable header bar accross the top of your site, with room for two user-defined images with two user-defined links. 

Version:         1.5.6 Plays nice with https, uses 3.5 media manager
Version History: 1.5.5 Added alt text fields
                 1.5.4  
                 1.5.3 Fixed bug with delete image
                 1.5.2 Fixed naming conflict bug       
                 1.5.1 Changed default images
                 1.5 Added new default logo
                 1.4 Enabled adjusting the height of the bar
                 1.3 Enbled modification of background image and links 
                 1.2 Enabled user to change the images that appear in the header.
                 1.1 Display bug fixes
        
License: GNU General Public License v2.0 (or later)
License URI: http://www.opensource.org/licenses/gpl-license.php

Table of Contents

**  Activation
**  Main Hooks 
**  The Admin Section
**  Front End Functionality

*/


/*  Activation/Deactivation 
------------------------------------------------------------- */
register_activation_hook( __FILE__, 'wp_bb_activate' );
register_deactivation_hook( __FILE__, 'wp_bb_deactivate' );

function wp_bb_is_utexas(){
  $url = parse_url(home_url());
  $host = explode(".", $url['host']);

  $domain = array_slice($host, -2, 1);
  if ( $domain[0] == "utexas" ) {
    return true;
  } else {
    return false;
  }
}

function wp_bb_activate() {
  if ( wp_bb_is_utexas() ) {
    $activation_default_options =   array(
      'max_width' => '',
      'bar_height' => '33',
      'bar_background' => '',
      'left_image' => plugins_url( '/wp-branding-bar/images/new-university.png' ),
      'right_image' => plugins_url( '/wp-branding-bar/images/new-college.png' ),
      'left_image_link' => '//www.utexas.edu',
      'right_image_link' => '//www.utexas.edu/cola',
      'left_alt_text' => 'The University of Texas at Austin',
      'right_alt_text' => 'College of Liberal Arts'
    );
   } else {
    $activation_default_options =   array(
      'max_width' => '',
      'bar_height' => '33',
      'bar_background' => plugins_url( '/wp-branding-bar/images/default-background.png' ),
      'left_image' => plugins_url( '/wp-branding-bar/images/default-left.png' ),
      'right_image' => plugins_url( '/wp-branding-bar/images/default-right.png' ),
      'left_image_link' => '//www.example.com',
      'right_image_link' => '//www.example.com',
      'left_alt_text' => 'Enter alt text for left image here',
      'right_alt_text' => 'Enter alt text for right image here'
    );
   }   

  update_option( 'wp_bb_options', $activation_default_options );
}

function wp_bb_deactivate() {
  delete_option( 'wp_bb_options');
}



/*  Main Hooks
------------------------------------------------------------- */

if ( is_admin() ){ // admin actions (see below)
  add_action( 'admin_menu', 'wp_bb_menu' );
  add_action( 'admin_init', 'wp_bb_options_setup' );
  add_action( 'admin_enqueue_scripts', 'wp_bb_enqueue_scripts' );
  add_action( 'admin_init', 'wp_bb_register_settings' );
} else { // Front end functionality
  add_action( 'after_setup_theme', 'wp_bb_start_up' );
}


/** The Admin Section
------------------------------------------------------------- */


// Initializes the menu
function wp_bb_menu() {
  add_submenu_page( 
    'options-general.php',
    'WP Branding Bar Options', 
    'WP Branding Bars', 
    'manage_options', 
    'wp_bb_menu_slug', 
    'wp_branding_bar_options' );
}

//  Set up the wp-bb- options page
function wp_bb_register_settings() {
  register_setting( 
    'wp_bb_options', 
    'wp_bb_options', 
    'wp_bb_options_validate' 
  );
  add_settings_section(
    'wp_bb_settings_section', 
    'WP Branding Bar Settings', 
    'wp_bb_section_text', 
    'wp_bb_plugin'
  );
  add_settings_field(
    'wp_bb_bar_width', 
    'Bar Width:', 
    'wp_bb_bar_width_string', 
    'wp_bb_plugin', 
    'wp_bb_settings_section'
  );
  add_settings_field(
    'wp_bb_bar_height', 
    'Bar Height:', 
    'wp_bb_bar_height_string', 
    'wp_bb_plugin', 
    'wp_bb_settings_section'
  );
  add_settings_field(
    'wp_bb_left_link', 
    'Left Link:', 
    'wp_bb_left_link_string', 
    'wp_bb_plugin', 
    'wp_bb_settings_section'
  ); 
  add_settings_field(
    'wp_bb_left_alt_text', 
    'Left Alt Text:', 
    'wp_bb_left_alt_text_string', 
    'wp_bb_plugin', 
    'wp_bb_settings_section'
  ); 
  add_settings_field(
    'wp_bb_left_image', 
    'Left Image:', 
    'wp_bb_left_image_string', 
    'wp_bb_plugin', 
    'wp_bb_settings_section'
  );
  add_settings_field(
    'wp_bb_left_image_preview', 
    'Left Image Preview:', 
    'wp_bb_left_image_preview', 
    'wp_bb_plugin', 
    'wp_bb_settings_section'
  );
  add_settings_field(
    'wp_bb_right_link', 
    'Right Link:', 
    'wp_bb_right_link_string', 
    'wp_bb_plugin', 
    'wp_bb_settings_section'
  ); 
  add_settings_field(
    'wp_bb_right_alt_text', 
    'Right Alt Text:', 
    'wp_bb_right_alt_text_string', 
    'wp_bb_plugin', 
    'wp_bb_settings_section'
  ); 
  add_settings_field(
    'wp_bb_right_image', 
    'Right Image:', 
    'wp_bb_right_image_string', 
    'wp_bb_plugin', 
    'wp_bb_settings_section'
  );
  add_settings_field(
    'wp_bb_right_image_preview', 
    'Right Image Preview:', 
    'wp_bb_right_image_preview', 
    'wp_bb_plugin', 
    'wp_bb_settings_section'
  );
  add_settings_field(
    'wp_bb_bar_background', 
    'Background:', 
    'wp_bb_bar_background_string', 
    'wp_bb_plugin', 
    'wp_bb_settings_section'
  );
  add_settings_field(
    'wp_bb_bar_background_preview', 
    'Background Preview:', 
    'wp_bb_bar_background_preview', 
    'wp_bb_plugin', 
    'wp_bb_settings_section'
  );
} 


function wp_bb_defaults() {
  return array(
    'max_width' => 0,
    'bar_height' => 33,
    'bar_background' => plugins_url( '/wp-branding-bar/images/default-background.png' ),
    'left_image' => '',
    'right_image' => '',
    'left_image_link' => '//www.example.com',
    'right_image_link' => '',
    'left_alt_text' => '',
    'right_alt_text' => ''
  );
}

//  Determines the text that appears on the option page
function wp_bb_section_text() {
  echo  '<h4>Purpose</h4>
         <p>WP Branding Bar creates an adjustable header bar accross the top of your site, with room for two user-defined images and two user-defined links.</p>
         <h4>Adjustments</h4>
        ';
}

// Creates the input field to set the width of the header
function wp_bb_bar_width_string() {
  $options = get_option('wp_bb_options');
  echo "<input id='wp-bb--bar-width-string' name='wp_bb_options[max_width]' size='40' type='text' value='{$options['max_width']}' />";
}

function wp_bb_bar_height_string() {
  $options = get_option('wp_bb_options');
  ?>
    <input id='wp-bb-bar-height-string' name='wp_bb_options[bar_height]' size='40' type='text' value="<?php echo $options['bar_height']; ?>" />
    <span class="description"> <?php _e('Default is 33', 'wp_bb' ); ?></span>
  <?php
}

function wp_bb_left_link_string() {
  $options = get_option('wp_bb_options');
  echo "<input id='wp-bb--left-link-string' name='wp_bb_options[left_image_link]' size='40' type='text' value='{$options['left_image_link']}' />";
}

function wp_bb_left_image_string() {
  $options = get_option('wp_bb_options');
  ?>
    <input type='hidden' value="<?php echo $options['left_image']; ?>" id='wp-bb--left-image-string' name='wp_bb_options[left_image]'><?php echo $options['left_image']; ?></input><br />
    <input id="upload-left-image-button" type="button" class="button" value="<?php _e( 'Upload Left Image', 'wp_bb' ); ?>" />
    <?php if ( '' != $options['left_image'] ): ?>  
      <input id="delete-left-image-button" name="wp_bb_options[delete_left_image]" type="submit" class="button" value="<?php _e( 'Delete Left Image', 'wp_bb' ); ?>" />  
    <?php endif; ?> 
    <span class="description"><?php _e('Upload an image for the left image. (less than 400px by 33px)', 'wp_bb' ); ?></span>
  <?php 
}

function wp_bb_left_image_preview() {
  $options = get_option( 'wp_bb_options' ); ?>
    <div id="upload-left-image-preview" style="min-height:60px;">
      <img style="max-width: 100%;" src="<?php echo esc_url( $options['left_image'] ); ?>" />
    </div>
  <?php
}

function wp_bb_right_link_string() {
  $options = get_option('wp_bb_options');
  echo "<input id='wp-bb--right-link-string' name='wp_bb_options[right_image_link]' size='40' type='text' value='{$options['right_image_link']}' />";
}

function wp_bb_right_image_string() {
  $options = get_option('wp_bb_options');
  ?>
    <input type='hidden' value="<?php echo $options['right_image']; ?>" id='wp-bb--right-image-string' name='wp_bb_options[right_image]'><?php echo $options['right_image']; ?></input><br />
    <input id="upload-right-image-button" type="button" class="button" value="<?php _e( 'Upload Right Image', 'wp_bb' ); ?>" />
    <?php if ( '' != $options['right_image'] ): ?>  
      <input id="delete-right-image-button" name="wp_bb_options[delete_right_image]" type="submit" class="button" value="<?php _e( 'Delete Right Image', 'wp_bb' ); ?>" />  
    <?php endif; ?> 
    <span class="description"><?php _e('Upload an image for the right image. (less than 400px by 33px)', 'wp_bb' ); ?></span>
  <?php 
}

function wp_bb_right_image_preview() {
  $options = get_option( 'wp_bb_options' ); ?>
    <div id="upload-right-image-preview" style="min-height:60px;">
      <img style="max-width: 100%;" src="<?php echo esc_url( $options['right_image'] ); ?>" />
    </div>
  <?php
}

function wp_bb_bar_background_string() {
  $options = get_option('wp_bb_options');
  ?>
    <input type='hidden' value="<?php echo $options['bar_background']; ?>" id='wp-bb--bar-background-string' name='wp_bb_options[bar_background]'><?php echo $options['bar_background']; ?></input><br />
    <input id="upload-bar-background-button" type="button" class="button" value="<?php _e( 'Upload Bar  Image', 'wp_bb' ); ?>" />
    <?php if ( '' != $options['bar_background'] ): ?>  
      <input id="delete-bar-background-button" name="wp_bb_options[delete_bar_background]" type="submit" class="button" value="<?php _e( 'Delete Bar Image', 'wp_bb' ); ?>" />  
    <?php endif; ?> 
    <span class="description"><?php _e('Upload an image for the bar background. (height 40px)', 'wp_bb' ); ?></span>
  <?php 
}

function wp_bb_bar_background_preview() {
  $options = get_option( 'wp_bb_options' ); ?>
    <div id="upload-bar-background-preview" style="min-height:60px;min-width:60px;background:transparent url('<?php echo esc_url( $options['bar_background'] ); ?>') repeat-x">
    </div>
  <?php
}

function wp_bb_right_alt_text_string() {
  $options = get_option('wp_bb_options');
  echo "<input id='wp-bb--right-alt-text-string' name='wp_bb_options[right_alt_text]' size='40' type='text' value='{$options['right_alt_text']}' />";
}

function wp_bb_left_alt_text_string() {
  $options = get_option('wp_bb_options');
  echo "<input id='wp-bb--left-alt-text-string' name='wp_bb_options[left_alt_text]' size='40' type='text' value='{$options['left_alt_text']}' />";
}

function wp_bb_options_validate($input) {
  $default_options = wp_bb_defaults();
  $submit = ! empty ($input['submit']);
  $delete_left_image = ! empty($input['delete_left_image']) ? true : false;
  $delete_right_image = ! empty($input['delete_right_image']) ? true : false; 
  $delete_bar_background = ! empty($input['delete_bar_background']) ? true : false; 

  if ( $delete_left_image ) {
    $newinput['left_image'] = '';
    $newinput['right_image'] = esc_url($input['right_image']); 
    $newinput['bar_background'] = esc_url($input['bar_background']);    
  }
  elseif ( $delete_right_image ) {
    $newinput['left_image'] = esc_url($input['left_image']);
    $newinput['right_image'] = '';    
    $newinput['bar_background'] = esc_url($input['bar_background']); 
  }
  elseif ( $delete_bar_background ) {
    $newinput['left_image'] = esc_url($input['left_image']);
    $newinput['right_image'] = esc_url($input['right_image']);    
    $newinput['bar_background'] = ''; 
  }
  else {
    $newinput['left_image'] = esc_url($input['left_image']);
    $newinput['right_image'] = esc_url($input['right_image']);
    $newinput['bar_background'] = esc_url($input['bar_background']);         
  }

  $newinput['max_width'] = trim($input['max_width']);    
  if(!preg_match('/^\d+%?$/i', $newinput['max_width'])) 
  {
    $newinput['max_width'] = '';
  }
  
  $newinput['bar_height'] = trim($input['bar_height']);    
  if(!preg_match('/^\d+%?$/i', $newinput['bar_height'])) 
  {
    $newinput['bar_height'] = '';
  }

  $newinput['left_image_link'] = esc_url($input['left_image_link']);
  $newinput['right_image_link'] = esc_url($input['right_image_link']);

  // We'll be outputting these strings via javascript, so use esc_js to escape
  $newinput['left_alt_text'] = esc_js($input['left_alt_text']);
  $newinput['right_alt_text'] = esc_js($input['right_alt_text']);

  return $newinput;
}
 

// Sets up the structure of the output of the page.
function wp_branding_bar_options() {
  if ( !current_user_can( 'manage_options' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  echo '<div class="wrap">';
  echo '<h2>WP Branding Bar</h2>';
  echo '<form method="post" action="options.php"> ';
        settings_fields('wp_bb_options');
        do_settings_sections('wp_bb_plugin');
        submit_button();
  echo '</form>';
  echo '</div>';
}


/*  Front End Functions
------------------------------------------------------------- */
// Add the stylesheet, enqueue jQuery, write the javascript to the footer

function wp_bb_start_up() {
  wp_enqueue_style('wp-branding-bar-stylesheet', plugins_url( 'wp-branding-bar/style.css'), false, '');
  add_action( 'wp_enqueue_scripts', 'wp_bb_enqueue_jquery'  );
  add_action( 'wp_footer', 'wp_bb_inject_UT_js' , 9999999);
}

function wp_bb_get_wp_version(){
  global $wp_version;
  return $wp_version;
}

function wp_bb_enqueue_scripts() {
  wp_enqueue_script('jquery');
  if (wp_bb_get_wp_version() < 3.5 ){
    wp_register_script( 'wp-branding-bar', plugins_url() . '/wp-branding-bar/wp-branding-bar-pre-wp35.js', array('jquery','media-upload','thickbox') );
    wp_enqueue_script('thickbox');
    wp_enqueue_style('thickbox');
    wp_enqueue_script('media-upload');
  } else {
    wp_register_script( 'wp-branding-bar', plugins_url() . '/wp-branding-bar/wp-branding-bar-post-wp35.js', array('jquery') );
    wp_enqueue_media();
  wp_enqueue_script('wp-branding-bar');
  }
}  

function wp_bb_options_setup() {
  global $pagenow;
  if ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow ) {
    add_filter( 'gettext', 'wp_bb_replace_thickbox_text'  , 1, 3 );
  }
}

function wp_bb_replace_thickbox_text($translated_text, $text, $domain) {
  if ('Insert into Post' == $text) {
    $referer = strpos( wp_get_referer(), 'wp-bb--settings' );
    if ( $referer != '' ) {
      return __('Select this Image', 'wp_bb' );
    }
  }
  return $translated_text;
}
  
// Enqueue jquery to get our javascripts to run -- just in case no other theme/plugin has
function wp_bb_enqueue_jquery() {
  wp_enqueue_script('jquery');      
}

/** Add banner-logo 
    Generate and inject the HTML that produces the header
*/
function wp_bb_inject_UT_js() {
  //set Javascript variables
  $default_options = wp_bb_defaults();
  $options = get_option('wp_bb_options');

  $maxDivWidth = ($options['max_width'] > 1) ? $options['max_width'] : $default_options['max_width'];
  $barHeight = ($options['bar_height'] > 1) ? $options['bar_height'] : $default_options['bar_height'];
  $leftImageURL = ! empty($options['left_image']) ? $options['left_image'] : $default_options['left_image'];
  $rightImageURL = ! empty($options['right_image']) ? $options['right_image'] : $default_options['right_image']; 
  $barBackgroundURL = ! empty($options['bar_background']) ? 'url(\'' . $options['bar_background'] . '\')' : 'none';
  $leftImageLink = ! empty($options['left_image_link']) ? $options['left_image_link'] : $default_options['left_image_link'];
  $rightImageLink = ! empty($options['right_image_link']) ? $options['right_image_link'] : $default_options['right_image_link'];
  $leftAltText = ! empty($options['left_alt_text']) ? $options['left_alt_text'] : $default_options['left_alt_text'];
  $rightAltText = ! empty($options['right_alt_text']) ? $options['right_alt_text'] : $default_options['right_alt_text'];


  $javascript = '  <script type="text/javascript">
                jQuery(document).ready(function() {';
  $javascript .=  '  maxDivWidth = "' . $maxDivWidth . '";';
  $javascript .=  '  barHeight = ' . $barHeight . ';';  
  $javascript .=  '  leftImageURL = "' . $leftImageURL . '";'; 
  $javascript .=  '  rightImageURL = "' . $rightImageURL . '";'; 
  $javascript .=  '  barBackgroundURL = "' . $barBackgroundURL . '";'; 
  $javascript .=  '  leftImageLink = "' . $leftImageLink . '";'; 
  $javascript .=  '  rightImageLink = "' . $rightImageLink . '";'; 
  $javascript .=  '  leftAltText = "' . $leftAltText . '";'; 
  $javascript .=  '  rightAltText = "' . $rightAltText . '";'; 


  $javascript .= <<<EOT
      the_html = '\
        <div id="wpbb-branding-header-wrapper">\
          <div id="wpbb-branding-inner">   \
            <div class="wpbb-branding-container">    \
              <div id="wpbb-main-branding">\
                <a href="';
      the_html += leftImageLink;
      the_html += '"><img alt="' + leftAltText + '" src="';
      the_html += leftImageURL;
      the_html += '\
        "></a> \
              </div>';
        if (rightImageURL) {
              the_html += '<div id="wpbb-secondary-branding">\
                <a href="';
      the_html += rightImageLink;
      the_html += '"><img alt="' + rightAltText + '" src="'; 
      the_html += rightImageURL;
      the_html += '\
        "></a>\
              </div>';
        }
the_html += ' </div>    \
            <div class="clear"> </div>  \
          </div>  \
        </div>\
        <div class="wpbb-header-spacer"> </div>';    
      jQuery('body').prepend(the_html);

      //  set the width of the wpbb-branding-container based on the widths of the widest divs on the page
      
      if (maxDivWidth == "100%" || maxDivWidth != "0"){

      }
      else
      {
        jQuery('.wrap, .wrapper, .container,#main, #wrapper, #container').each(function(index) {
          curDivWidth = parseInt(jQuery(this).css("width"));
          if (maxDivWidth < curDivWidth) {        
              maxDivWidth = curDivWidth;                        
          }
        }); 
      
        if (maxDivWidth == 0){
          maxDivWidth = '100%'
        };
      }

      jQuery('#wpbb-branding-inner').css('background-image', barBackgroundURL)
      jQuery('#wpbb-branding-header-wrapper').css('height', barHeight);
      jQuery('.wpbb-branding-container').css('width',maxDivWidth).css('height',barHeight);
      jQuery('.wpbb-header-spacer').css( 'height', barHeight + 7)

      jQuery('div').not('#wpbb-branding-header-wrapper').each(function(index) { 
        if ((jQuery(this).css('position') == 'absolute') && (jQuery(this).offset().top < 1) ){ 
          var my_top = parseInt(jQuery(this).css('top'));
          if (my_top < 40) { 
            my_top = my_top + 40 + 'px';
          } 
          jQuery(this).css('top', my_top);
       }
      });
    });
      </script>
EOT;

echo $javascript;
}
