<?php get_header(); ?>

<div class="content">
    <?php
    while (have_posts()) : the_post();
        ?>
        <div class="vacancy-detail">
            <h1><?php the_title(); ?></h1>
            
            <div class="vacancy-meta">
                <span>Автор: <?php the_author(); ?></span> |
                <span>Дата: <?php the_date(); ?></span>
            </div>

            <?php if (has_post_thumbnail()) {
                the_post_thumbnail('large');
            } ?>
            
            <div class="vacancy-content">
                <?php the_content(); ?>
            </div>
            
            <div class="vacancy-extra">
                <p><strong>Номер телефона:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), '_vacancy_number', true)); ?></p>
                <p><strong>Зарплата:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), '_vacancy_salary', true)); ?></p>
                <p><strong>Местоположение:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), '_vacancy_location', true)); ?></p>
            </div>
            
            <?php comments_template(); ?>
        </div>
        <?php
    endwhile;
    ?>
</div>

<?php get_footer(); ?>
