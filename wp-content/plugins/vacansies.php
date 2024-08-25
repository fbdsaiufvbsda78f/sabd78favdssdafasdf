<?php
/*
Plugin Name: vacansies
Description: vacansies
Version: 1.0
Author: 123
*/

if (!defined('ABSPATH')) {
    exit;
}

function jlp_register_job_post_type() {
    $labels = array(
        'name'               => 'Вакансии',
        'singular_name'      => 'Вакансия',
        'menu_name'          => 'Вакансии',
        'name_admin_bar'     => 'Вакансия',
        'add_new'            => 'Добавить новую',
        'add_new_item'       => 'Добавить новую вакансию',
        'new_item'           => 'Новая вакансия',
        'edit_item'          => 'Редактировать вакансию',
        'view_item'          => 'Просмотреть вакансию',
        'all_items'          => 'Все вакансии',
        'search_items'       => 'Найти вакансию',
        'parent_item_colon'  => 'Родительская вакансия:',
        'not_found'          => 'Вакансии не найдены.',
        'not_found_in_trash' => 'В корзине вакансий не найдено.',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'vacancy'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'editor', 'thumbnail', 'comments'),
    );

    register_post_type('vacancy', $args);
}

add_action('init', 'jlp_register_job_post_type');

function jlp_register_taxonomy() {
    $labels = array(
        'name'              => 'Категории вакансий',
        'singular_name'     => 'Категория вакансий',
        'search_items'      => 'Поиск категорий вакансий',
        'all_items'         => 'Все категории вакансий',
        'parent_item'       => 'Родительская категория вакансий',
        'parent_item_colon' => 'Родительская категория вакансий:',
        'edit_item'         => 'Редактировать категорию вакансий',
        'update_item'       => 'Обновить категорию вакансий',
        'add_new_item'      => 'Добавить новую категорию вакансий',
        'new_item_name'     => 'Новое имя категории вакансий',
        'menu_name'         => 'Категория вакансий',
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'job-category'),
    );

    register_taxonomy('job_category', array('vacancy'), $args);
}

add_action('init', 'jlp_register_taxonomy');

function jlp_add_custom_meta_boxes() {
    add_meta_box(
        'jlp_vacancy_details',
        'Детали вакансии',
        'jlp_render_vacancy_meta_box',
        'vacancy',
        'normal',
        'high'
    );
}

function jlp_render_vacancy_meta_box($post) {
    $vacancy_number = get_post_meta($post->ID, '_vacancy_number', true);
    $vacancy_salary = get_post_meta($post->ID, '_vacancy_salary', true);
    $vacancy_location = get_post_meta($post->ID, '_vacancy_location', true);

    echo '<label for="vacancy_number">Номер телефона:</label>';
    echo '<input type="text" id="vacancy_number" name="vacancy_number" value="' . esc_attr($vacancy_number) . '" size="25" /><br/><br/>';

    echo '<label for="vacancy_salary">Зарплата:</label>';
    echo '<input type="text" id="vacancy_salary" name="vacancy_salary" value="' . esc_attr($vacancy_salary) . '" size="25" /><br/><br/>';

    echo '<label for="vacancy_location">Местоположение:</label>';
    echo '<input type="text" id="vacancy_location" name="vacancy_location" value="' . esc_attr($vacancy_location) . '" size="25" />';
}

add_action('add_meta_boxes', 'jlp_add_custom_meta_boxes');

function jlp_save_vacancy_meta($post_id) {
    if (array_key_exists('vacancy_number', $_POST)) {
        update_post_meta($post_id, '_vacancy_number', sanitize_text_field($_POST['vacancy_number']));
    }

    if (array_key_exists('vacancy_salary', $_POST)) {
        update_post_meta($post_id, '_vacancy_salary', sanitize_text_field($_POST['vacancy_salary']));
    }

    if (array_key_exists('vacancy_location', $_POST)) {
        update_post_meta($post_id, '_vacancy_location', sanitize_text_field($_POST['vacancy_location']));
    }
}

