<?php
/**
 * The template for displaying search results pages.
 *
 * @package Chow
 */

get_header(); ?>
<!-- Content
================================================== -->
<!-- Titlebar
================================================== -->
<section id="titlebar" class="browse-all">
	<!-- Container -->
	<div class="container">
		<div class="eight columns">
			<h2><?php _e('Browse Recipes','chow') ?></h2>
		</div>
	</div>
	<!-- Container / End -->
</section>

<?php get_template_part( 'searchformadv' ); ?>

<div class="margin-top-35"></div>

<div class="container">
    <div class="full-grid">
        <div class="sixteen columns search-title">
           <h3 class="headline"><?php _e('Search Results','chow') ?></h3>
           <span class="line  margin-bottom-35"></span>
           <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>

        
        <?php if ( have_posts() ) : ?>
            <div class="isotope">
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php
                        /* Include the Post-Format-specific template for the content.
                         * If you want to override this in a child theme, then include a file
                         * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                         */
                        get_template_part( 'grid/content', get_post_format() );
                    ?>

                <?php endwhile; ?>
            </div>
            <div class="clearfix"></div>

            <div class="pagination-container masonry">
                <?php if(function_exists('wp_pagenavi')) { ?>
                    <nav class="pagination wp-pagenavi">
                        <?php wp_pagenavi(); ?>
                    </nav>
                    <?php
                } else {
                    if ( get_next_posts_link() ||  get_previous_posts_link() ) : ?>
                    <nav class="pagination-next-prev ">
                        <ul>
                            <?php if ( get_previous_posts_link() ) : ?>
                            <li id="next"><?php previous_posts_link(''); ?></li>
                            <?php endif;
                                if ( get_next_posts_link() ) : ?>
                            <li id="prev"><?php next_posts_link(''); ?></li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif;
                } ?>
            </div>

        <?php else : ?>

            <div class="sixteen columns search-no-result-info">
                <i class="fa fa-frown-o no-found-icon"></i>
                <header class="page-header">
                    <h1 class="page-title"><?php _e( 'Nothing Found', 'chow' ); ?></h1>
                </header>
                <div class="page-content">
                    <p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords or settings.', 'chow' ); ?></p>
                   
                    <div class="margin-bottom-35"></div>
                </div>
            </div>

    <?php endif; ?>

    </div>


<?php get_footer(); ?>
