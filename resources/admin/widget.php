<?php

class UrbelloWidget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
        parent::__construct(
			"urbello_widget",
			__("Urbello Widget", __CROSS_POSTER_PLUGIN_SLUG__ ),
			array("description" => __("Urbello Widget", __CROSS_POSTER_PLUGIN_SLUG__))
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
        if(CrossPoster::getOption("sidepanel") == 1){
            $sidepanel_img  = CrossPoster::getOption("sidepanel_img");
            $link           = CrossPoster::getImageLink();
            $link           = "<div class='cp_sidepanel widget'>"
                            . "<a href='{$link}' target='_new'>"
                            . "<img src='" . __CROSS_POSTER_IMAGES__ . $sidepanel_img . "'>" 
                            . "</a></div>";
            echo $link;
        }
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		// do nothing
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		// do nothing
	}
}

