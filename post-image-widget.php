<?php
/*
Plugin Name: Post Image Widget
Plugin URI: http://www.zenixsol.com
Description: This plugin provides a widget to post image directly from the frontpanel of your site without going into the backend.
Author: Salman Aslam
Version: 1.0
Author URI: http://www.zenixsol.com
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/


/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
add_action( 'widgets_init', 'piw_load_widgets' );

/**
 * Register our widget.
 * 'PIW_Widget' is the widget class used below.
 *
 * @since 0.1
 */
function piw_load_widgets() {
    register_widget( 'PIW_Widget' );
}
// return the path to where this plugin is currently installed
function get_plugin_url_piw() {
    // WP < 2.6
    if ( !function_exists( "plugins_url" ) )
        return get_option( "siteurl" ) . "/wp-content/plugins/" . plugin_basename( dirname( __FILE__ ) );
    else
        return plugins_url( plugin_basename( dirname( __FILE__ ) ) );
}
/**
 * Post Image Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */
class PIW_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function PIW_Widget() {
            $widget_ops = array('description' => __('A widget to post image directly from the frontpanel.', 'post-image-widget'));
            $control_ops = array('width' => '280');
            $this->WP_Widget('PIW', 'Quick Image Widget', $widget_ops, $control_ops);
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
           
            extract( $args );
            /* Our variables from the widget settings. */
            $title = apply_filters('widget_title', $instance['title'] );
            /* Before widget (defined by themes). */
            echo $before_widget;
            /* Display the widget title if one was input (before and after defined by themes). */
            if ( $title ) {
                echo $before_title . $title . $after_title;
            }
            include "post_image_widget_form.php";
            /* After widget (defined by themes). */
            echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
            $instance = $old_instance;
            
            /* Strip tags for title and name to remove HTML (important for text inputs). */
            $instance['title'] = strip_tags( $new_instance['title'] );
            $instance['frole'] = $new_instance['frole'];
            $instance['fstatus'] = $new_instance['fstatus'];
            $instance['srole'] = $new_instance['srole'];
            $instance['sstatus'] = $new_instance['sstatus'];
            print_r($instance);
            return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {
          
            ?>
            
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
                <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
            </p>
           
            <hr>
            <p>
                <label for="<?php echo $this->get_field_id( 'frole' ); ?>"><?php _e('First Role:', 'postimage'); ?></label>
                <select name="<?php echo $this->get_field_name( 'frole' ); ?>" id="<?php echo $this->get_field_id( 'frole' ); ?>" >
                    
                    <option value="administrator" <?php if ($instance['frole']=="administrator") echo "selected";?>>Administrator</option>
                    <option value="editor" <?php if ($instance['frole']=="editor") echo "selected";?>>Editor</option>
                    <option value="author" <?php if ($instance['frole']=="author") echo "selected";?>>Author</option>
                    <option vale="contributor" <?php if ($instance['frole']=="contributor") echo "selected";?>>Contributor</option>
                    <option value="subscriber" <?php if ($instance['frole']=="subscriber") echo "selected";?>>Subscriber</option>
                </select>
            </p>
            <p>
              <label for="<?php echo $this->get_field_id( 'fstatus' ); ?>"><?php _e('Post Status:', 'postimage'); ?></label>  
              <select name="<?php echo $this->get_field_name( 'fstatus' ); ?>" id="<?php echo $this->get_field_id( 'fstatus' ); ?>">
                  <option value="publish" <?php if ($instance['fstatus']=="publish") echo "selected";?>>Publish</option>
                  <option value="pending" <?php if ($instance['fstatus']=="pending") echo "selected";?>>Pending</option>
                  <option value="draft" <?php if ($instance['fstatus']=="draft") echo "selected";?>>Draft</option>
              </select>
            <hr>
            
            
            <p>
                <label for="<?php echo $this->get_field_id( 'srole' ); ?>"><?php _e('Second Role:', 'postimage'); ?></label>
                <select name="<?php echo $this->get_field_name( 'srole' ); ?>" id="<?php echo $this->get_field_id( 'srole' ); ?>" >
                  
                    <option value="administrator" <?php if ($instance['srole']=="Administrator") echo "selected";?>>Administrator</option>
                    <option value="editor" <?php if ($instance['srole']=="editor") echo "selected";?>>Editor</option>
                    <option value="author" <?php if ($instance['srole']=="author") echo "selected";?>>Author</option>
                    <option vale="contributor" <?php if ($instance['srole']=="contributor") echo "selected";?>>Contributor</option>
                    <option value="subscriber" <?php if ($instance['srole']=="subscriber") echo "selected";?>>Subscriber</option>
                </select>
            </p>
            <p>
              <label for="<?php echo $this->get_field_id( 'sstatus' ); ?>"><?php _e('Post Status:', 'postimage'); ?></label>  
              <select name="<?php echo $this->get_field_name( 'sstatus' ); ?>" id="<?php echo $this->get_field_id( 'sstatus' ); ?>">
                   <option value="publish" <?php if ($instance['sstatus']=="publish") echo "selected";?>>Publish</option>
                  <option value="pending" <?php if ($instance['sstatus']=="pending") echo "selected";?>>Pending</option>
                  <option value="draft" <?php if ($instance['sstatus']=="draft") echo "selected";?>>Draft</option>
              </select>
            <hr>
            </p>
	<?php
	}
}

?>