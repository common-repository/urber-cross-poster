<?php
/**
* Plugin Name: URBELLO Cross Poster
* Plugin URI: http://blog.urbello.com/wpplugins
* Description: Share your stories on <strong>the City Loving</strong> platform. To get started: 1) Click "Activate" on the left of this description. 2) Go to the +Urbello plugin settings which will appear in the tools below Settings or once you've clicked Activate <a href="/wp-admin/admin.php?page=__crossposter_">click here - +Urbello settings</a>.
* Version: 1.6
* Author: +Urbello.com
* Author URI: http://www.urbello.com/
* License: GPL2
*/
/*
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

// define("__CROSS_POSTER_BASE_URL__", "http://localhost:9000");
define("__CROSS_POSTER_BASE_URL__", "http://www.urbello.com");

define("__CROSS_POSTER_PLUGIN_NAME__", "URBELLO Cross Poster");
define("__CROSS_POSTER_PLUGIN_SLUG__", "__crossposter_");
define("__CROSS_POSTER_VERSION__", 1.6);
define("__CROSS_POSTER_DIR__", trailingslashit(plugin_dir_path(__FILE__)));
define("__CROSS_POSTER_URL__", plugin_dir_url(__FILE__));
define("__CROSS_POSTER_ROOT__", trailingslashit(plugins_url("", __FILE__)));
define("__CROSS_POSTER_RESOURCES__", __CROSS_POSTER_ROOT__ . "resources/");
define("__CROSS_POSTER_IMAGES__", __CROSS_POSTER_RESOURCES__ . "images/");
define("__CROSS_POSTER_API_URL_POST__", __CROSS_POSTER_BASE_URL__ . "/api/wp/post");
define("__CROSS_POSTER_API_URL_LOGIN__", __CROSS_POSTER_BASE_URL__ . "/api/wp/loggedin");
define("__CROSS_POSTER_IMAGE_URL__", __CROSS_POSTER_BASE_URL__ . "/{author}");
define("__CROSS_POSTER_PING_DAYS__", 1);
define("__CROSS_POSTER_DEBUG__", false);
define("__CROSS_POSTER_TEST__", false);
define("__CROSS_POSTER_STAGING__", false);

if(__CROSS_POSTER_DEBUG__){
    @error_reporting(E_ALL);
    @ini_set("display_errors", "1");
}

/**
 * Abort loading if WordPress is upgrading
 */
if (defined("WP_INSTALLING") && WP_INSTALLING) return;

class CrossPoster{

    private $error;
    private $notice;

    public function __construct(){
        // all hooks and actions
        add_action("init", array($this, "crossPoster_register"));
        register_activation_hook(__FILE__ , array($this, "crossPoster_activate"));
        register_deactivation_hook(__FILE__ , array($this, "crossPoster_deactivate"));
        add_action("wp_enqueue_scripts", array($this, "crossPoster_includeResources"));
        add_action("admin_enqueue_scripts", array($this, "crossPoster_includeResources"));
        add_action("plugins_loaded", array($this, "crossPoster_i18n"));
        add_action("wp_footer", array($this, "crossPoster_footer"));
        add_action("admin_footer", array($this, "crossPoster_footer_admin"));
        add_action("post_submitbox_misc_actions", array($this, "crossPoster_showExtra"));
        add_action("admin_menu", array($this, "crossPoster_add_menu"));
        add_action("save_post", array($this, "crossPoster_savePost"));
        add_action("widgets_init", array($this, "crossPoster_widget"));
        add_action("admin_head-index.php", array($this, "crossPoster_admin_head"));
    }

    /**
     * Initializes the locale
     */
    function crossPoster_i18n(){
        $pluginDirName  = dirname(plugin_basename(__FILE__));
        $domain         = __CROSS_POSTER_PLUGIN_SLUG__;
        $locale         = apply_filters("plugin_locale", get_locale(), $domain);
        load_textdomain($domain, WP_LANG_DIR . "/" . $pluginDirName . "/" . $domain . "-" . $locale . ".mo");
        load_plugin_textdomain($domain, "", $pluginDirName . "/resources/lang/");
    }

    function crossPoster_footer_admin(){
        @session_start();
        include_once __CROSS_POSTER_DIR__ . "resources/admin/includes/footer.php";
    }

    function crossPoster_footer(){
        if(self::getOption("floating") == 1){
            $floating_img   = self::getOption("floating_img");
            $floating_pos   = self::getOption("floating_pos");
            $link           = self::getImageLink();
            $link           = "<div class='cp_floating cp_$floating_pos'>"
                            . "<a href='{$link}' target='_new'>"
                            . "<img src='" . __CROSS_POSTER_IMAGES__ . $floating_img . "'>" 
                            . "</a></div>";
            echo $link;
        }
    }

    /**
     * Initializes the admin menu
     */
    function crossPoster_add_menu(){
        add_menu_page(__CROSS_POSTER_PLUGIN_NAME__, NULL, 'manage_options', __CROSS_POSTER_PLUGIN_SLUG__, array($this, 'crossPoster_settings'), __CROSS_POSTER_IMAGES__ . 'floating2.png');
    }

    /**
     * Saves settings from the settings screen
     */
    function crossPoster_settings(){
        if(isset($_POST["cp-submit"])){
            self::saveSettings();
        }
        include_once __CROSS_POSTER_DIR__ . "resources/admin/includes/settings.php";
    }

