<?php

// Pub Image Gallery Shortcode
function shortcode_pub_image_gallery_function() {
  
  $string = '';
  $id = get_the_ID();
  global $wpdb;
  $db_entryid = $wpdb->get_results("SELECT entry_id FROM wp_gf_entry_meta WHERE form_id=2 AND meta_value=" . $id);
  
  if ($db_entryid) {
    // Get the first entry ID to get the image
    $entry_id = $db_entryid[0]->entry_id;
    $db_image_gallery = $wpdb->get_results("SELECT meta_value FROM wp_gf_entry_meta WHERE entry_id=" . $entry_id . " AND meta_key=4");
    
    if ($db_image_gallery) {
      $gallery_string = $db_image_gallery[0]->meta_value;
      
      if ($gallery_string) {
        
        $gallery_array = explode(',', $gallery_string);
        //var_dump($gallery_array);
        $string .= '<div class="pub_gallery_container">';
        foreach($gallery_array as $gallery_image) {
          $gallery_image = str_replace("[","",$gallery_image);
          $gallery_image = str_replace("]","",$gallery_image);
          $gallery_image = str_replace("\/","/",$gallery_image);
          $gallery_image = str_replace('"','',$gallery_image);
          $string .= '<div class="mySlides">';
          $string .= '<img src="' . $gallery_image . '" style="width:100%;" />';
          $string .= '</div>';
        }
        
        $string .= '<a class="prev" onclick="plusSlides(-1)">&#10094;</a>';
        $string .= '<a class="next" onclick="plusSlides(1)">&#10095;</a>';
        
        
        $string .= '<div class="row">';
        $gallery_count = 1;
        foreach($gallery_array as $gallery_image) {
          $gallery_image = str_replace("[","",$gallery_image);
          $gallery_image = str_replace("]","",$gallery_image);
          $gallery_image = str_replace("\/","/",$gallery_image);
          $gallery_image = str_replace('"','',$gallery_image);
          $string .= '<div class="column">';
          $string .= '<img class="demo cursor" src="' . $gallery_image . '" style="width:100%" onclick="currentSlide(' . $gallery_count . ')">';
          $string .= '</div>';
          $gallery_count++;
        }
        
        $string .= '</div>';
        
        $string .= '</div>';
        
        ?>

<style>

/* Position the image container (needed to position the left and right arrows) */
.pub_gallery_container {
  position: relative;
}

/* Hide the images by default */
.pub_gallery_container .mySlides {
  display: none;
}

/* Add a pointer when hovering over the thumbnail images */
.pub_gallery_container .cursor {
  cursor: pointer;
}

/* Next & previous buttons */
.pub_gallery_container .prev,
.pub_gallery_container .next {
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
.pub_gallery_container .next {
  right: 0;
  border-radius: 3px 0 0 3px;
}

/* On hover, add a black background color with a little bit see-through */
.pub_gallery_container .prev:hover,
.pub_gallery_container .next:hover {
  background-color: rgba(0, 0, 0, 0.8);
}

/* Number text (1/3 etc) */
.pub_gallery_container .numbertext {
  color: #f2f2f2;
  font-size: 12px;
  padding: 8px 12px;
  position: absolute;
  top: 0;
}

/* Container for image text */
.pub_gallery_container .caption-container {
  text-align: center;
  background-color: #222;
  padding: 2px 16px;
  color: white;
}

.pub_gallery_container .row:after {
  content: "";
  display: table;
  clear: both;
}

/* Six columns side by side */
.pub_gallery_container .column {
  float: left;
  width: 16.66%;
}

/* Add a transparency effect for thumnbail images */
.pub_gallery_container .demo {
  opacity: 0.6;
}

.pub_gallery_container .active,
.pub_gallery_container .demo:hover {
  opacity: 1;
}
</style>


<script>
var slideIndex = 1;
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
  var dots = document.getElementsByClassName("demo");
  var captionText = document.getElementById("caption");
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
  captionText.innerHTML = dots[slideIndex-1].alt;
}
</script>

<?php
        
      }
      
    } else {
      $image_url = false;
    }
    
  } else {
    $image_url = false;
  }
  
  return $string;
  
}
add_shortcode('pub_image_gallery', 'shortcode_pub_image_gallery_function');