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
 * @author     Bob Xiao <wanbo.xiao@gmail.com>
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

    //---------------------------------------------------
    // MENU SECTION
    //---------------------------------------------------
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


    //---------------------------------------------------
    // Ajax
    //---------------------------------------------------
    public function fpi_form_submit()
    {
        // access right ?????
        if (wp_doing_ajax() &&  wp_verify_nonce($_POST['nonce'], 'fpi_admin_save')) {
            $jsonData = wp_unslash($_POST['jsonData']);
            $service = new Fpi_Service();
            $result = $service->fpi_process_fb_data($jsonData);
            wp_send_json($result);
        } else {
            echo esc_html__('unsafe', 'fpi');
        }
    }
    /*
        public function fpi_process_fb_data($jsonData)
        {
            $returnData = [];
            $data = json_decode($jsonData, true);
            if (isset($data['data']) && is_array($data['data'])) {
                foreach ($data['data'] as $facebook_post) {
                    $returnData[] = $this->fpi_create_post($facebook_post);
                }
            } else {
                $returnData[] = [
                    'status' => 'fail',
                    'msg' => esc_html__('Invalid data format(Json needed)', 'fpi'),
                ];
            }
            return $returnData;
        }
    
        public function fpi_create_post(array $facebook_post):array
        {
            if ($this->fpi_validate_post_data($facebook_post)) {
                $create_date = strtotime($facebook_post['created_time']);
                $content =
                "Post from Facebook user".$facebook_post['from']['name']."<br>"
                ."<a href='http://trib.al/NR1shY9'>".$facebook_post['name']."</a></br>"
                 .$facebook_post['message'] ."<br/>";
                $postarr = [
                    'post_date'=> date("Y-m-d H:i:s", $create_date),
                    'post_content'=>$content,
                    'post_title'=>$facebook_post['name'],
                    'post_status' => 'Publish'
                ];
                $post_id = wp_insert_post($postarr);
    
                return [
                    'status'=> esc_html__('success', 'fpi'),
                    'msg' => 'Facebook post '. $facebook_post['id']. " has been inserted. <a href='".get_permalink($post_id)."'>View</a><br/>",
                ];
            } else {
                return [
                    'status' => 'fail',
                    'msg' => esc_html__('Invalid facebook data format '.((isset($facebook_post['id'])?'at facebook ID:'.$facebook_post['id'] : '')), 'fpi'),
                ];
            }
        }
    
        private function fpi_validate_post_data(array $facebook_post):bool
        {
            return (isset($facebook_post['id'])
                && isset($facebook_post['created_time'])
                && isset($facebook_post['from']['name'])
                && isset($facebook_post['name'])
                && isset($facebook_post['message'])
            );
        }*/
}
