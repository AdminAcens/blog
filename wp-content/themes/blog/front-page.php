<?php if ( is_front_page() && is_page() ) { include( get_page_template() ); return; } ?>

<?php
$et_ptemplate_settings = array();
$et_ptemplate_settings = maybe_unserialize( get_post_meta( get_the_ID(), 'et_ptemplate_settings', true ) );

$fullwidth = isset( $et_ptemplate_settings['et_fullwidthpage'] ) ? (bool) $et_ptemplate_settings['et_fullwidthpage'] : false;

$et_ptemplate_blogstyle = isset( $et_ptemplate_settings['et_ptemplate_blogstyle'] ) ? (bool) $et_ptemplate_settings['et_ptemplate_blogstyle'] : false;

$et_ptemplate_showthumb = isset( $et_ptemplate_settings['et_ptemplate_showthumb'] ) ? (bool) $et_ptemplate_settings['et_ptemplate_showthumb'] : false;

$blog_cats = isset( $et_ptemplate_settings['et_ptemplate_blogcats'] ) ? (array) array_map( 'intval', $et_ptemplate_settings['et_ptemplate_blogcats'] ) : array();
$et_ptemplate_blog_perpage = isset( $et_ptemplate_settings['et_ptemplate_blog_perpage'] ) ? (int) $et_ptemplate_settings['et_ptemplate_blog_perpage'] : 10;
?>

<?php get_header(); ?>

<?php if ( 'on' == et_get_option( 'foxy_featured', 'on' ) ) get_template_part( 'includes/featured' ); ?>

<?php get_template_part( 'includes/breadcrumbs', 'page' ); ?>

<?php
	$thumb = '';

	$width = (int) apply_filters( 'et_blog_image_width', 1280 );
	$height = (int) apply_filters( 'et_blog_image_height', 420 );

	$classtext = '';
	$titletext = get_the_title();
	$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Singleimage' );
	$thumb = $thumbnail["thumb"];

	$show_thumb = et_get_option( 'styleshop_page_thumbnails', 'false' );
?>
<?php if ( 'on' == $show_thumb && '' != $thumb ) : ?>
	<div class="post-thumbnail">
		<?php print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, $classtext ); ?>
	</div> <!-- .post-thumbnail -->
<?php endif; ?>

<div id="content" class="clearfix<?php if ( $fullwidth ) echo ' fullwidth'; ?>">
	<div id="left-area"<?php if ( 'on' == $show_thumb && '' != $thumb ) echo ' class="et_full_width_image"'; ?>>
		<div id="et_pt_blog" class="responsive clearfix">
			<?php $cat_query = '';
			if ( !empty($blog_cats) ) $cat_query = '&cat=' . implode(",", $blog_cats);
			else echo '<!-- blog category is not selected -->'; ?>
			<?php
				$et_paged = is_front_page() ? get_query_var( 'page' ) : get_query_var( 'paged' );
			?>
			<?php query_posts("posts_per_page=$et_ptemplate_blog_perpage&paged=" . $et_paged . $cat_query); ?>
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

				<div class="et_pt_blogentry clearfix">
					<h2 class="et_pt_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

					<p class="et_pt_blogmeta"><?php esc_html_e('Posted','Foxy'); ?> <?php esc_html_e('by','Foxy'); ?> <?php the_author_posts_link(); ?> <?php esc_html_e('on','Foxy'); ?> <?php the_time(get_option('foxy_date_format')) ?> <?php esc_html_e('in','Foxy'); ?> <?php the_category(', ') ?> | <?php comments_popup_link(esc_html__('0 comments','Foxy'), esc_html__('1 comment','Foxy'), '% '.esc_html__('comments','Foxy')); ?></p>

					<?php $thumb = '';
					$width = 184;
					$height = 184;
					$classtext = '';
					$titletext = get_the_title();

					$thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext);
					$thumb = $thumbnail["thumb"]; ?>

					<?php if ( $thumb <> '' && !$et_ptemplate_showthumb ) { ?>
						<div class="et_pt_thumb alignleft">
							<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, $classtext); ?>
							<a href="<?php the_permalink(); ?>"><span class="overlay"></span></a>
						</div> <!-- end .thumb -->
					<?php }; ?>

					<?php if (!$et_ptemplate_blogstyle) { ?>
						<p><?php truncate_post(550);?></p>
						<a href="<?php the_permalink(); ?>" class="readmore"><span><?php esc_html_e('read more','Foxy'); ?></span></a>
					<?php } else { ?>
						<?php
							global $more;
							$more = 0;
						?>
						<?php the_content(); ?>
					<?php } ?>
				</div> <!-- end .et_pt_blogentry -->

			<?php endwhile; ?>
				<div class="page-nav clearfix">
					<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); }
					else { ?>
						 <?php get_template_part('includes/navigation'); ?>
					<?php } ?>
				</div> <!-- end .entry -->
			<?php else : ?>
				<?php get_template_part('includes/no-results'); ?>
			<?php endif; wp_reset_query(); ?>
		</div> <!-- end #et_pt_blog -->

	</div> <!-- #left-area -->

	<?php get_sidebar(); ?>
</div> <!-- #content -->

<?php get_footer(); ?>