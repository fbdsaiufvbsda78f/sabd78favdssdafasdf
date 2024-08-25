<?php
get_header(); ?>

<div class="content">
    <h1>Наши сотрудники</h1>
    <div class="staff-list">
        <?php
        $staff_posts = new WP_Query(array(
            'posts_per_page' => -1,
            'post_type'      => 'staff',
        ));
        if ($staff_posts->have_posts()) :
            while ($staff_posts->have_posts()) : $staff_posts->the_post();
                ?>
                <div class="staff-item">
                    <h3><?php the_title(); ?></h3>
                    <?php if (has_post_thumbnail()) {
                        the_post_thumbnail('thumbnail');
                    } ?>
                    <div class="staff-description"><?php the_excerpt(); ?></div>
                </div>
                <?php
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </div>
</div>

<?php get_footer(); ?>
