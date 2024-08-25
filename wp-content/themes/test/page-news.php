<?php
get_header(); ?>

<div class="content">
    <h1>Новости</h1>
    <div class="news-list">
        <?php
        $news_posts = new WP_Query(array(
            'post_type' => 'post',
            'category_name' => 'news',
        ));
        if ($news_posts->have_posts()) :
            while ($news_posts->have_posts()) : $news_posts->the_post();
                ?>
                <div class="news-item">
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <div class="news-meta">
                        <span>Автор: <?php the_author(); ?></span> |
                        <span>Дата: <?php the_date(); ?></span>
                    </div>
                    <?php if (has_post_thumbnail()) {
                        the_post_thumbnail('medium');
                    } ?>
                    <?php the_excerpt(); ?>
                </div>
                <?php
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </div>
</div>

<?php get_footer(); ?>
