<?php

// Pub Dashboard Shortcode
function shortcode_pub_dashboard_function() {
  
  $string = '';
  
  $site_url = get_site_url();
  $url_pub_id = $_GET['pub_id'];
  $user_id = get_current_user_id();
  
  // If a pub id is given in the url...
  if ($url_pub_id) {
    
    $chapters = get_the_terms($url_pub_id, 'chapter');
    $chapter = $chapters[0]->name;
    $locations = get_the_terms($url_pub_id, 'location');
    $location = $locations[0]->name;
    $string .= '<div class="main-search-wrapper">';
      $string .= '<div class="main-search-result ' . do_shortcode('[pub_category_class_name id=' . $url_pub_id . ']') . '">';
        $string .= '<div class="inner">';
          $string .= '<div class="result-content">';
            $string .= '<div class="pub-maininfo">';
              $string .= '<div class="pub-badge-wrapper">';
                $string .= do_shortcode('[pub_card_category_icon id=' . $url_pub_id . ']');
              $string .= '</div>';
              $string .= '<div class="pub-details">';
                $string .= '<div class="pub-details-category">';
                  $string .= '<p>' . do_shortcode('[pub_card_category id=' . $url_pub_id . ']') . '</p>';
                $string .= '</div>';
                $string .= '<div class="pub-details-title">';
                  $string .= '<h2>' . get_the_title($url_pub_id) . '</h2>';
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
          $string .= '</div>';
        $string .= '</div>';
      $string .= '</div>';
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
    display:flex;
    justify-content: space-between;
  }
  .main-search-result .pub-maininfo {
    display:flex;
    align-items: center;
  }
  .main-search-result .pub-icons .awards .awards_icons,
  .main-search-result .pub-icons .features .features_icons {
    display:flex;
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
    display: flex;
    flex-direction: column;
    justify-content: center;
    flex: 1;
  }
  .main-search-result .result-image {
    display: flex;
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
    display: flex;
    align-items: center;
  }
  @media (max-width:991px) {
    .main-search-result a {
      flex-direction:column;
    }
    .main-search-result .result-image {
      justify-content: center;
    }
  }
  @media (max-width:767px) {
    .main-search-result .pub-icons .awards h4,
    .main-search-result .pub-icons .features h4 {
      margin-bottom:4px;
    }
    .main-search-result .inner {
      flex-direction: column;
    }
    .inner .result-button {
      /*padding-top:15px;*/
    }
    .main-search-result .pub-maininfo {
      flex-direction: column;
      align-items: flex-start;
    }
    .pub-details {
      padding-top:10px;
    }
  }';
    $string .= '</style>';
    
  }
  
  return $string;
}
add_shortcode('pub_dashboard', 'shortcode_pub_dashboard_function');