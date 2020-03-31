<?php
/**
*Plugin Name: Custom list posts_types 
*Plugin URI: https://labarta.es/
*Description: Elimina autor y comentarios, añade imagen y fecha revisión en listado páginas y entradas de WordPress.
*Version: 1.1
*Author: Labarta
*Author URI: https://labarta.es/
*License: GPL2
**/

defined( 'ABSPATH' ) or die( '¡Sin trampas!' );

/* Enqueue admin styles */

function jml_custom_admin_styles() {
    wp_enqueue_style('custom-styles', plugins_url('/css/styles.css', __FILE__ ));
	}
add_action('admin_enqueue_scripts', 'jml_custom_admin_styles');

/* Remove columns to pages list */

function jml_custom_pages_columns( $columns ) {
//	unset( $columns['cb'] );
//	unset( $columns['title'] );
//	unset( $columns['date'] );	
	unset( $columns['author'] );
	unset( $columns['comments'] );
//	unset( $columns['Modified'] );	
	return $columns;
}
add_filter( 'manage_pages_columns', 'jml_custom_pages_columns' );


/* Remove columns to posts list */ 

function jml_remove_columns( $columns ) {	
//	unset( $columns['cb'] );
//	unset( $columns['title'] );
//	unset( $columns['categories'] );
//	unset( $columns['tags'] );
//	unset( $columns['date'] );
	unset( $columns['comments'] );
	unset( $columns['author'] );
//	unset( $columns['new_posts_thumb'] );
//	unset( $columns['Modified'] );	
	return $columns;
}
add_filter ( 'manage_edit-post_columns', 'jml_remove_columns' );



/* Add featured image to post list*/

add_filter( 'manage_posts_columns', 'jmlc_custom_posts_columns' );
function jmlc_custom_posts_columns( $columns ) {
 
  $columns['new_posts_thumb'] = __('Featured Image');
  return $columns;
}
 
add_action( 'manage_posts_custom_column', 'jmlc_custom_posts_column_content', 10, 2 );
 
function jmlc_custom_posts_column_content( $column_name, $post_id ) {
  switch($column_name){
    case 'new_posts_thumb':
    $post_thumbnail_id = get_post_thumbnail_id($post_id);
    if ($post_thumbnail_id) {
      $post_thumbnail_img = wp_get_attachment_image_src( $post_thumbnail_id, 'thumbnail' );
      echo '<img width="60" src="' . $post_thumbnail_img[0] . '" />';
    }
     break;
  }
}


/* Register Modified Date Column for both posts & pages */

function jml_modified_column_register( $columns ) {
	$columns['Modified'] = __( 'Modificada', 'show_modified_date_in_admin_lists' );
	return $columns;
}
add_filter( 'manage_posts_columns', 'jml_modified_column_register' );
add_filter( 'manage_pages_columns', 'jml_modified_column_register' );

function jml_modified_column_display( $column_name, $post_id ) {
	switch ( $column_name ) {
	case 'Modified':
		global $post; 
	       	echo '<p class="mod-date">';
	       	echo '<em>'.get_the_modified_date().'</em><br />';
			echo '</p>';
		break; // end all case breaks
	}
}
add_action( 'manage_posts_custom_column', 'jml_modified_column_display', 10, 2 );
add_action( 'manage_pages_custom_column', 'jml_modified_column_display', 10, 2 );

function jml_modified_column_register_sortable( $columns ) {
	$columns['Modified'] = 'modified';
	return $columns;
}
add_filter( 'manage_edit-post_sortable_columns', 'jml_modified_column_register_sortable' );
add_filter( 'manage_edit-page_sortable_columns', 'jml_modified_column_register_sortable' );