add_action('save_post', 'jlp_save_vacancy_meta');

class Job_Category_Widget extends WP_Widget {
    function __construct() {
        parent::__construct(
            'job_category_widget',
            __('Список категорий вакансий', 'jlp_domain'),
            array('description' => __('Отображает список категорий вакансий', 'jlp_domain'))
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];

        $categories = get_terms(array(
            'taxonomy' => 'job_category',
            'hide_empty' => false,
        ));

        if (!empty($categories)) {
            echo '<ul>';
            foreach ($categories as $category) {
                echo '<li><a href="' . get_term_link($category) . '">' . $category->name . '</a></li>';
            }
            echo '</ul>';
        }

        echo $args['after_widget'];
    }
}

function jlp_register_widgets() {
    register_widget('Job_Category_Widget');
}

add_action('widgets_init', 'jlp_register_widgets');

class Job_List_Widget extends WP_Widget {
    function __construct() {
        parent::__construct(
            'job_list_widget',
            __('Список вакансий', 'jlp_domain'),
            array('description' => __('Отображает список вакансий', 'jlp_domain'))
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];

        $query = new WP_Query(array(
            'post_type' => 'vacancy',
            'posts_per_page' => 10,
        ));

        if ($query->have_posts()) {
            echo '<ul>';
            while ($query->have_posts()) {
                $query->the_post();
                echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
            }
            echo '</ul>';
        }

        wp_reset_postdata();

        echo $args['after_widget'];
    }
}

add_action('widgets_init', function() {
    register_widget('Job_List_Widget');
});

class Single_Job_Widget extends WP_Widget {
    function __construct() {
        parent::__construct(
            'single_job_widget',
            __('Одиночная вакансия', 'jlp_domain'),
            array('description' => __('Отображает вакансию по ID', 'jlp_domain'))
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];

        if (!empty($instance['job_id'])) {
            $job_id = $instance['job_id'];
            $post = get_post($job_id);

            if ($post && $post->post_type === 'vacancy') {
                echo '<h2>' . esc_html($post->post_title) . '</h2>';
                echo '<p>' . esc_html(get_post_meta($job_id, '_vacancy_location', true)) . '</p>';
                echo '<p>' . esc_html(get_post_meta($job_id, '_vacancy_salary', true)) . '</p>';
                echo '<p>' . esc_html(get_post_meta($job_id, '_vacancy_number', true)) . '</p>';
                echo '<div>' . esc_html($post->post_content) . '</div>';
            }
        }

        echo $args['after_widget'];
    }

    public function form($instance) {
        $job_id = !empty($instance['job_id']) ? $instance['job_id'] : '';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('job_id')); ?>">ID вакансии:</label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('job_id')); ?>" name="<?php echo esc_attr($this->get_field_name('job_id')); ?>" type="number" value="<?php echo esc_attr($job_id); ?>">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['job_id'] = (!empty($new_instance['job_id'])) ? sanitize_text_field($new_instance['job_id']) : '';

        return $instance;
    }
}

add_action('widgets_init', function() {
    register_widget('Single_Job_Widget');
});

function jlp_job_category_shortcode() {
    ob_start();
    the_widget('Job_Category_Widget');
    return ob_get_clean();
}

add_shortcode('job_categories', 'jlp_job_category_shortcode');

function jlp_job_list_shortcode() {
    ob_start();
    the_widget('Job_List_Widget');
    return ob_get_clean();
}

add_shortcode('job_list', 'jlp_job_list_shortcode');

function jlp_single_job_shortcode($atts) {
    $atts = shortcode_atts(array('id' => ''), $atts);
    ob_start();
    the_widget('Single_Job_Widget', array('job_id' => $atts['id']));
    return ob_get_clean();
}

add_shortcode('single_job', 'jlp_single_job_shortcode');
