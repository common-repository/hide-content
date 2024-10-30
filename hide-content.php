<?php
/*
Plugin Name: Hide Content
Plugin URI: http://school-wp.net/plaginy/plagin-wordpress-hide-content/
Description: Plugin hides the content from unauthorized users.
Version: 0.2
Author: School-wp.net
Author URI: http://school-wp.net
License: GPLv2 or later
*/

if(!defined('WP_CONTENT_URL'))
	define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if(!defined('WP_CONTENT_DIR'))
	define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
if(!defined('WP_PLUGIN_URL'))
	define('WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins');
if(!defined('WP_PLUGIN_DIR'))
	define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');
	
	
add_action('admin_menu', 'my_plugin_menu');
function my_plugin_menu() {
	add_options_page('Plugin "Hide Content" - Settings', 'Hide Content', 'manage_options', 'hide-content-options.php', 'admin_form');
}	

add_shortcode('hide', 'swp_if_logged_in');
function swp_if_logged_in($atts, $content=""){
	$message = get_option('hide_content_message');
	$links = get_option('hide_content_links');
	$text_signup = get_option('hide_content_text_signup');
	$url_signup = get_option('hide_content_url_signup');
	$text_login = get_option('hide_content_text_login');
	$url_login = get_option('hide_content_url_login');
	
	if(!$message or empty($message))
		$message = __('To view please log in!', 'hide-content');
	
	if(!$links)
		$links = null;
		
	if(!$text_signup)
		$text_signup = __('Sign Up', 'hide-content');	
		
	if(!$url_signup)
		$url_signup = admin_url();
		
	if(!$text_login)
		$text_login = __('Log In', 'hide-content');
		
	if(!$url_login)
		$url_login = wp_login_url();
	
	if ( is_user_logged_in() ) {
		return $content;
	}else{
		if($links == null){
			return '<div class="swp_no_logged_in_msg">'.$message.'</div>';
		}else{
			return '<div class="swp_no_logged_in_msg">'.$message.' <a href="'.$url_signup.'" class="swp_signup">'.$text_signup.'</a> <sapn class="swp_sep">|</span> <a href="'.$url_login.'" class="swp_login">'.$text_login.'</a></div>';
		}
	}
}

function swp_hide_content_button(){
	if ( current_user_can('edit_posts') && current_user_can('edit_pages') ){
		add_filter('mce_external_plugins', 'swp_hide_content_plugin');
		add_filter('mce_buttons_2', 'swp_hide_content_register_button');
	}
}
add_action('init', 'swp_hide_content_button');

function swp_hide_content_register_button($buttons){
array_push($buttons, 'hide'); //Button register
return $buttons;
}

function swp_hide_content_plugin($plugin_array){
	$plugin_array['swp_add_hide_content_button'] = WP_PLUGIN_URL.'/hide-content/hide-content.js';
	return $plugin_array;
}

add_action( 'plugins_loaded', 'true_load_plugin_textdomain' );
function true_load_plugin_textdomain() {
	load_plugin_textdomain( 'hide-content', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}





function admin_form(){
$message = get_option('hide_content_message');
$links = get_option('hide_content_links');
$text_signup = get_option('hide_content_text_signup');
$url_signup = get_option('hide_content_url_signup');
$text_login = get_option('hide_content_text_login');
$url_login = get_option('hide_content_url_login');
 if ( isset($_POST['submit']) )
{  
	if ( function_exists('current_user_can') && !current_user_can('manage_options') )
			die ( _e('Hacker?', 'hide-content') );

	if (function_exists ('check_admin_referer') )
	{
		check_admin_referer('hide_content_form');
	}

	$message = $_POST['message'];
	$links = $_POST['links'];
	$text_signup = $_POST['text_signup'];
	$url_signup = $_POST['url_signup'];
	$text_login = $_POST['text_login'];
	$url_login = $_POST['url_login'];

	update_option('hide_content_message', $message);
	update_option('hide_content_links', $links);
	update_option('hide_content_text_signup', $text_signup);
	update_option('hide_content_url_signup', $url_signup);
	update_option('hide_content_text_login', $text_login);
	update_option('hide_content_url_login', $url_login);
}

?>
    <div class='wrap'>
        <h2><?php _e('"Hide Content" Settings', 'hide-content'); ?></h2>

        <form name="hide-content" method="post"
            action="<?php echo $_SERVER['PHP_SELF']; ?>?page=hide-content-options.php&amp;updated=true">

            <?php
                if (function_exists ('wp_nonce_field') )
                {
                    wp_nonce_field('hide_content_form');
                }
            ?>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('Message:', 'hide-content'); ?></th>

                    <td>
                        <input type="text" name="message"
                            size="80" value="<?php echo $message; ?>" />
                    </td>
                </tr>
				<tr valign="top">
                    <th scope="row"><?php _e('Show links Sign Up|Login:', 'hide-content'); ?></th>
                    <td>
						
                        <input type="checkbox" name="links" size="80" <?php if($links !== null){echo 'checked="checked"';}; ?> />
                    </td>
                </tr>
				<tr valign="top">
                    <th scope="row"><?php _e('Text link "Sign Up":', 'hide-content'); ?></th>
                    <td>
                        <input type="text" name="text_signup"
                            size="80" value="<?php echo $text_signup; ?>" />
                    </td>
                </tr>
				<tr valign="top">
                    <th scope="row"><?php _e('URL link "Sign Up":', 'hide-content'); ?></th>
                    <td>
                        <input type="text" name="url_signup"
                            size="80" value="<?php echo $url_signup; ?>" />
                    </td>
                </tr>
				<tr valign="top">
                    <th scope="row"><?php _e('Text link "Log In":', 'hide-content'); ?></th>
                    <td>
                        <input type="text" name="text_login"
                            size="80" value="<?php echo $text_login; ?>" />
                    </td>
                </tr>
				<tr valign="top">
                    <th scope="row"><?php _e('URL link "Log In":', 'hide-content'); ?></th>
                    <td>
                        <input type="text" name="url_login"
                            size="80" value="<?php echo $url_login; ?>" />
                    </td>
                </tr>
            </table>

            <input type="hidden" name="action" value="update" />
            <p class="submit">
            <input type="submit" name="submit" value="<?php _e('Save Changes') ?>" />
            </p>
        </form>
    </div>
	<script type="text/javascript">
	jQuery(document).ready(function($){
		$('input[name=links]').on('change', function(){
			if(!$('input[name=links]').prop("checked")){
				$('input[name=text_signup]').parent().parent().fadeOut();
				$('input[name=url_signup]').parent().parent().fadeOut();
				$('input[name=text_login]').parent().parent().fadeOut();
				$('input[name=url_login]').parent().parent().fadeOut();
				
			}else{
				$('input[name=text_signup]').parent().parent().fadeIn();
				$('input[name=url_signup]').parent().parent().fadeIn();
				$('input[name=text_login]').parent().parent().fadeIn();
				$('input[name=url_login]').parent().parent().fadeIn();
			}
		})
		if(!$('input[name=links]').prop("checked")){
			$('input[name=text_signup]').parent().parent().hide();
			$('input[name=url_signup]').parent().parent().hide();
			$('input[name=text_login]').parent().parent().hide();
			$('input[name=url_login]').parent().parent().hide();
		}
	});
	</script>
<?php }; ?>