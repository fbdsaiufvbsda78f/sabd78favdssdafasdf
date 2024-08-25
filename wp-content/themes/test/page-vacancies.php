<?php
get_header(); ?>

<div class="content">
    <h1>Вакансии</h1>
    <div class="vacancies-list">
        <?php
        $vacancies = new WP_Query(array(
            'post_type' => 'vacancy',
            'posts_per_page' => -1,
        ));
        if ($vacancies->have_posts()) :
            while ($vacancies->have_posts()) : $vacancies->the_post();
                ?>
                <div class="vacancy-item">
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <div class="vacancy-meta">
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
        else :
            echo '<p>Вакансии не найдены.</p>';
        endif;
        ?>
    </div>
</div>

<?php get_footer(); ?>
