<?php

// Pub Address Shortcode
function shortcode_claim_form_submission_message_function() {
  
  $string = '';
  
  $string .= '<div class="builder-only" style="display:none;">Form Submission Message</div>';
  
  if ($_GET['submit']) {
    if ($_GET['submit'] == 'claim') {
      $message = 'Thanks for submitting a pub claim. Sit tight - your claim is now with our reviewers and we\'ll get back to you very shortly.';
    } elseif ($_GET['submit'] == 'features') {
      $message = 'Thanks for submitting general features for this pub. Sit tight - your claim is now with our reviewers and we\'ll get back to you very shortly.';
    } elseif ($_GET['submit'] == 'food') {
      $message = 'Thanks for submitting food details for this pub. Sit tight - your claim is now with our reviewers and we\'ll get back to you very shortly.';
    } elseif ($_GET['submit'] == 'drinks') {
      $message = 'Thanks for submitting drinks details for this pub. Sit tight - your claim is now with our reviewers and we\'ll get back to you very shortly.';
    } elseif ($_GET['submit'] == 'games') {
      $message = 'Thanks for submitting game details for this pub. Sit tight - your claim is now with our reviewers and we\'ll get back to you very shortly.';
    } elseif ($_GET['submit'] == 'accommodation') {
      $message = 'Thanks for submitting accommodation details for this pub. Sit tight - your claim is now with our reviewers and we\'ll get back to you very shortly.';
    } elseif ($_GET['submit'] == 'entertainment') {
      $message = 'Thanks for submitting entertainment details for this pub. Sit tight - your claim is now with our reviewers and we\'ll get back to you very shortly.';
    } elseif ($_GET['submit'] == 'gallery') {
      $message = 'Thanks for submitting gallery images for this pub. Sit tight - your claim is now with our reviewers and we\'ll get back to you very shortly.';
    } else {
      $message = false;
    }
    $string .= '<div class="alert alert-primary">';
      $string .= $message;
    $string .= '</div>';
  }
  
  return $string;
}
add_shortcode('claim_form_submission_message', 'shortcode_claim_form_submission_message_function');