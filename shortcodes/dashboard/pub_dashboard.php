<?php

// Pub Dashboard Shortcode
function shortcode_pub_dashboard_function() {
  
  $string = '';
  
  $site_url = get_site_url();
  $url_pub_id = $_GET['pub_id'];
  $current_user_id = strval(get_current_user_id());
  
  $entry_count = 0;
  global $wpdb;
  // First check if the logged in user has pubs that have been claimed
  $entry_id_objects = $wpdb->get_results("SELECT id FROM wp_gf_entry WHERE form_id=6 AND created_by=" . $current_user_id);
  
  if ($entry_id_objects) {
    
    foreach($entry_id_objects as $entry_id_object) {
      
      $entry_id = $entry_id_object->id;
      $pub_id_objects = $wpdb->get_results("SELECT meta_value FROM wp_gf_entry_meta WHERE meta_key=1 AND entry_id=" . $entry_id);
      
      foreach($pub_id_objects as $pub_id_object) {
        
        $pub_id = $pub_id_object->meta_value;
        if ($pub_id == $url_pub_id) {
          $entry_count++;
        } else {}
        
      }
      
    }
  }
  
  if ($entry_count >= 1) {
    
    $form_page_array = array(
      'general_features',
      'image_gallery',
      'accommodation',
      'drinks',
      'entertainment',
      'food',
      'games',
    );
    
    if ($form_page_array) {
      $string .= '<div class="pub-dashboard">';
      foreach($form_page_array as $pub_dashboard_section) {
        
        if ($pub_dashboard_section == 'general_features') {
          $title = 'General Features';
          $claim_form_id = '13';
          $edit_form_id = '14';
          $claim_url = $site_url . '/claim-a-pub/claim-general-features?pub_id=' . $url_pub_id;
          $edit_url = $site_url . '/claim-a-pub/edit-general-features?pub_id=' . $url_pub_id;
          $coming_soon = false;
        } elseif ($pub_dashboard_section == 'image_gallery') {
          $title = 'Image Gallery';
          $claim_form_id = '15';
          $edit_form_id = false;
          $claim_url = $site_url . '/claim-a-pub/claim-image-gallery?pub_id=' . $url_pub_id;
          $edit_url = $site_url . '/claim-a-pub/edit-image-gallery?pub_id=' . $url_pub_id;
          $coming_soon = false;
        } elseif ($pub_dashboard_section == 'accommodation') {
          $title = 'Accommodation';
          $claim_form_id = false;
          $edit_form_id = false;
          $claim_url = $site_url . '/claim-a-pub/claim-accommodation?pub_id=' . $url_pub_id;
          $edit_url = $site_url . '/claim-a-pub/edit-accommodation?pub_id=' . $url_pub_id;
          $coming_soon = true;
        } elseif ($pub_dashboard_section == 'drinks') {
          $title = 'Drinks';
          $claim_form_id = false;
          $edit_form_id = false;
          $claim_url = $site_url . '/claim-a-pub/claim-drinks?pub_id=' . $url_pub_id;
          $edit_url = $site_url . '/claim-a-pub/edit-drinks?pub_id=' . $url_pub_id;
          $coming_soon = true;
        } elseif ($pub_dashboard_section == 'entertainment') {
          $title = 'Entertainment';
          $claim_form_id = false;
          $edit_form_id = false;
          $claim_url = $site_url . '/claim-a-pub/claim-entertainment?pub_id=' . $url_pub_id;
          $edit_url = $site_url . '/claim-a-pub/edit-entertainment?pub_id=' . $url_pub_id;
          $coming_soon = true;
        } elseif ($pub_dashboard_section == 'food') {
          $title = 'Food';
          $claim_form_id = false;
          $edit_form_id = false;
          $claim_url = $site_url . '/claim-a-pub/claim-food?pub_id=' . $url_pub_id;
          $edit_url = $site_url . '/claim-a-pub/edit-food?pub_id=' . $url_pub_id;
          $coming_soon = true;
        } elseif ($pub_dashboard_section == 'games') {
          $title = 'Games';
          $claim_form_id = false;
          $edit_form_id = false;
          $claim_url = $site_url . '/claim-a-pub/claim-games?pub_id=' . $url_pub_id;
          $edit_url = $site_url . '/claim-a-pub/edit-games?pub_id=' . $url_pub_id;
          $coming_soon = true;
        } else {
          $title = false;
          $claim_form_id = false;
          $edit_form_id = false;
          $coming_soon = false;
        }
        
        // If this section is not coming soon, we need to query the database
        if (!$coming_soon) {
          $section_claim_count = 0;
          // Check if this user has got an entry for the form for this section
          $section_claims = $wpdb->get_results("SELECT id FROM wp_gf_entry WHERE form_id=" . $claim_form_id . " AND created_by=" . $current_user_id);
          
          if ($section_claims) {
            // This means that there are claims on this section form from the current user
            // We now need to check if any of those claims are for this pub
            foreach($section_claims as $section_claim) {
              $section_claim_entry_id = $section_claim->id;
              $section_claim_pub_ids_objects = $wpdb->get_results("SELECT meta_value FROM wp_gf_entry_meta WHERE meta_key=1 AND entry_id=" . $section_claim_entry_id);
              
              if ($section_claim_pub_ids_objects) {
                
                foreach($section_claim_pub_ids_objects as $section_claim_pub_ids_object) {
                  
                  $section_claim_pub_id = $section_claim_pub_ids_object->meta_value;
                  if ($section_claim_pub_id == $url_pub_id) {
                    $section_claim_count++;
                    $section_claim_id = $section_claim_entry_id;
                  } else {}
                  
                }
                
              } else {}
              
            }
            
          } else {}
          
          if ($section_claim_count >= 1) {
          
            // This means that this section for this pub has been claimed before
            // And should therefore reference the Edit forms, pages etc
            
            $status = 'edit';
            
          } else {
            
            // This means that this section for this pub has not been claimed before
            // And therefore should reference the Claim forms, pages etc
            
            $status = 'claim';
            
          }
          
        } else {
          $status = false;
        }
        
        $string .= '<div class="pub-dashboard-panel ' . $pub_dashboard_section . '">';
        $string .= '<div class="title">';
        $string .= '<h2>' . $title . '</h2>';
        if ($status == 'claim') {
          
          $string .= '<div class="pub-dashboard-button">';
          $string .= '<a href="' . $claim_url . '">Claim</a>';
          $string .= '</div>';
          
        } elseif ($status == 'edit') {
          
          $string .= '<div class="pub-dashboard-button">';
          $string .= '<a href="' . $edit_url . '&entry_id=' . $section_claim_id . '">Edit</a>';
          $string .= '</div>';
          
        } else {
          
        }
        if ($coming_soon) {
          //$string .= do_shortcode['[fl_builder_insert_layout id="121357"]'];
          $string .= do_shortcode('[fl_builder_insert_layout id=121357]');
        }
        $string .= '</div>';
        $string .= '</div>';
        
      }
      $string .= '</div">';
    }
    ?>
<style>
  .pub-dashboard-button a {
    background-color:#c63c37;
    color: #ffffff;
    border:2px solid #c63c37;
    padding:8px 24px;
    transition:all 0.5s;
    border-radius:4px;
    font-weight:bold;
    text-transform: uppercase;
    display:inline-block;
  }
  .pub-dashboard-button a:hover {
    background-color:#ffffff;
    color: #c63c37;
  }
</style>
<?php
    
  } else {
    // The user does doesn't corespond to the pub id. 
  }
  
  
  return $string;
}
add_shortcode('pub_dashboard', 'shortcode_pub_dashboard_function');