<?php
/**
 * The template for archive docs page
 *
 * @author Mantrabrain
 * @package Documentation/ArchiveTemplate
 */

get_header(); ?>

<?php
	// display live search box.
	echo do_shortcode( '[easy_doc_wp_live_search]' );
?>
<div class="wrap docs-archive-wraper">

	<?php

	// Display category list.
	echo do_shortcode( '[easy_doc_wp_category_list]' );

	?>

</div><!-- .wrap -->

<?php
get_footer();
