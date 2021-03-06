<?php

add_action( 'the_content', 'memberful_wp_protect_content', -10 );

function memberful_wp_protect_content( $content ) {
  global $post;

  if ( current_user_can( 'publish_posts' ) ) {
    return $content;
  }

  if ( ! memberful_can_user_access_post( wp_get_current_user()->ID, $post->ID ) ) {
    // Disable Beaver Builder
    remove_action( "the_content", "FLBuilder::render_content" );

    // Remove Elementor action hook
    if (get_queried_object_id() === $post->ID) {
      remove_action("elementor/frontend/the_content", "memberful_wp_protect_content");
    }

    // Remove media enclosures from the RSS feed
    add_filter("rss_enclosure", "__return_empty_string");

    $memberful_marketing_content = memberful_marketing_content( $post->ID );
    return apply_filters( 'memberful_wp_protect_content', $memberful_marketing_content );
  }

  return $content;
}
