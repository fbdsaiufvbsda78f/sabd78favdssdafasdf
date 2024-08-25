<?php get_header(); ?>

<div class="content">
    <h1 id="site-traffic">Загрузка посещаемости...</h1>
    
    <div class="block">
        <h2>Последние вакансии</h2>
        <?php
        $vacancies = new WP_Query(array(
            'posts_per_page' => 15,
            'post_type'      => 'vacancy',
        ));
        if ($vacancies->have_posts()) :
            while ($vacancies->have_posts()) : $vacancies->the_post();
                ?>
                <div class="news-item">
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <?php the_excerpt(); ?>
                </div>
                <?php
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </div>
	
    <div class="block">
        <h2>Последние новости</h2>
        <?php
        $news_posts = new WP_Query(array(
            'posts_per_page' => 15,
            'post_type'      => 'news',
        ));
        if ($news_posts->have_posts()) :
            while ($news_posts->have_posts()) : $news_posts->the_post();
                ?>
                <div class="news-item">
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <?php the_excerpt(); ?>
                </div>
                <?php
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </div>

    <div class="block">
        <h2>О нас</h2>
        <?php
        $about_page = get_page_by_path('about');
        if ($about_page) :
            echo '<p>' . get_the_excerpt($about_page) . '</p>';
            echo '<a href="' . get_permalink($about_page) . '">Подробнее</a>';
        endif;
        ?>
    </div>

    <div class="block">
        <h2>Наши сотрудники</h2>
        <?php
        $staff_posts = new WP_Query(array(
            'posts_per_page' => 15,
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
                </div>
                <?php
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </div>
</div>

<?php get_footer(); ?>
