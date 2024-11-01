<?php
/*
Plugin Name: Simple Tag By Category 
Author: harshsoni_w3
Description: This Widget Displays tag by Specific Category
Version: 0.2
*/

// Creating the widget 
class wpb_widget extends WP_Widget {

function __construct() {
parent::__construct(
// Base ID of your widget
'wpb_widget', 

// Widget name will appear in UI
__('TagCloud By Category', 'wpb_widget_domain'), 

// Widget description
array( 'description' => __( 'Sample widget based on WPBeginner Tutorial', 'wpb_widget_domain' ), ) 
);
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
$category = $instance['category_id'];
// before and after widget arguments are defined by themes
echo '<section>';
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];

// This is where you run the code and display the output
global $post;
$args=array(
      'cat' => $category , //cat__not_in wouldn't work
      
      'showposts'=>-1,
     
    );
	query_posts($args);
			
			while (have_posts()) : the_post();
			$tagCount= 0;
			 $posttags =   get_the_tags();
		//var_dump($posttags);
		if ($posttags) {
			foreach($posttags as $tag) {
		
				$all_tags_arr[] = $tag -> term_id; 
			}
		}
		endwhile; 
		if($all_tags_arr)
		{
	 $tags_arr = array_unique($all_tags_arr);
	 
	foreach($tags_arr as $tag) {
		  $tagname = get_tag($tag); 
		$sortarray[$tag]=$tagname->name;
			}  
		asort($sortarray);
		$lastElement = end($sortarray);
		$tagCount =count($sortarray);
	// var_dump($tags_arr);
	$cat_tags='<div>';
	 foreach($sortarray as $k=>$tag) {
		$tagname = get_tag($k); // <-- your tag ID

				$cat_tags.= '<a href="'.get_tag_link( $k).'">'.$tagname->name.'</a>';
				if($tagCount>0 && $tag != $lastElement){ 
				$cat_tags.= ', ' ;
				} 
			$tagCount++;
			}  
		}
		$cat_tags.='</div></section>';
	 //var_dump($cat_tags); 
echo __( $cat_tags, 'wpb_widget_domain' );
echo $args['after_widget'];
}
		
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'New title', 'wpb_widget_domain' );
}
if ( isset( $instance[ 'category_id' ] ) ) {
$category_id = $instance[ 'category_id' ];
}
else {
$category_id = __( ' ', 'wpb_widget_domain' );
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
<label for="<?php echo $this->get_field_id( 'category_id' ); ?>"><?php _e( 'Caetegory ID:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'category_id' ); ?>" name="<?php echo $this->get_field_name( 'category_id' ); ?>" type="text" value="<?php echo esc_attr( $category_id ); ?>" />
</p>
<?php 
}
	
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
$instance['category_id'] = ( ! empty( $new_instance['category_id'] ) ) ? strip_tags( $new_instance['category_id'] ) : '';
return $instance;
}
} // Class wpb_widget ends here

// Register and load the widget
function wpb_load_widget() {
	register_widget( 'wpb_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );
