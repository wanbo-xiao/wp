<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.bobsyd.com/demos
 * @since      1.0.0
 *
 * @package    Fpi
 * @subpackage Fpi/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Fpi
 * @subpackage Fpi/admin
 * @author     Bob Xiao
 */

class Fpi_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Fpi_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Fpi_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/fpi-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Fpi_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Fpi_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/fpi-admin.js', array( 'jquery' ), $this->version, false);

        /* some data can be used in script */
        
        wp_localize_script(
            $this->plugin_name,
            'fpi_js_data',
            array(
                'single_json_file' => plugins_url('public/facebook-single-data.json', dirname(__FILE__)),
                'large_json_file' => plugins_url('public/facebook-large-data.json', dirname(__FILE__)),
                'admin_ajax' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('fpi_admin_save'),
            )
        );
    }

    /**
     *    ---------------------------------------------------
     *    MENU SECTION
     *    ---------------------------------------------------
     */

    public function menu_section()
    {
        add_menu_page('Facebook posts import', 'Facebook posts import', 'publish_posts', 'fb-post-import', array($this, 'menu_section_display'), 'dashicons-facebook-alt', 76);
    }
    
    public function menu_section_display()
    {
        if (current_user_can('publish_posts')) {
            ob_start();
            include_once plugin_dir_path(__FILE__) . 'partials/fpi-admin-display.php';
            echo ob_get_clean();
        } else {
            echo 'Access Denied';
        }
    }

    /**
     *    ---------------------------------------------------
     *    Ajax url handle
     *    ---------------------------------------------------
     */

    public function fpi_form_submit()
    {
        if (wp_doing_ajax() &&  wp_verify_nonce($_POST['nonce'], 'fpi_admin_save')) {
            $jsonData = wp_unslash($_POST['jsonData']);
            $service = new Fpi_Service();
            $result = $service->fpi_process_fb_data($jsonData);
            wp_send_json($result);
        } else {
            wp_send_json([['status'=>'fail', 'msg'=>'API access deny']]);
        }
    }
}
