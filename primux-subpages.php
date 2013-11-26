<?php
/*
Plugin Name: Primux Subpages
Plugin URI: http://primux.dk
Description: Widget to show all subpages of the parent page.
Version:1.0
Author: Primux Media
Author URI: http://primux.dk
*/

// First I register the Widget with the help of a action
function mux_subpages_register_widgets() {
	register_widget('mux_subpages');
}

add_action( 'widgets_init', 'mux_subpages_register_widgets' );


// Next we creat a Class which contains the widget
class mux_subpages extends WP_Widget {
	// The first function is what is going to define this widget for wordpress
	// Remember same name as the $this class
	function __construct()
	{
		$widget_options = array( 'description' => __('Widget to show all subpages of the parent page.'));
		parent::__construct('subpages', __('Sub Pages'),$widget_options);
	}
	// This is what we execute on the actually site
	function widget($args, $instance){
		global $post;
		extract($args);
		$title = apply_filters('widget_title', $instance['title'] );
		/* Before widget (defined by themes). */
		echo $before_widget;
		
		// Determine which page we are on, and finding the Top page
		if($post->post_parent) { //If we are in a subpage we do this
			$ancestors = $post->ancestors;
			$ancestor_num = count($ancestors) - 1;
			$child_of = $ancestors[$ancestor_num];
			$parent_title = get_the_title($child_of);
		} else { // Else we do this
			$child_of = $post->ID;
			$parent_title = get_the_title($post->ID);
		}
		// We look at if the Title show be the Top Page
		if($instance['top_page_title']) {
			if($instance['top_page']) {
				// Show the Title be a link ?
				$title = "<a href=\"".get_permalink($child_of)."\">".$parent_title."</a>";
			} else {
				// Or just a Title
				$title = $parent_title;
			}
		}
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		/* Here we Echo out the pages */
		echo "<ul>";
		wp_list_pages('child_of=' .$child_of . '&title_li=&depth='.$instance['depth']);
		echo "</ul>";
		/* After widget (defined by themes). */
	 	echo $after_widget;
	 }
	 // This is the form for the admin page
	 function form($instance) {
	 	$title = esc_attr($instance['title']);
	 	$depth = esc_attr($instance['depth']);
	 	$top_page = esc_attr($instance['top_page']);
	 	$top_page_title = esc_attr($instance['top_page_title']);
?>
	 	<p>
			<label for="<?php echo $this->get_field_id('Title'); ?>">Title:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('depth'); ?>">Depth:</label>
			<select class="widefat" id="<?php echo $this->get_field_id('depth'); ?>"  name="<?php echo $this->get_field_name('depth'); ?>" >
<?php 
			for($i=0;$i<5;$i++) {
				if($i == $depth) {
					echo "<option value=\"".$i."\" selected>".$i."</option>";
				} else {
					echo "<option value=\"".$i."\">".$i."</option>";
				}
			}
?>				
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('Title'); ?>">Use Top page name as Title:
			<input type="checkbox" value="TRUE" id="<?php echo $this->get_field_id('top_page_title'); ?>" name="<?php echo $this->get_field_name('top_page_title'); ?>" <?php if($top_page_title) echo "checked"; ?>>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('Title'); ?>">Make Title a link:
			<input type="checkbox" value="TRUE" id="<?php echo $this->get_field_id('top_page'); ?>" name="<?php echo $this->get_field_name('top_page'); ?>" <?php if($top_page) echo "checked"; ?>>
			</label>
		</p>
		
<?php 		
	 	
	 }
}
?>
