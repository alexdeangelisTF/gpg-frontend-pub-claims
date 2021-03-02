<?php

function gpg_register_cpt_claim() {

	/**
	 * Register new post type: Claim.
	 */

	$labels = [
		"name" => __( "Claims", "gpg-frontend-pub-claims" ),
		"singular_name" => __( "Claim", "gpg-frontend-pub-claims" ),
		"menu_name" => __( "Claims", "gpg-frontend-pub-claims" ),
		"all_items" => __( "All Claims", "gpg-frontend-pub-claims" ),
		"add_new" => __( "Add New", "gpg-frontend-pub-claims" ),
		"add_new_item" => __( "Add new Claim", "gpg-frontend-pub-claims" ),
		"edit_item" => __( "Edit Claim", "gpg-frontend-pub-claims" ),
		"new_item" => __( "New Claim", "gpg-frontend-pub-claims" ),
		"view_item" => __( "View Claim", "gpg-frontend-pub-claims" ),
		"view_items" => __( "View Claims", "gpg-frontend-pub-claims" ),
		"search_items" => __( "Search Claims", "gpg-frontend-pub-claims" ),
		"not_found" => __( "No Claims found", "gpg-frontend-pub-claims" ),
		"not_found_in_trash" => __( "No Claims found in trash", "gpg-frontend-pub-claims" ),
		"parent" => __( "Parent Claim:", "gpg-frontend-pub-claims" ),
		"featured_image" => __( "Featured image for this Claim", "gpg-frontend-pub-claims" ),
		"set_featured_image" => __( "Set featured image for this Claim", "gpg-frontend-pub-claims" ),
		"remove_featured_image" => __( "Remove featured image for this Claim", "gpg-frontend-pub-claims" ),
		"use_featured_image" => __( "Use as featured image for this Claim", "gpg-frontend-pub-claims" ),
		"archives" => __( "Claim archives", "gpg-frontend-pub-claims" ),
		"insert_into_item" => __( "Insert into Claim", "gpg-frontend-pub-claims" ),
		"uploaded_to_this_item" => __( "Upload to this Claim", "gpg-frontend-pub-claims" ),
		"filter_items_list" => __( "Filter Claims list", "gpg-frontend-pub-claims" ),
		"items_list_navigation" => __( "Claims list navigation", "gpg-frontend-pub-claims" ),
		"items_list" => __( "Claims list", "gpg-frontend-pub-claims" ),
		"attributes" => __( "Claims attributes", "gpg-frontend-pub-claims" ),
		"name_admin_bar" => __( "Claim", "gpg-frontend-pub-claims" ),
		"item_published" => __( "Claim published", "gpg-frontend-pub-claims" ),
		"item_published_privately" => __( "Claim published privately.", "gpg-frontend-pub-claims" ),
		"item_reverted_to_draft" => __( "Claim reverted to draft.", "gpg-frontend-pub-claims" ),
		"item_scheduled" => __( "Claim scheduled", "gpg-frontend-pub-claims" ),
		"item_updated" => __( "Claim updated.", "gpg-frontend-pub-claims" ),
		"parent_item_colon" => __( "Parent Claim:", "gpg-frontend-pub-claims" ),
	];

	$args = [
		"label" => __( "Claims", "gpg-frontend-pub-claims" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => false,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => [ "slug" => "claim", "with_front" => true ],
		"query_var" => true,
		"menu_icon" => "dashicons-flag",
		"supports" => [ "title" ],
	];

	register_post_type( "claim", $args );
}

add_action( 'init', 'gpg_register_cpt_claim' );


// Add extra column to Claims CPT for postcode
add_filter('manage_claim_posts_columns', function($columns) {
	// We remove the date column & then add it back after postcode has been added
	$taken_out = $columns['date'];
	unset($columns['date']);
	$columns = array_merge($columns, ['postcode' => __('Postcode', 'textdomain')]);
	$columns['date'] = $taken_out;
	return $columns;
});
 
add_action('manage_claim_posts_custom_column', function($column_key, $post_id) {
	if ($column_key == 'postcode') {
		$pub_id = get_post_meta($post_id, 'claim_pub_id', true);
		if ($pub_id) {
			$postcode = get_post_meta($pub_id, 'pub_postcode', true);
			echo $postcode;
		}
	}
}, 10, 2);