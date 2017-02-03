<!-- Masonry -->
<?php
$layout = ot_get_option('pp_recipes_layout');
if($layout == 'left-sidebar-grid' ) {
    echo '<div class="twelve columns left-sidebar-class">';
} else if ($layout == 'right-sidebar-grid') {
    echo '<div class="twelve columns">';
} else if ($layout == 'masonry') {
    echo '<div class="full-grid">';
} else {
    echo '<div class="twelve columns">';
} ?>

<?php if( $layout == 'masonry'){ echo '<div class="sixteen columns">'; } ?>
    <!-- Headline -->
    <?php if( is_archive() || is_tag() || is_tax() || is_category()){ } else { ?> 
        <h3 class="headline"><?php if( is_home() || is_front_page() ) { echo ot_get_option('pp_recipes_title','Latest Recipes'); } else { the_archive_title(); } ?></h3>
        <span class="line rb margin-bottom-35"></span>
        <div class="clearfix"></div>
    <?php } ?>
    <?php the_archive_description( '<div class="margin-bottom-20">', '</div>') ?>
<?php if($layout == 'masonry'){
    echo '</div><div class="clearfix"></div>';
} ?>
    <!-- Isotope -->

    <?php if ( have_posts() ) : ?>

    <?php /* Start the Loop */ ?>
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
                <nav class="pagination">
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

        <?php get_template_part( 'content', 'none' ); ?>

    <?php endif; ?>

    </div><!-- #Masonry -->