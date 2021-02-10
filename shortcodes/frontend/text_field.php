<?php

function gpg_pub_text_field_shortcode($atts = array()) {
  
  // set up default parameters
  extract(shortcode_atts(array(
    'form_id' => '6',
    'field_key' => '135',
    'heading' => '',
  ), $atts));
  
  $string = '';
  
  if (is_singular('pub')) {
    
    $pub_id = get_the_ID();
    
    $args = array(
      'posts_per_page'    => 1,
      'post_type'         => 'claim',
      'meta_query' => array(
        array(
          'key' => 'claim_pub_id',
          'value' => $pub_id,
          'compare' => '='
        )
      )
    );
    
    $the_query = new WP_Query( $args );

    if ( $the_query->have_posts() ) {
      while( $the_query->have_posts() ) {
        $the_query->the_post();
        
        // Get the user ID of this claim
        $user_id = get_field('claim_user_id');
        
        if ($user_id) {
          
          global $wpdb;
          
          // Get the rows associated with the pub
          $entry_objects = $wpdb->get_results("SELECT entry_id FROM wp_gf_entry_meta WHERE meta_key=1 AND meta_value=" . $pub_id . " AND form_id=" . $form_id);
          
          $entry_array = array();
          if ($entry_objects) {
            foreach($entry_objects as $entry_object) {
              
              // Get the rows that are associated to the user & associated to the pub
              $user_entries = $wpdb->get_results("SELECT id FROM wp_gf_entry WHERE created_by=" . $user_id . " AND form_id=" . $form_id . " AND id=" . $entry_object->entry_id);
              
              if ($user_entries) {
                
                foreach($user_entries as $user_entry) {
                  
                  // Add this entry ID to the array of entries associated to this user & pub
                  array_push($entry_array, $user_entry->id);
                  
                }
                
              }
              
            }
            if (!empty($entry_array)) {
              // Reverse order of the array, so we get the latest entry ID first
              $entry_array = array_reverse($entry_array);
              
              foreach($entry_array as $entry) {
                
                // Look for the text field within this entry
                $pub_text_object = $wpdb->get_results("SELECT meta_value FROM wp_gf_entry_meta WHERE entry_id=" . $entry . " AND meta_key=" . $field_key);
                
                if ($pub_text_object) {
                  
                  $string .= '<div class="text-field text-field-' . $form_id .'-' . $field_key . '">';
                  
                  if ($heading) {
                    $string .= '<h2>' . $heading . '</h2>';
                  }
                  
                  $string .= '<p>' . $pub_text_object[0]->meta_value . '</p>';
                  
                  $string .= '</div>';
                  
                  break;
                  // Break exits out of the foreach when the field is found
                  
                } else {}
                
              }
              
            }
          }
          
        }
        
      }
    }
    else {}
    
    wp_reset_postdata();
    
  }
  
  return $string;
}
add_shortcode('pub_claim_text_field', 'gpg_pub_text_field_shortcode');