<?php
// https://gist.github.com/bueltge/757903
// https://www.tipsandtricks-hq.com/how-to-add-meta-boxes-to-provide-extra-content-for-wordpress-posts-6078
// https://premium.wpmudev.org/blog/create-google-analytics-plugin/?omtr=b&utm_expid=3606929-101.yRvM9BqCTnWwtfcczEfOmg.1&utm_referrer=https%3A%2F%2Fwww.google.is%2F

// http://tutorialzine.com/2012/11/option-panel-wordpress-plugin-settings-api/
// http://www.wpexplorer.com/wordpress-theme-options/


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'TMS_SEO_Settings' ) ) {

class TMS_SEO_Settings {

  /**
		 * Start things up
		 *
		 * @since 1.0.0
		 */
	public function __construct() {
    if( is_admin() ){
		 add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
     add_action( 'admin_init', array( $this, 'register_settings' ) );
    }
	}

  /**
   * Add sub menu page
   *
   * @since 1.0.0
   */
	public function add_admin_menu() {
		add_options_page(
			'SEO options',
			'SEO options',
			'manage_options',
			'seo_options',
			array(
				$this,
				'settings_page'
			)
		);



	}


  /**
   * Settings page output
   *
   * @since 1.0.0
   */
	public function  settings_page() {?>
    <div class="wrap">

      <h1><?php esc_html_e( 'SEO Options', 'text-domain' ); ?></h1>

      <form method="post" action="options.php">

        <?php settings_fields( 'theme_options' ); ?>

        <table class="form-table wpex-custom-admin-login-table">

         

          <?php // Text input example ?>
          <tr valign="top">
            <th scope="row"><?php esc_html_e( 'Google analytics kóði:', 'text-domain' ); ?></th>
            <td>
              <?php $value = self::get_theme_option( 'input_example' ); ?>
              <!--<input type="text" name="theme_options[input_example]" value="<?php //echo esc_attr( $value ); ?>"> -->
              <textarea name="theme_options[input_example]" rows="14" cols="80"><?php echo esc_attr( $value ); ?></textarea>
            </td>
          </tr>

          <?php // Select example ?>
      <!--    <tr valign="top" class="wpex-custom-admin-screen-background-section">
            <th scope="row"><?php // esc_html_e( 'Select Example', 'text-domain' ); ?></th>
            <td>
              <?php // $value = self::get_theme_option( 'select_example' ); ?>
              <select name="theme_options[select_example]">
                <?php
             
?>-->
        </table>

        <?php submit_button(); ?>

      </form>

    </div><!-- .wrap -->



	<?php }


  /**
   * Returns all theme options
   *
   * @since 1.0.0
   */
  public static function get_theme_options() {
    return get_option( 'theme_options' );
  }

  /**
   * Returns single theme option
   *
   * @since 1.0.0
   */
  public static function get_theme_option( $id ) {
    $options = self::get_theme_options();
    if ( isset( $options[$id] ) ) {
      return $options[$id];
    }
  }



  /**
   * Register a setting and its sanitization callback.
   *
   * We are only registering 1 setting so we can store all options in a single option as
   * an array. You could, however, register a new setting for each option
   *
   * @since 1.0.0
   */
  public static function register_settings() {
    register_setting( 'theme_options', 'theme_options', array( 'WPEX_Theme_Options', 'sanitize' ) );
  }

  /**
   * Sanitization callback
   *
   * @since 1.0.0
   */
  public static function sanitize( $options ) {

    // If we have options lets sanitize them
    if ( $options ) {

     
      // Input
      if ( ! empty( $options['input_example'] ) ) {
        $options['input_example'] = sanitize_text_field( $options['input_example'] );
      } else {
        unset( $options['input_example'] ); // Remove from options if empty
      }

      // Select
      if ( ! empty( $options['select_example'] ) ) {
        $options['select_example'] = sanitize_text_field( $options['select_example'] );
      }

    }

    // Return sanitized options
    return $options;

  }



}

new TMS_SEO_Settings;


// Helper function to use in your theme to return a theme option value
function myprefix_get_theme_option( $id = '' ) {
	return TMS_SEO_Settings::get_theme_option( $id );
}



add_action('wp_head', 'wpb_add_googleanalytics');
function wpb_add_googleanalytics() {
	if( !is_admin() ):
		// til að koma í veg fyrir að pagespeed insights fái scriptuna serveraða til sín
		//if (!isset($_SERVER['HTTP_USER_AGENT']) || stripos($_SERVER['HTTP_USER_AGENT'], 'Speed Insights') === false):
			// your analytics code here
  		echo '<script>' . myprefix_get_theme_option('input_example') . '</script>';
	//	endif;
	endif;
}




}


/*
https://wordpress.org/ideas/topic/function-to-display-an-array-of-all-enqueued-scriptsstyles
http://wpengineer.com/2308/debug-enqueued-scripts-and-styles-in-wordpress/
*/
