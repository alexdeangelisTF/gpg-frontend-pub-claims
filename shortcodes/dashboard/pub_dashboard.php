<?php

// Pub Dashboard Shortcode
function shortcode_pub_dashboard_function() {
  
  $string = '';
  global $wpdb;
  $site_url = get_site_url();
  $pub_id = $_GET['pub_id'];
  $current_user_id = strval(get_current_user_id());
  
  $dashboard_sections = array(
    'claim',
    'features',
    'food',
    'drinks',
    'games',
    'accommodation',
    'entertainment',
    'image_gallery',
  );
  
  $string .= '<div class="pub-dashboard">';
  
  foreach($dashboard_sections as $dashboard_section) {
    $empty = false;
    if ($dashboard_section == 'claim') {
      $form_id = 6;
      $form_url = $site_url . '/claim-a-pub/claim-form?pub_id=' . $pub_id;
      $title = 'Introduction';
    } elseif ($dashboard_section == 'features') {
      $form_id = 13;
      $form_url = $site_url . '/claim-a-pub/claim-general-features?pub_id=' . $pub_id;
      $title = 'General features';
    } elseif($dashboard_section == 'food') {
      $form_id = 17;
      $form_url = $site_url . '/claim-a-pub/claim-food?pub_id=' . $pub_id;
      $title = 'Food';
    } elseif($dashboard_section == 'drinks') {
      $form_id = 18;
      $form_url = $site_url . '/claim-a-pub/claim-drinks?pub_id=' . $pub_id;
      $title = 'Drinks';
    } elseif($dashboard_section == 'games') {
      $form_id = 19;
      $form_url = $site_url . '/claim-a-pub/claim-games?pub_id=' . $pub_id;
      $title = 'Games';
    } elseif($dashboard_section == 'accommodation') {
      $form_id = 20;
      $form_url = $site_url . '/claim-a-pub/claim-accommodation?pub_id=' . $pub_id;
      $title = 'Accommodation';
    } elseif($dashboard_section == 'entertainment') {
      $form_id = 21;
      $form_url = $site_url . '/claim-a-pub/claim-entertainment?pub_id=' . $pub_id;
      $title = 'Entertainment'; 
    } elseif($dashboard_section == 'image_gallery') {
      $form_id = 15;
      $form_url = $site_url . '/claim-a-pub/claim-image-gallery?pub_id=' . $pub_id;
      $title = 'Image Gallery'; 
    } else {}
    
    
    // We need to get the latest entry id for the form from the user, for this pub.
    // Get all entry ids, which are matched to this pub id
    $pub_id_objects = $wpdb->get_results("SELECT entry_id FROM wp_gf_entry_meta WHERE meta_key=1 AND meta_value=" . $pub_id . " AND form_id=" . $form_id);

    if ($pub_id_objects) {
      $entry_id_array = array();
      foreach($pub_id_objects as $pub_id_object) {
        array_push($entry_id_array, $pub_id_object->entry_id);
      }
    } else {}
    if ($entry_id_array) {
      $user_entry_id_array = array();
      foreach($entry_id_array as $entry_id) {

        $pub_entry_objects = $wpdb->get_results("SELECT id FROM wp_gf_entry WHERE id=" . $entry_id . " AND form_id=" . $form_id . " AND created_by=" . $current_user_id);

        if ($pub_entry_objects) {
          foreach($pub_entry_objects as $pub_entry_object) {
            array_push($user_entry_id_array, $pub_entry_object->id);
          }
        }

      }
    } else {}
    
    // This allows all sections apart from Claim to be shown, even if there is no previous entry for it
    if ($user_entry_id_array || $dashboard_section != 'claim') {
      
      if ($user_entry_id_array) {
        // Sort the list of entries associated to this user & pub, so that the latest entry is first
        rsort($user_entry_id_array);
        // Get the first entry in the array, which should be the latest entry to this form
        $entry_id = $user_entry_id_array[0];
        // Get the status of the edit
        $workflow_status = $wpdb->get_results("SELECT meta_value FROM wp_gf_entry_meta WHERE entry_id=" . $entry_id . " AND form_id=" . $form_id . " AND meta_key='workflow_final_status'");
        // As entry is is unique, just get the first key in the array & get the meta value
        $edit_status = $workflow_status[0]->meta_value;
        if ($edit_status == 'approved') {
          $status_icon = 'fa-check';
          $status_colour = 'green';
        } elseif ($edit_status == 'pending') {
          $status_icon = 'fa-clock';
          $status_colour = 'orange';
        } elseif ($edit_status == 'rejected') {
          $status_icon = 'fa-times';
          $status_colour = 'red';
        } else {
          $status_icon = 'fa-pen';
          $status_colour = 'grey';
        }
        $form_url = $form_url . '&entry_id=' . $entry_id;
      } else {
        $status_icon = 'fa-pen';
        $status_colour = 'grey';
      }
      $string .= '<div class="pub-dashboard-section pub-dashboard-' . $dashboard_section . '">';
      $string .= '<div class="edit-status ' . $status_colour . '">';
      $string .= '<div class="icon">';
      //$string .= '<span class="fa-stack fa-2x">';
      //$string .= '<i class="fas fa-circle fa-stack-2x"></i>';
      //$string .= '<i class="fas ' . $status_icon . ' fa-stack-1x fa-inverse"></i>';
      $string .= '<i class="fas ' . $status_icon . '"></i>';
      //$string .= '</span>';
      $string .= '</div>';
      $string .= '</div>';
      $string .= '<h4>' . $title . '</h4>';
      $string .= '<div class="pub-dashboard-button">';
      $string .= '<a href="' . $form_url . '">Edit</a>';
      $string .= '</div>';
      $string .= '</div>';
    }
    
  }
  
  $string .= '</div>';
  
  $string .= '<style>
    .pub-dashboard {
      display:-webkit-box;
      display:-ms-flexbox;
      display:flex;
      -ms-flex-pack: distribute;
      justify-content: space-around;
      -ms-flex-wrap: wrap;
      flex-wrap: wrap;
    }
    .pub-dashboard .pub-dashboard-section {
      text-align:center;
    }
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
    .edit-status {
      padding-bottom:8px;
    }
    .edit-status .icon {
      width: 60px;
      height: 60px;
      border: 2px solid;
      border-radius: 50%;
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      -webkit-box-align: center;
      -ms-flex-align: center;
      align-items: center;
      -webkit-box-pack: center;
      -ms-flex-pack: center;
      justify-content: center;
      margin: auto;
    }
    .edit-status .icon {
      font-size:24px;
    }
    .edit-status.green .icon {
      border-color:#48C637;
    }
    .edit-status.green .icon i {
      color:#48C637;
    }
    .edit-status.orange .icon {
      border-color:#F1D35D;
    }
    .edit-status.orange .icon i {
      color:#F1D35D;
    }
    .edit-status.red .icon {
      border-color:#ED6863;
    }
    .edit-status.red .icon i {
      color:#ED6863;
    }
    .edit-status.grey .icon {
      border-color:#727373;
    }
    .edit-status.grey .icon i {
      color:#727373;
    }
    @media (max-width:1299px) {
      .pub-dashboard .pub-dashboard-section {
        width:25%;
        padding-bottom: 25px;
      }
    }
    @media (max-width:991px) {
      .pub-dashboard .pub-dashboard-section {
        width:50%;
      }
    }
  </style>';
  
  return $string;
}
add_shortcode('pub_dashboard', 'shortcode_pub_dashboard_function');