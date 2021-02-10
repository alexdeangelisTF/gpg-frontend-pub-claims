<?php

// Pub Dashboard Shortcode
function shortcode_gpg_get_url_parameter_function( $atts = array() ) {
  
  // set up default parameters
  extract(shortcode_atts(array(
    'param' => '', // The name of the parameter in the url
    'before' => '', // Any static text before the returned result
    'use_param' => 'no', // Use the parameter name in the returned string?
  ), $atts));
  
  $string = '';
  
  if ($param) {
    
    $url_param = $_GET[$param];
    
    if ($url_param) {
      
      if ($before) {
        $string .= $before;
      }
      
      if ($use_param == 'yes') {
        $string .= $param . '=';
      }
      
      $string .= $url_param;
    }
    
  } else {}
  
  return $string;
}
add_shortcode('gpg_get_url_parameter', 'shortcode_gpg_get_url_parameter_function');