<?php

// Pub Image Gallery Shortcode
function shortcode_claim_pub_gallery_function() {
  
  $string = '';
  $pub_id = get_the_ID();
  $claim_form_id = '6';
  $gallery_form_id = '15';
  
  global $wpdb;
  // We first need to get the image that was set as a featured image in the original claim form
  // Ge the entry IDs for all entries from the Form ID 6 that are associated to the specific pub ID
  $pub_entries = $wpdb->get_results("
  SELECT entry_id 
  FROM wp_gf_entry_meta 
  WHERE form_id=" . $claim_form_id . " AND meta_key=1 AND meta_value=" . $pub_id);
  
  if ($pub_entries) {
    // For each form entry associated to this pub, grab all the data associated to it in the Gravity Forms meta table
    foreach($pub_entries as $pub_entry) {
      
      // For each form entry associated to this pub, grab the entry id associated to it in the Gravity Forms meta table
      $pub_entry_id = $pub_entry->entry_id;
      // Now get any data from the table which matches the entry ID
      $entry_data = $wpdb->get_results("
      SELECT * 
      FROM wp_gf_entry_meta 
      WHERE entry_id=" . $pub_entry_id);
      
      if ($entry_data) {
        $approved = 0;
        foreach($entry_data as $entry) {
          if ($entry->meta_key == 'is_approved' && $entry->meta_value == '1') {
            $approved++;
          } else {}
        }
        //If $approved is equal to or greater than 1, the is_approved meta key for this entry is set to 1, which means the entry has been approved
        if ($approved >= 1) {
          $image_gallery = array();
          foreach($entry_data as $entry) {
            if ($entry->meta_key == '145') {
              array_push($image_gallery, $entry->meta_value);
            }
          }
        }
      }
      
    }
  }
  // Now we will try to get any gallery images from the gallery form
  // Get the entry IDs for all entries from Form ID 15 that are associated to the specific pub ID
  $pub_entries = $wpdb->get_results("
  SELECT entry_id 
  FROM wp_gf_entry_meta 
  WHERE form_id=" . $gallery_form_id . " AND meta_key=1 AND meta_value=" . $pub_id);
  
  if ($pub_entries) {
    // For each form entry associated to this pub, grab the entry id associated to it in the Gravity Forms meta table
    foreach($pub_entries as $pub_entry) {
      
      $pub_entry_id = $pub_entry->entry_id;
      
      $entry_data = $wpdb->get_results("
      SELECT * 
      FROM wp_gf_entry_meta 
      WHERE entry_id=" . $pub_entry_id);
      
      if ($entry_data) {
        // Now we loop through the data in this entry & check that the is_approved meta_key is set to 1
        $approved = 0;
        foreach($entry_data as $entry) {
          if ($entry->meta_key == 'is_approved' && $entry->meta_value == '1') {
            $approved++;
          } else {}
        }
        //If $approved is equal to or greater than 1, the is_approved meta key for this entry is set to 1, which means the entry has been approved
        if ($approved >= 1) {
          if (!$image_gallery) {
            $image_gallery = array();
          }
          // Loop through the fields that have images & add them to the image array
          // The form field id that could have an image are 137, 138, 140 & 139
          foreach($entry_data as $entry) {
            if ($entry->meta_key == '137' ||
              $entry->meta_key == '138' ||
              $entry->meta_key == '140' ||
              $entry->meta_key == '139') {
              array_push($image_gallery, $entry->meta_value);
            }
          }
          
        }
      }
      
    }
  }
  
  // If the image gallery array is not empty, it means that there are approved pub images
  if (!empty($image_gallery)) {

    $string .= '<div class="pub_gallery_container">';

    // Big image
    foreach($image_gallery as $image) {
      $string .= '<div class="mySlides">';
      $string .= '<img src="' . $image . '" style="width:100%">';
      $string .= '</div>';
    }

    $string .= '<a class="prev" onclick="plusSlides(-1)">&#10094;</a>';
    $string .= '<a class="next" onclick="plusSlides(1)">&#10095;</a>';

    //$string .= '<div class="caption-container">';
    //$string .= '<p id="caption"></p>';
    //$string .= '</div>';

    // Thumbnail images
    $image_count = 1;
    $string .= '<div class="gallery-row">';
    foreach($image_gallery as $image) {
      $string .= '<div class="gallery-column">';
      $string .= '<img class="gallery-demo gallery-cursor" src="' . $image . '" style="width:100%" onclick="currentSlide(' . $image_count . ')" alt="' . get_the_title() . ' image">';
      $string .= '</div>';
      $image_count++;
    }
    $string .= '</div>';
    $string .= '</div>';

    $string .= '<style>';
    $string .= '/* Position the image container (needed to position the left and right arrows) */
.pub_gallery_container {
position: relative;
}

/* Hide the images by default */
.mySlides {
display: none;
}

/* Add a pointer when hovering over the thumbnail images */
.gallery-cursor {
cursor: pointer;
}

/* Next & previous buttons */
.prev,
.next {
cursor: pointer;
position: absolute;
top: 40%;
width: auto;
padding: 16px;
margin-top: -50px;
color: white;
font-weight: bold;
font-size: 20px;
border-radius: 0 3px 3px 0;
user-select: none;
-webkit-user-select: none;
}

/* Position the "next button" to the right */
.next {
right: 0;
border-radius: 3px 0 0 3px;
}

/* On hover, add a black background color with a little bit see-through */
.prev:hover,
.next:hover {
background-color: rgba(0, 0, 0, 0.8);
}

/* Number text (1/3 etc) */
.numbertext {
color: #f2f2f2;
font-size: 12px;
padding: 8px 12px;
position: absolute;
top: 0;
}

/* Container for image text 
.caption-container {
text-align: center;
background-color: #222;
padding: 2px 16px;
color: white;
}*/

.gallery-row:after {
content: "";
display: table;
clear: both;
}

/* Six columns side by side */
.gallery-column {
float: left;
width: 16.66%;
}

/* Add a transparency effect for thumnbail images */
.gallery-demo {
opacity: 0.6;
}

.active,
.gallery-demo:hover {
opacity: 1;
}';
    $string .= '</style>';

    $string .= '<script>';
    $string .= 'var slideIndex = 1;
showSlides(slideIndex);

// Next/previous controls
function plusSlides(n) {
showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
showSlides(slideIndex = n);
}

function showSlides(n) {
var i;
var slides = document.getElementsByClassName("mySlides");
var dots = document.getElementsByClassName("gallery-demo");
//var captionText = document.getElementById("caption");
if (n > slides.length) {slideIndex = 1}
if (n < 1) {slideIndex = slides.length}
for (i = 0; i < slides.length; i++) {
slides[i].style.display = "none";
}
for (i = 0; i < dots.length; i++) {
dots[i].className = dots[i].className.replace(" active", "");
}
slides[slideIndex-1].style.display = "block";
dots[slideIndex-1].className += " active";
//captionText.innerHTML = dots[slideIndex-1].alt;
}';
    $string .= '</script>';

  }
  
  //return $string;
  
}
add_shortcode('claim_pub_gallery', 'shortcode_claim_pub_gallery_function');