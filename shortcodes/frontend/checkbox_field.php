<?php

function gpg_pub_checkbox_field_shortcode($atts = array()) {
  
  // set up default parameters
  extract(shortcode_atts(array(
    'form_id' => '18',
    'field_key' => '144',
    'label' => 'Checkbox label',
  ), $atts));
  
  $string = '';
  $string .= '<div class="builder-only" style="display:none;">Checkbox Field</div>';
  
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
        
        // Get all the entries that are related to the pub
        
        global $wpdb;
        $entry_meta_objects = $wpdb->get_results("SELECT entry_id FROM wp_gf_entry_meta WHERE meta_key=1 AND meta_value=" . $pub_id . " AND form_id=" . $form_id);
        $entry_id_array = array();
        
        if ($entry_meta_objects) {
          foreach($entry_meta_objects as $entry_meta_object) {
            array_push($entry_id_array, $entry_meta_object->entry_id);
          }
          // Sort the array by biggest number first
          // This way we get the latest entry first
          rsort($entry_id_array);
        }
        
        if ($entry_id_array) {
          
          $user_entry_id_array = array();
          foreach($entry_id_array as $entry_id) {
            
            // Go to the gravity forms entry table & only get the entries that are linked to the claimed user id
            $entry_objects = $wpdb->get_results("SELECT id FROM wp_gf_entry WHERE created_by=" . $user_id . " AND id=" . $entry_id);
            
            if ($entry_objects) {
              // Because were doing a DB search via the ID, its either going to find 1 record, or no records. That way, we don't need to do a foreach, just a [0]
              array_push($user_entry_id_array, $entry_objects[0]->id);
            }
            
          }
        }
        // The $user_entry_id_array array variable contains the entry IDs for the specific pub that are associated to the correct claim user, with the latest entry first
        if ($user_entry_id_array) {
          $approved_entry_array = array();
          // We now need loop through these entry IDs & check if the entry has been approved
          foreach($user_entry_id_array as $user_entry_id) {
            
            $workflow_status_object = $wpdb->get_results("SELECT meta_value FROM wp_gf_entry_meta WHERE meta_key='workflow_final_status' AND meta_value='approved' AND entry_id=" . $user_entry_id);
            
            // If something is found in this database call, it means the entry is approved
            if ($workflow_status_object) {
              
              array_push($approved_entry_array, $user_entry_id);
              // End the foreach loop
              break;
            } else {
              // If the workflow is not set, it may mean that this is the imported data
              // We need to look in the meta if 'is_approved' is set to 1
              
              $approved_status_object = $wpdb->get_results("SELECT entry_id FROM wp_gf_entry_meta WHERE meta_key='is_approved' AND meta_value=1 AND entry_id=" . $user_entry_id);
              
              if ($approved_status_object) {
                // This means that an imported entry has been found.
                // Push this current entry into the approved entry array & break
                array_push($approved_entry_array, $user_entry_id);
                // End the foreach loop
                break;
              }
            }
            
          }
        }
        
        if ($approved_entry_array) {
          // We have approved entries from the confirmed claim user in this array. We display the text from this entry!
          $latest_approved_entry_id = $approved_entry_array[0];
          
          // For checkbox values, add '.1' to the end of the field key
          $field_key = $field_key . '.1';
          // Look for the text field within this entry
          $pub_text_object = $wpdb->get_results("SELECT meta_value FROM wp_gf_entry_meta WHERE entry_id=" . $latest_approved_entry_id . " AND meta_key=" . $field_key);
          
          if ($pub_text_object && $pub_text_object[0]->meta_value == "1") {
                  
            $string .= '<div class="checkbox-field checbox-field-' . $form_id .'-' . $field_key . '">';

            $string .= '<p>' . $label . '</p>';

            $string .= '</div>';

          } else {
            $string .= 'nothing';
          }
          
        }
        
      }
    }
    else {}
    
    wp_reset_postdata();
    
  }
  
  return $string;
}
add_shortcode('pub_claim_checkbox_field', 'gpg_pub_checkbox_field_shortcode');