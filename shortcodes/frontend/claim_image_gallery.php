<?php

// Pub Image Gallery Shortcode
function shortcode_claim_pub_gallery_function() {
  
  $string = '';
  $pub_id = get_the_ID();
  $string .= '<div class="builder-only" style="display:none;">Image Gallery</div>';
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
  
  $image_locations = array();
  $image_locations[0] = array(
    'form_id' => 6, // Form ID
    'field_key' => 145, // Field Key
    'delete_key' => 157, // Field that checks for a deletion
  );
  $image_locations[1] = array(
    'form_id' => 15, // Form ID
    'field_key' => 137, // Field Key
    'delete_key' => 155, // Field that checks for a deletion
  );
  $image_locations[2] = array(
    'form_id' => 15, // Form ID
    'field_key' => 138, // Field Key
    'delete_key' => 156, // Field that checks for a deletion
  );
  $image_locations[3] = array(
    'form_id' => 15, // Form ID
    'field_key' => 140, // Field Key
    'delete_key' => 157, // Field that checks for a deletion
  );
  $image_locations[4] = array(
    'form_id' => 15, // Form ID
    'field_key' => 139, // Field Key
    'delete_key' => 158, // Field that checks for a deletion
  );
  
  $the_query = new WP_Query( $args );

  if ( $the_query->have_posts() ) {
    while( $the_query->have_posts() ) {
      $the_query->the_post();

      // Get the user ID of this claim
      $user_id = get_field('claim_user_id');

      $gallery_array = array();
      // Get all the entries that are related to the pub

      global $wpdb;
      
      foreach($image_locations as $image_location) {
        
        $entry_meta_objects = $wpdb->get_results("SELECT entry_id FROM wp_gf_entry_meta WHERE meta_key=1 AND meta_value=" . $pub_id . " AND form_id=" . $image_location['form_id']);
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
              //break;
            } else {
              // If the workflow is not set, it may mean that this is the imported data
              // We need to look in the meta if 'is_approved' is set to 1
              
              $approved_status_object = $wpdb->get_results("SELECT entry_id FROM wp_gf_entry_meta WHERE meta_key='is_approved' AND meta_value=1 AND entry_id=" . $user_entry_id);
              
              if ($approved_status_object) {
                // This means that an imported entry has been found.
                // Push this current entry into the approved entry array & break
                array_push($approved_entry_array, $user_entry_id);
                // End the foreach loop
                //break;
              }
            }

          }
        }

        if ($approved_entry_array) {
          
          // Loop through the array of approved entries for this pub & user
          // Check if the entry has a image
          // If it does, break out of the loop, as we only want the latest uploaded image
          foreach($approved_entry_array as $approved_entry_id) {

            // We need to check if this is a 'Delete image' form
            $pub_image_delete_object = $wpdb->get_results("SELECT meta_value FROM wp_gf_entry_meta WHERE entry_id=" . $approved_entry_id . " AND meta_key='" . $image_location['delete_key'] . ".1' AND meta_value=1");
            if ($pub_image_delete_object) {
              // Get out of the foreach loop, if this entry id says delete image
              break;
            }
            
            $pub_image_object = $wpdb->get_results("SELECT meta_value FROM wp_gf_entry_meta WHERE entry_id=" . $approved_entry_id . " AND meta_key=" . $image_location['field_key']);

            if ($pub_image_object) {
              // Put the latest approved image url into the gallery array
              array_push($gallery_array, $pub_image_object[0]->meta_value);
              break;
            }

          }
        }
        
      }

    }
  }
  else {}

  wp_reset_postdata();
  
  
  
  
  
  
  // If the image gallery array is not empty, it means that there are approved pub images
  if (!empty($gallery_array)) {

    $gallery_count = count($gallery_array);
    
    $string .= '<div class="pub_gallery_container">';
    // Big image
    foreach($gallery_array as $image) {
      $string .= '<div class="mySlides">';
      //$string .= '<img src="' . $image . '" style="width:100%">';
      $string .= '<div class="ratio" style="background-image:url(' . $image . ')">';
      $string .= '<div class="content"></div>';
      $string .= '</div>';
      $string .= '</div>';
    }

    // If the image gallery has more than one image, show the arrows & thumbnail images
    if ($gallery_count > 1) {
      
      $string .= '<a class="prev" onclick="plusSlides(-1)"><i class="fas fa-chevron-left"></i></a>';
      $string .= '<a class="next" onclick="plusSlides(1)"><i class="fas fa-chevron-right"></i></a>';
      
      // Thumbnail images
      $image_count = 1;
      $string .= '<div class="gallery-row">';
      foreach($gallery_array as $image) {
        $string .= '<div class="gallery-column">';
        //$string .= '<img class="gallery-demo gallery-cursor" src="' . $image . '" style="width:100%" onclick="currentSlide(' . $image_count . ')" alt="' . get_the_title() . ' image">';
        $string .= '<div class="ratio gallery-demo gallery-cursor" style="background-image:url(' . $image . ')" onclick="currentSlide(' . $image_count . ')"><div class="content"></div></div>';
        $string .= '</div>';
        $image_count++;
      }
      $string .= '</div>';
      
    }
    
    $string .= '</div>';

    $string .= '<style>';
    $string .= '/* Position the image container (needed to position the left and right arrows) */
.pub_gallery_container {
position: relative;
margin-top:15px;
margin-bottom:15px;
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
  background-color: rgba(0, 0, 0, 0);
  transition:all 0.5s;
}
.prev i,
.next i {
color:#fff;
font-size:28px;
text-shadow: 0 2px 15px rgba(0, 0, 0, 0.8);
}
/* Position the "next button" to the right */
.next {
right: 0;
border-radius: 3px 0 0 3px;
}

/* On hover, add a black background color with a little bit see-through */
.prev:hover,
.next:hover {
  background-color: rgba(0, 0, 0, 0.2);
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
.gallery-row {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  padding-top:20px;
}
.gallery-row:after {
content: "";
display: table;
clear: both;
}

/* Six columns side by side */
.gallery-column {
  width:20%;
}
.gallery-row .gallery-column {
  margin-right: 20px;
}
.gallery-row .gallery-column:last-child {
  margin-right: 0;
}
/* Add a transparency effect for thumnbail images */
.gallery-demo {
opacity: 0.6;
}

.active,
.gallery-demo:hover {
opacity: 1;
}
.pub_gallery_container .ratio {
    position: relative;
    background-size:cover;
    background-repeat: no-repeat;
    background-position:center;
}

.pub_gallery_container .ratio > .content {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
}

.pub_gallery_container .ratio:before {
    display: block;
    content: " ";
    width: 100%;
    padding-top: 62.5%;
}
@media (max-width:991px) {
  .gallery-row {
    display:none;
  }
}
';    
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
  
  return $string;
  
}
add_shortcode('claim_pub_gallery', 'shortcode_claim_pub_gallery_function');