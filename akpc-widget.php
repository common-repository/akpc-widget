<?php
//-----------------------------------------------------------------------------
/*
Plugin Name: Popularity Contest (AKPC) Widget
Version: 1.0
Plugin URI: http://nullpointer.debashish.com
Description: Sidebar widget version of <a href="http://alexking.org/projects/wordpress">Popularity Contest plugin</a> by Alex King. Please install and activate the plugin before using this widget.
Author: Debashish Chakrabarty
Author URI: http://www.debashish.com
Min WP Version: 2.7
*/
//-----------------------------------------------------------------------------
?>
<?php
// the sidebar widget
function dc_popcontest_widget( $args ) {
  // args
  extract( $args ); // extract args

  // options
  $options = get_option('dc_popcontest_widget'); // get options    
  echo $before_widget;
  echo $before_title . $options['title'] . $after_title;
  echo '<ul class="'.$options['ulclass'].'">';
  if (function_exists('akpc_most_popular')) {
		akpc_most_popular($limit=5);
  }	
  else {
	echo '<li>The Popularity Contest Widget needs Alex Kings <a href="http://alexking.org/projects/wordpress">Popularity Contest</a> plugin to function. Please install and activate the plugin first.</li>';
  }
  echo '</ul>';
  echo $after_widget;  
  // output done
  return;  
}

function dc_popcontest_widget_control() {

  // options
  $options = $newoptions = get_option('dc_popcontest_widget'); // get options
  
  // set new options
  if( $_POST['dc-popcontest-widget-submit'] ) {
    $newoptions['title'] = strip_tags( stripslashes($_POST['dc-popcontest-widget-title']) );
	$newoptions['ulclass'] = strip_tags( stripslashes($_POST['dc-popcontest-ul-classname']) );
  }
  
  // update options if needed
  if( $options != $newoptions ) {
    $options = $newoptions;
    update_option('dc_popcontest_widget', $options);
  }
  
  // output  
	echo '<p>'._e('Title');
    echo '<input type="text" style="width:300px" id="dc-popcontest-widget-title" name="dc-popcontest-widget-title" value="'.attribute_escape($options['title']).'" />'.'<br />';
	echo '</p>';  
	echo '<p>'._e('UL Class');
    echo '<input type="text" style="width:300px" id="dc-popcontest-ul-classname" name="dc-popcontest-ul-classname" value="'.attribute_escape($options['ulclass']).'" />'.'<br />';
	echo '</p>';  
	echo '<input type="hidden" name="dc-popcontest-widget-submit" id="dc-popcontest-widget-submit" value="1" />';
}

// activate and deactivate plugin
function popcontest_activate() {
  // options, defaultvalues
  $options = array( 
    'widget' => array( 
      'title' => 'Popular Posts',
	  'ulclass' => 'popularposts'
    )
  );
  
  // register option
  add_option( 'dc_popcontest_widget', $options['widget'] );  
  // activated
  return;
}

function popcontest_deactivate() {
  // unregister option
  delete_option('dc_popcontest_widget');   
  // deactivated
  return;
}

// initialization
function popcontest_init() {  
  // register widget
  $class['classname'] = 'dc_popcontest_widget';
  wp_register_sidebar_widget('related_posts', __('Popular Posts'), 'dc_popcontest_widget', $class);
  wp_register_widget_control('related_posts', __('Popular Posts'), 'dc_popcontest_widget_control', 'width=300&height=200');
  
  // initialization done
  return;  
}

// actions
add_action( 'activate_'.plugin_basename(__FILE__),   'popcontest_activate' );
add_action( 'deactivate_'.plugin_basename(__FILE__), 'popcontest_deactivate' );
add_action( 'init', 'popcontest_init');
?>