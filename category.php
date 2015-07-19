<?php
/**
 * The template for displaying Category pages
 *
 * Used to display archive-type pages for posts in a category.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

	<section id="primary" class="site-content">
		<div id="content" role="main">

		<?php if ( have_posts() ) : ?>
			<header class="archive-header">
				<h1 class="archive-title"><?php printf( __( '%s', 'twentytwelve' ), '<span>' . single_cat_title( '', false ) . '</span>' ); ?></h1>

			<?php if ( category_description() ) : // Show an optional category description ?>
				<div class="archive-meta"><?php echo category_description(); ?>
			<?php endif; ?>

			<?php $my_query = new WP_Query( 'cat=' . $cat . '&posts_per_page=-1' );?>
			      		<div class="archive-dropdown-container">
					     <select name="archive-dropdown" onchange='document.location.href=value;'>
					<?php while ( $my_query->have_posts() ) : $my_query->the_post(); ?>
					      	     <option value="<?php echo get_the_permalink();?>"><?php echo get_the_title(); ?></option>
					<?php endwhile; ?>	   
					      </select>
					 </div><!-- .archive-dropdown-container -->
				</div><!-- .archive-meta -->
			</header><!-- .archive-header -->

			<?php /* rewind_posts(); */?>

			<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();

				/* Include the post format-specific template for the content. If you want to
				 * this in a child theme then include a file called called content-___.php
				 * (where ___ is the post format) and that will be used instead.
				 */
				get_template_part( 'content', get_post_format() );

			endwhile;

			twentytwelve_content_nav( 'nav-below' );
			?>

		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>

		</div><!-- #content -->
	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>