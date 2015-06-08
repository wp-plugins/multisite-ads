<?php
/*
Plugin Name: Multisite Ads
Description: Includes custom HTML/CSS/JS code in the network.
Version: 1.1.0
Author: EWSEL
Author URI: http://ewsel.com
License: GPL2
*/
/*
    Copyright 2011 EWSEL (email: info@ewsel.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/* ************************************************************************* *\
	I18N
\* ************************************************************************* */
define( 'ew_TEXTDOMAIN', 'multisite-ads' );
add_action( 'init', 'ew_load_textdomain' );

function ew_load_textdomain() {
	$plugin_dir = basename(dirname(__FILE__));
	load_plugin_textdomain( MURM_TEXTDOMAIN, false, $plugin_dir.'/lang' );
}

/*****************************************************************************\
		LOGGING AND NOTICES
\*****************************************************************************/


function ew_nag( $message ) {
	echo( '<div id="message" class="updated"><p>'.$message.'</p></div>' );
}

function ew_nagerr( $message ) {
	echo( '<div id="message" class="error"><p>'.$message.'</p></div>' );
}

/*****************************************************************************\
		OPTIONS
\*****************************************************************************/

add_action( 'network_admin_menu','ew_network_admin_menu' );
function ew_network_admin_menu() {
	add_submenu_page( 'settings.php', __( 'Multisite Ads', ew_TEXTDOMAIN ), __( 'Multisite Ads', ew_TEXTDOMAIN ),
		'manage_network_options', 'ew-ads-options', 'ew_options_page' );
}

function ew_options_page() {
	if( isset($_REQUEST['action']) ) {
        $action = $_REQUEST['action'];
    } else {
        $action = 'default';
    }   
    switch( $action ) {
    case 'update-options':
    	update_site_option( 'ewsel_code', $_POST['ew_code'] );
    	ew_update_settings( $_POST['settings'] );
    	ew_nag( __( 'Settings saved.', ew_TEXTDOMAIN ) );
    	ew_options_page_default();
    	break;
	default:
		ew_options_page_default();
		break;
	}
}
/* <?php _e( '', ew_TEXTDOMAIN ); ?> */
function ew_options_page_default() {
	extract( ew_get_settings() );
	?>
	<div id="wrap">
		<h2><?php _e( 'Multisite wp_head Code', ew_TEXTDOMAIN ); ?></h2>
		<?php
			if( !$hide_donation_button ) {
				?>
				<h3><?php _e( 'Please consider a donation', ew_TEXTDOMAIN ); ?></h3>
				<p>
					<?php _e( 'I spend quite a lot of my precious time working on opensource WordPress plugins. If you find this one useful, please consider helping me develop it further. Even the smallest amount of money you are willing to spend will be welcome.', ew_TEXTDOMAIN ); ?>
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="hosted_button_id" value="FTJ2H2J4GURXQ">
						<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" style="border:none;" >
						<img style="display:none;" alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
					</form>
				</p>
				<?php
				}
		?>
		<h3><?php _e( 'Settings', SUH_TEXTDOMAIN ); ?></h3>
		<form method="post">
            <input type="hidden" name="action" value="update-options" />
            <table class="form-table">
                <tr valign="top">
                	<th><label for="ew_code"><?php _e( 'Ads Code (<code>HTML</code>, <code>CSS</code>, <code>JS</code> etc):', ew_TEXTDOMAIN ); ?></label></th>
                	<td>
                		<textarea name="ew_code" cols="40" rows="5" style="font-family: monospace;" placeholder="<!-- This Code Show in Head and Footer -->"><?php echo stripslashes( get_site_option( 'ewsel_code' ) ); ?></textarea>
                	</td>
                </tr>
                <tr valign="top">
                	<th>
                		<label><?php _e( 'Hide donation button', ew_TEXTDOMAIN ); ?></label><br />
                	</th>
                	<td>
                		<input type="checkbox" name="settings[hide_donation_button]" 
                			<?php if( $hide_donation_button ) echo 'checked="checked"'; ?>
                		/>
                	</td>
                	<td><small><?php _e( 'If you don\'t want to be bothered again...', ew_TEXTDOMAIN ); ?></small></td>
                </tr>
			</table>
			<p class="submit">
	            <input type="submit" class="button-primary" value="<?php _e( 'Save', ew_TEXTDOMAIN ); ?>" />    
	        </p>
		</form>
	</div>
	<?php
}
define( 'ew_SETTINGS', 'ew_settings' );

function ew_get_settings() {
	$defaults = array(
		'hide_donation_button' => false
	);
	$settings = get_site_option( ew_SETTINGS, array() );
	return wp_parse_args( $settings, $defaults );
}

function ew_update_settings( $settings ) {
	update_site_option( ew_SETTINGS, $settings );
}

/*****************************************************************************\
		CODE EMBEDDING
\*****************************************************************************/
add_action( 'wp_footer', 'ew_embed_code_footer' );
add_action( 'wp_head', 'ew_embed_code_head' );
function ew_embed_code_head() {
	if( is_admin() ) {
		return;
	}
	
	echo stripslashes( get_site_option( 'ewsel_code' ) );
}
function ew_embed_code_footer() {
	if( is_admin() ) {
		return;
	}
	echo stripslashes( get_site_option( 'ewsel_code' ) );
}
?>