    /**
     * Loads the JS and CSS resources
     */
    function crossPoster_includeResources() {
        wp_enqueue_script("jquery");

        wp_register_script("cp", __CROSS_POSTER_RESOURCES__ . "js/cp.js");
        wp_enqueue_script("cp");

        wp_register_style("cp", __CROSS_POSTER_RESOURCES__ . "css/cp.css");
        wp_enqueue_style("cp");
    }

    /**
     * Register the plugin
     */
    function crossPoster_register(){
        // do nothing
    }

    /**
     * Activate the plugin
     */
    function crossPoster_activate(){
        // do nothing
        self::setOption("share", 1);
    }

    /**
     * Deactivate the plugin
     */
    function crossPoster_deactivate(){
        if(__CROSS_POSTER_TEST__ || __CROSS_POSTER_STAGING__){
            define("WP_UNINSTALL_PLUGIN", true);
            include_once __CROSS_POSTER_DIR__ . "uninstall.php";
        }
    }

    function crossPoster_showExtra(){
        global $post, $pagenow;
        if(__CROSS_POSTER_TEST__ || ($post->post_type == "post" && $pagenow == "post-new.php")){
            @session_start();
            include_once __CROSS_POSTER_DIR__ . "resources/admin/includes/extra.php";
        }
    }

    function crossPoster_savePost($postID){
        if(!isset($_POST["cp_crosspost"])) return;

        $post       = get_post($postID);

        $content    = strip_tags(strip_shortcodes($post->post_content));
        $img        = NULL;
        $imgContent = NULL;
        if(has_post_thumbnail($post->ID)){
            $imgContent = get_the_post_thumbnail($post->ID);
        }else{
            $imgContent = $post->post_content;
        }
        $matches    = array();
        $output     = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $imgContent, $matches);
        if(!empty($matches[1])){
            $img    = $matches[1][0];
        }
        $link       = get_permalink($post->ID);

        self::writeDebug("image = " . $img);
        self::writeDebug("content = " . $content);

        global $current_user;
        get_currentuserinfo();

        $email      = $current_user->user_email;
        $username   = $current_user->user_login;
        $siteurl    = site_url();

        $params     = array(
                        "account"   => array(
                                        "emailAddress" => $email,
                                        "username" => $username,
                                        "siteUrl" => $siteurl,
                        ),
                        "post"      => array(
                                        "subject"   => $post->post_title,
                                        "text"      => str_replace(array("\r","\n", '"'), array("", "", '\"'), wpautop($content)),
                                        "imageUrl"  => $img,
                                        "postLink"  => $link,
                                        "postID"    => $post->ID,
                        )
        );
        self::writeDebug("params = " . print_r($params, true));
        @session_start();
        $_SESSION["cp_api_json"]   = json_encode($params);
        self::writeDebug("cp_api_json in session " . $_SESSION["cp_api_json"]);
    }

    function saveSettings(){
        self::setOption("username", @$_POST["urbello_user"]);
        self::setOption("share", @$_POST["share"]);
        self::setOption("floating", @$_POST["floating"]);
        self::setOption("floating_img", @$_POST["floating_img"]);
        self::setOption("floating_pos", @$_POST["floating_pos"]);
        self::setOption("sidepanel", @$_POST["sidepanel"]);
        self::setOption("sidepanel_img", @$_POST["sidepanel_img"]);
    }

    function crossPoster_admin_head(){
        $last           = self::getOption("last-check");
        $check          = $last ? FALSE : TRUE;
        if(!$check){
            $check      = (time() - $last) / (24 * 60 * 60 * 1000) > __CROSS_POSTER_PING_DAYS__;
        }

        if($check){
            self::setOption("last-check", time());
            require_once __CROSS_POSTER_DIR__ . "/resources/admin/includes/login-check.php";
        }
    }

    function crossPoster_widget(){
        register_widget("UrbelloWidget");
    }

    static function getImageLink(){
        $link           = __CROSS_POSTER_IMAGE_URL__;
        $author         = "";
        if(strlen(self::getOption("username")) > 0){
            $author     = "authors/" . self::getOption("username");
        }
        $link           = str_replace("{author}", $author, $link);
        return $link;
    }

    /****************************************** Util functions ******************************************/

    /**
     * Writes to the file /tmp/log.log if DEBUG is on
     */
    public static function writeDebug($msg){
        if(__CROSS_POSTER_DEBUG__) file_put_contents(__CROSS_POSTER_DIR__ . "tmp/log.log", date("F j, Y H:i:s") . " - " . $msg."\n", FILE_APPEND);
    }

    /**
     * Custom wrapper for the get_option function
     * 
     * @return string
     */
    public static function getOption($field, $clean=false){
        $val = get_option(__CROSS_POSTER_PLUGIN_SLUG__ . $field);
        return $clean ? htmlspecialchars($val) : $val;
    }

    /**
     * Custom wrapper for the update_option function
     * 
     * @return mixed
     */
    public static function setOption($field, $value){
        return update_option(__CROSS_POSTER_PLUGIN_SLUG__ . $field, $value);
    }

    /**
     * Custom wrapper for the get_post_meta function
     * 
     * @return mixed
     */
    public static function getPostMeta($postID, $name, $single=true){
        return get_post_meta($postID, __CROSS_POSTER_PLUGIN_SLUG__ . $name, $single);
    }

    /**
     * Custom wrapper for the update_post_meta function
     */
    public static function setPostMeta($postID, $name, $value){
        update_post_meta($postID, __CROSS_POSTER_PLUGIN_SLUG__ . $name, $value);
    }

}

$crossPoster = new CrossPoster();


include_once __CROSS_POSTER_DIR__ . "resources/admin/widget.php";
