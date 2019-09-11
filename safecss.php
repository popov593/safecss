<?php
/**
* Plugin Name: Safe CSS
* Plugin URI: https://www.vipestudio.com
* Description: Adding custom CSS to your website, without being scared changing the theme or upgrading will delete the progress. Find me under Appearance > Safe CSS.
* Version: 1.0
* Author: Ivan Popov
* Author URI: https://www.vipestudio.com
**/

/* Include all */
	foreach ( glob( plugin_dir_path( __FILE__ ) . "*.php" ) as $file ) {
                include_once $file;
}

/**
 * Append CSS
*/

// register jquery and style on initialization
add_action('init', 'registersfcss_script');
function registersfcss_script() {
    //wp_register_script( 'sfcss_jquery', plugins_url('assets/js/js.js', __FILE__), array('jquery'), '2.5.1' );

    wp_register_style( 'sfcss_style', plugins_url('frontend/custom.css', __FILE__), false, '1.0.0', 'all');
}

//js to admin
function sfccadmin_enqueue($hook) {
    wp_enqueue_script('my_custom_script', plugin_dir_url(__FILE__) . 'assets/js/backend.js');
}
//add_action('admin_enqueue_scripts', 'sfccadmin_enqueue');

// use the registered jquery and style above
add_action('wp_enqueue_scripts', 'enqueue_sfccstyle');

function enqueue_sfccstyle(){
   wp_enqueue_script('sfcss_jquery');

   wp_enqueue_style( 'sfcss_style' );
}

//add menu element
add_action( 'admin_menu', 'safecss_info_menu' );

function safecss_info_menu(){

  $parent_slug = 'themes.php';
  $page_title = 'Add your custom CSS';
  $menu_title = 'Safe CSS';
  $capability = 'manage_options';
  $menu_slug  = 'safecss';
  $function   = 'safecss_page';
  $icon_url   = 'dashicons-plus-alt';
  $position   = 10;

  add_submenu_page( $parent_slug,
                 $page_title,
                 $menu_title,
                 $capability,
                 $menu_slug,
                 $function,
                 $icon_url,
                 $position );
}

//plugin page
if( !function_exists("safecss_page") ) {
function safecss_page(){
        $file = plugin_dir_path( __FILE__ ) . "frontend/custom.css";
        $file_contents = file_get_contents($file);
        if(isset($_POST["action"])&&($_POST["action"]=="Save CSS")) { 
           $file_contents = $_POST['file_contents'];
           $fh = fopen($file, "w");
           file_put_contents($file,$file_contents);
           fwrite($fh, $file_contents);
           fclose($fh);
        }
        echo "<h1>Safe CSS</h1>";
        echo "<p>Include any custom css you would like to use. It is kept in a seperate file and is safe from database upgrade or theme change.</p>";
        echo "<form method='POST' name='correspond_form' id='correspond_formJoin'>";
        echo "<textarea id='edit' name='file_contents' style='background: url(". plugin_dir_url( __FILE__ ) . 'assets/img/lines.png' . ") #fff; background-attachment: local; background-repeat: no-repeat; padding-left: 35px; padding-top: 10px; border-color:#ccc; font-size: 13px;' rows='50' cols='100'>";
        echo file_get_contents($file);
        echo "</textarea></br>";
        echo "<input type='submit' name='action' value='Save CSS' class='button button-primary'></td>";
        echo "</form>";
}
}
