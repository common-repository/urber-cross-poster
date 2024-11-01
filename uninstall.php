<?php

//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit();

defined("__CROSS_POSTER_PLUGIN_SLUG__") or define("__CROSS_POSTER_PLUGIN_SLUG__", "__crossposter_");

$opt    = array("username", "share", "floating", "floating_img", "floating_pos", "sidepanel", "sidepanel_img", "last-check");
foreach($opt as $option){
    delete_option(__CROSS_POSTER_PLUGIN_SLUG__ . $option);
}
