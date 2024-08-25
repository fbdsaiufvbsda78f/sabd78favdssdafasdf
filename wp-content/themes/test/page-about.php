<?php
get_header(); ?>

<div class="content">
    <h1><?php the_title(); ?></h1>
    <div class="page-content">
        <?php
        while (have_posts()) : the_post();
            the_content();
        endwhile;
        ?>
    </div>
</div>

<?php get_footer(); ?>
