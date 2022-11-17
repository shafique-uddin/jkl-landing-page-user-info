<?php 
/**
* Plugin Name
*
* @package           PluginPackage
* @author            Your Name
* @copyright         2019 Your Name or Company Name
* @license           GPL-2.0-or-later
*
* @wordpress-plugin
* Plugin Name:       jk landing page user info plugin
* Plugin URI:        https://shafique.com
* Description:       Description of the plugin.
* Version:           1.0.0
* Requires at least: 5.2
* Requires PHP:      7.2
* Author:            Muhammad Shafique Uddin
* Author URI:        https://shafique.com
* Text Domain:       jk_landing_page_user_info
* License:           GPL v2 or later
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
*/
 
 // ADMISSION INFO DB VERSION
 global $jk_landing_page_define_db_version;
 $jk_landing_page_define_db_version = '1.1.2';
 
/**
* Activate the plugin.
*/
function jk_landing_page_info_installing_db() { 
    global $jk_landing_page_define_db_version;
    global $wpdb;
    $db_collate = $wpdb->get_charset_collate();
    $jk_landing_page_user_info_details_tbl = $wpdb->prefix.'jk_landing_page_user_db';

    $jk_landing_page_user_info_start_tbl_query = "CREATE TABLE $jk_landing_page_user_info_details_tbl (
        id INT(250) NOT NULL AUTO_INCREMENT,
        userName VARCHAR(250) NOT NULL,
        userMobile VARCHAR(250) NOT NULL,
        userEmail VARCHAR(250) NOT NULL,
        userDis VARCHAR(250) NOT NULL,
        userClg VARCHAR(250) NOT NULL,
        userCurrentStatus VARCHAR(250) NOT NULL,
        PRIMARY KEY  (id)
    )$db_collate;";
    

    require_once(ABSPATH.'wp-admin/includes/upgrade.php');
    dbDelta( $jk_landing_page_user_info_start_tbl_query );
    
    add_option('jk_landing_page_user_info_db_version_value', "$jk_landing_page_define_db_version");

    $wpdb->insert(
        $jk_landing_page_user_info_details_tbl,
        array(
            'userName' => 'Sample Data',
            'userMobile' => 'Sample Data',
            'userEmail' => 'Sample@Data.com',
            'userDis' => 'Sample Data',
            'userClg' => 'Sample Data',
            'userCurrentStatus' => 'Sample Data',
        )
    );

    // Clear the permalinks after the post type has been registered.
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'jk_landing_page_info_installing_db');

 
 
/**
* Deactivation hook.
*/
function jk_landing_page_info_deactivation_db_info() {
    global $jk_landing_page_define_db_version;
    global $wpdb;
    $jk_landing_page_user_info_details_tbl = $wpdb->prefix.'jk_landing_page_user_db';

    $jk_landing_page_user_details_tbl_sample_data_clean_query = "DELETE FROM $jk_landing_page_user_info_details_tbl WHERE userCurrentStatus LIKE 'Sample Data'";
    $wpdb->query($jk_landing_page_user_details_tbl_sample_data_clean_query);

    // Clear the permalinks to remove our post type's rules from the database.
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'jk_landing_page_info_deactivation_db_info' );


/**
 * Database Update hook.
 */
function jk_landing_page_user_info_update_checking_hndl(){
    global $jk_landing_page_define_db_version;
    global $wpdb;
    $jk_landing_page_user_info_details_tbl = $wpdb->prefix.'jk_landing_page_user_db';

    if(get_option( 'jk_landing_page_user_info_db_version_value' ) != $jk_landing_page_define_db_version){
        $query = "CREATE TABLE $jk_landing_page_user_info_details_tbl (
            id INT(250) NOT NULL AUTO_INCREMENT,
            userName VARCHAR(250) NOT NULL,
            userMobile VARCHAR(250) NOT NULL,
            userEmail VARCHAR(250) NOT NULL,
            userDis VARCHAR(250) NOT NULL,
            userClg VARCHAR(250) NOT NULL,
            userCurrentStatus VARCHAR(250) NOT NULL,
            PRIMARY KEY  (id)
        )$db_collate;";
    
        require_once(ABSPATH.'wp-admin/includes/upgrade.php');
        dbDelta( $query );
    
        update_option( 'jk_landing_page_user_info_db_version_value', "$jk_landing_page_define_db_version");
    
        $wpdb->update(
            $jk_landing_page_user_info_details_tbl,
            array(
                'userName' => 'Sample Data',
                'link' => 'Sample Link',
                'userMobile' => 'Sample Data',
                'userEmail' => 'Sample@Data.com',
                'userDis' => 'Sample Data',
                'userClg' => 'Sample Data',
                'userCurrentStatus' => 'Sample Data',
            ),
            array(
                'id' => 1
            )
        );

        // IF NEED ANY COLUMN DELETE
    }
}
add_action( 'plugins_loaded', 'jk_landing_page_user_info_update_checking_hndl' );


/**
 * Admin menu page
 */
function jk_landing_page_user_info_admin_menu_hndlr(){
    add_menu_page('User Information', 'User Info', 'manage_options', 'userinfo', 'jk_landing_page_all_st_info_hndlr', 'dashicons-database-view');
    // add_menu_page( 'Admission Information', 'Admission Info', 'manage_options', 'admissioninfo', 'admission_info_all_varsity_info_hndlr', 'dashicons-database-view' );
    
    add_submenu_page( 'userinfo', 'All info', 'All info', 'manage_options', 'userinfo' );
    add_submenu_page('userinfo', 'Delete User', 'Delete User', 'manage_options', 'delete-user', 'jk_landing_page_user_info_delete_hndlr');
    // add_submenu_page( 'userinfo', 'Delete User', 'Add info', 'manage_options', 'add-new-varsity-info', 'admission_info_add_new_info_frm_hndlr' );
    // add_submenu_page( 'admissioninfo', 'Admission Info File Attachment', 'Attachment', 'manage_options', 'admission-info-file-attachment', 'admission_info_file_attachment_frm_hndlr' );
}
add_action('admin_menu','jk_landing_page_user_info_admin_menu_hndlr');
function jk_landing_page_all_st_info_hndlr(){
    echo 'this is empty file function';
}
function jk_landing_page_user_info_delete_hndlr(){
    echo 'this also empty file function. This page will show all data and admin is capable to delete any person from here.';
}


/**
 * Plugin Admin Menu Page Styling
 * Add CSS AND JS
 */
function admission_info_admin_page_CSS_JS_include_hndlr($screen){
    // if(('toplevel_page_admissioninfo' == $screen)||('admission-info_page_add-new-varsity-info' == $screen) || ('admission-info_page_admission-info-file-attachment' == $screen)){
       wp_enqueue_style( 'admission-info-custom-css', plugin_dir_url( __FILE__ ).'admin/css/main.css', null, time() );
       wp_enqueue_style( 'admission-info-bootstrap-css-handler', '//cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css');
       wp_enqueue_style( 'admission-info-date-picker-stylesheet', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
       wp_enqueue_style( 'admission-info-date-picker-demo-stylesheet', '/resources/demos/style.css');
       wp_enqueue_script( 'admission-info-main-jquery', plugin_dir_url( __FILE__ ).'admin/js/main.js', null , null , true );
       wp_enqueue_script( 'jquery', 'https://code.jquery.com/jquery-1.12.4.js', array('json2'), '1.12.4', true );
       wp_enqueue_script( 'jquery-ui-datepicker', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js', array('jquery'), '1.11.4', true );
    // }
}
add_action( 'admin_enqueue_scripts', 'admission_info_admin_page_CSS_JS_include_hndlr', 1);
?>