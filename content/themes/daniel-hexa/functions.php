<?php

function daniel_hexa_titleless_formats() {
	return array(
		'aside',
		'status',
		'link',
		'quote',
		);
}

/**
 * Get our format to category conversions
 */
function daniel_hexa_get_formats_to_categories() {
	return array(
			'aside'         => 'asides',
			'gallery'       => 'galleries',
			'image'         => 'photos',
			'quote'         => 'quotes',
			'status'        => 'statuses',
			'video'         => 'videos',
		);
}

/**
 * Categories are old skool
 */
add_action( 'add_meta_boxes', 'daniel_hexa_remove_category_meta_box', 100 );
function daniel_hexa_remove_category_meta_box() {
	remove_meta_box( 'categorydiv', 'post', 'side' );
}
/**
 * ... but we still need them for things not to break :(
 */
add_action( 'save_post', 'daniel_hexa_assign_category_from_post_format' );
function daniel_hexa_assign_category_from_post_format( $post_id ) {

	if ( 'post' != get_post_type( $post_id ) )
		return;

	$post_format = get_post_format( $post_id );

	$formats_to_categories = daniel_hexa_get_formats_to_categories();
	if ( isset( $formats_to_categories[$post_format] ) )
		$category = $formats_to_categories[$post_format];
	else
		$category = 'posts';

	wp_set_object_terms( $post_id, array( $category ), 'category' );
}
/**
 * ... and we should redirect categories to their corresponding type pages
 */
add_action( 'init', 'daniel_hexa_redirect_categories_to_formats' );
function daniel_hexa_redirect_categories_to_formats() {

	$formats_to_categories = daniel_hexa_get_formats_to_categories();

	if ( false !== stripos( $_SERVER['REQUEST_URI'], '/category/' ) ) {
		$category = trim( str_replace( '/category/', '', $_SERVER['REQUEST_URI'] ), '/' );
		if ( false !== ( $index = array_search( $category, $formats_to_categories ) ) ) {
			$redirect_to = get_post_format_link( $index ); 
			wp_safe_redirect( $redirect_to, 301 );
			exit;
		}
	}

}


/**
 * Tin-foil hat: off
 */
add_action( 'wp_footer', 'daniel_hexa_wp_footer_google_analytics' );
function daniel_hexa_wp_footer_google_analytics () {
	// Don't log me!
	if ( is_user_logged_in() )
		return;
	?>
<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	ga('create', 'UA-40493114-1', 'danielbachhuber.com');
	ga('send', 'pageview');
</script>
<?php
}

/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function hexa_posted_on() {
	if ( is_sticky() && ! is_single() ) {
		printf( __( '<span class="post-date"><a href="%1$s" title="%2$s" rel="bookmark">Featured</a></span><span class="byline"><span class="author vcard"><a class="url fn n" href="%3$s" title="%4$s" rel="author">%5$s</a></span></span>', 'hexa' ),
			esc_url( get_permalink() ),
			esc_attr( get_the_time() ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( __( 'View all posts by %s', 'hexa' ), get_the_author() ) ),
			esc_html( get_the_author() )
		);
	}
	else {
		printf( __( '<span class="post-date"><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span>', 'hexa' ),
			esc_url( get_permalink() ),
			esc_attr( get_the_time() ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() )
		);
	}
}