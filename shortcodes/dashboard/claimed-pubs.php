<?php

// Pub Address Shortcode
function shortcode_claimed_pubs_function() {
  
  $string = '';
  
  $current_user_id = strval(get_current_user_id());
  
  global $wpdb;
  // This gets the entry ids that are linked to the user & form id 6
  $entry_id_objects = $wpdb->get_results("SELECT id FROM wp_gf_entry WHERE form_id=6 AND created_by=" . $current_user_id);
  
  if ($entry_id_objects) {
    $string .= '<div class="main-search-wrapper">';
    
    $entry_id_array = array();
    
    // Loop through all the entry id objects & add the entry id value to an entry id array
    foreach($entry_id_objects as $entry_id_object) {
      
      array_push($entry_id_array, $entry_id_object->id);
      
    }
    
    // Sort the entry ids, so latest is first in the array
    rsort($entry_id_array);
    
    $post_ids_array = array();
    
    // Loop through the entry ids to get the pub id object associated to the entry id
    foreach($entry_id_array as $entry_id) {
      
      $pub_id_objects = $wpdb->get_results("SELECT meta_value FROM wp_gf_entry_meta WHERE meta_key=1 AND entry_id=" . $entry_id);
      
      if ($pub_id_objects) {
        
        // Loop through the post id objects, adding the post id value to an array
        foreach($pub_id_objects as $pub_id_object) {
          
          array_push($post_ids_array, $pub_id_object->meta_value);
          
        }
        
      }
      
    }
    // This array could have duplicates of the same pub, if a user has modified their pub record multiple times
    // We need to us array_unique to remove the duplicates
    $post_ids_array = array_unique($post_ids_array);
    
    if ($post_ids_array) {
      
      foreach($post_ids_array as $pub_id) {
        
        $chapters = get_the_terms($pub_id, 'chapter');
        $chapter = $chapters[0]->name;
        $locations = get_the_terms($pub_id, 'location');
        $location = $locations[0]->name;

        $string .= '<div class="main-search-result ' . do_shortcode('[pub_category_class_name id=' . $pub_id . ']') . '">';
          $string .= '<div class="inner">';
            $string .= '<div class="result-content">';
              $string .= '<div class="pub-maininfo">';
                $string .= '<div class="pub-badge-wrapper">';
                  $string .= do_shortcode('[pub_card_category_icon id=' . $pub_id . ']');
                $string .= '</div>';
                $string .= '<div class="pub-details">';
                  $string .= '<div class="pub-details-category">';
                    $string .= '<p>' . do_shortcode('[pub_card_category id=' . $pub_id . ']') . '</p>';
                  $string .= '</div>';
                  $string .= '<div class="pub-details-title">';
                    $string .= '<h2>' . get_the_title($pub_id) . '</h2>';
                  $string .= '</div>';
                  $string .= '<div class="pub-details-location">';
                    $string .= wpautop($location);
                  $string .= '</div>';
                  $string .= '<div class="pub-details-chapter">';
                    $string .= wpautop($chapter);
                  $string .= '</div>';
                $string .= '</div>';
              $string .= '</div>';
            $string .= '</div>';
            $string .= '<div class="result-button">';
              $string .= '<a href="' . $site_url . '/pub-dashboard?pub_id=' . $pub_id . '">Pub dashboard</a>';
              //$string .= '<span>Claim status: ' . $pub_claim_status . '</span>';
            $string .= '</div>';
          $string .= '</div>';
        $string .= '</div>';
        
      }
      
    }
    
    $string .= '</div>';
    $string .= '<style>';
    $string .= '.main-search-result {
    border: 1px solid #e7e7e7;
    border-radius: 4px;
    margin-bottom:5px;
    padding:15px 25px;
  }
  .main-search-result:last-child {
    margin-bottom:0;
  }
  .main-search-result .inner {
    display:-webkit-box;
    display:-ms-flexbox;
    display:flex;
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    justify-content: space-between;
  }
  .main-search-result .pub-maininfo {
    display:-webkit-box;
    display:-ms-flexbox;
    display:flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
  }
  .main-search-result .pub-icons .awards .awards_icons,
  .main-search-result .pub-icons .features .features_icons {
    display:-webkit-box;
    display:-ms-flexbox;
    display:flex;
    -ms-flex-wrap:wrap;
    flex-wrap:wrap;
  }
  .main-search-result .pub-icons .awards .awards_icons > *,
  .main-search-result .pub-icons .features .features_icons > * {
    padding-right:4px;
  }
  .main-search-result .pub-icons .awards .awards_icons > :last-child,
  .main-search-result .pub-icons .features .features_icons > :last-child {
    padding-right:0;
  }
  .main-search-result p,
  .main-search-result h2 {
    margin:0;
    color: #151515;
  }
  .main-search-result .pub-badge-wrapper {
    padding-right:15px;
  }
  .main-search-result .pub-badge-wrapper img {
    width: 80px;
    height: auto;
  }
  .main-search-result .pub-icons img {
    width: 36px;
    height: auto;
    padding-right: 5px;
    margin-bottom:4px;
  }
  .main-search-result .pub-icons .features .fa-stack {
    font-size:16px;
    width: 2em;
    color: #151515;
    margin-bottom:4px;
  }
  .main-search-result .result-content {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    -webkit-box-flex: 1;
    -ms-flex: 1;
    flex: 1;
  }
  .main-search-result .result-image {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    background-color: #d0d0d0;
  }
  .main-search-result .pub-details-category p {
    text-transform:uppercase;
    font-size: 12px;
    color: #c63c37;
  }
  .main-search-result.closed p,
  .main-search-result.closed h2,
  .main-search-result.archived p,
  .main-search-result.archived h2 {
    color:#d0d0d0;
  }
  .inner .result-button {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
  }
  .inner .result-button a {
    display:inline-block;
    padding:10px;
    background-color:#c63c37;
    color:#fff;
    text-transform:uppercase;
    font-weight:bold;
    border:2px solid #c63c37;
    transition:all 0.5s;
    border-radius:2px;
  }
  .inner .result-button a:hover {
    background-color:#ffffff;
    color:#c63c37;
  }
  @media (max-width:991px) {
    .main-search-result a {
      -webkit-box-orient:vertical;
      -webkit-box-direction:normal;
      -ms-flex-direction:column;
      flex-direction:column;
    }
    .main-search-result .result-image {
      -webkit-box-pack: center;
      -ms-flex-pack: center;
      justify-content: center;
    }
  }
  @media (max-width:767px) {
    .main-search-result .pub-icons .awards h4,
    .main-search-result .pub-icons .features h4 {
      margin-bottom:4px;
    }
    .main-search-result .inner {
      -webkit-box-orient: vertical;
      -webkit-box-direction: normal;
      -ms-flex-direction: column;
      flex-direction: column;
    }
    .inner .result-button {
      padding-top:15px;
    }
    .main-search-result .pub-maininfo {
      -webkit-box-orient: vertical;
      -webkit-box-direction: normal;
      -ms-flex-direction: column;
      flex-direction: column;
      -webkit-box-align: start;
      -ms-flex-align: start;
      align-items: flex-start;
    }
    .pub-details {
      padding-top:10px;
    }
  }';
    $string .= '</style>';
  } else {
    $site_url = get_site_url();
    $string .= '<h2>You do not have a claim on a pub. Please start by claiming a pub <a href="' . $site_url . '/claim-a-pub/">here</a>.</h2>';
  }
  
  return $string;
}
add_shortcode('claimed_pubs', 'shortcode_claimed_pubs_function');