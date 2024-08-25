<?php get_header(); ?>

<div class="content">
    <?php
    while (have_posts()) : the_post();
        ?>
        <div class="news-detail">
            <h1><?php the_title(); ?></h1>
            <div class="news-meta">
                <span>Автор: <?php the_author(); ?></span> |
                <span>Дата: <?php the_date(); ?></span>
            </div>
            <?php if (has_post_thumbnail()) {
                the_post_thumbnail('large');
            } ?>
            <div class="news-content">
                <?php the_content(); ?>
            </div>
            <?php comments_template(); ?>
        </div>
        <?php
    endwhile;
    ?>
</div>

<?php get_footer(); ?>
