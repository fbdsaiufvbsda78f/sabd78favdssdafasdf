<?php
/*
Plugin Name: news
Description: news
Version: 1.0
Author: 123
*/

if (!defined('ABSPATH')) {
    exit;
}

function np_register_news_post_type() {
    $labels = array(
        'name'               => 'Новости',
        'singular_name'      => 'Новость',
        'menu_name'          => 'Новости',
        'name_admin_bar'     => 'Новость',
        'add_new'            => 'Добавить новую',
        'add_new_item'       => 'Добавить новую новость',
        'new_item'           => 'Новая новость',
        'edit_item'          => 'Редактировать новость',
        'view_item'          => 'Просмотреть новость',
        'all_items'          => 'Все новости',
        'search_items'       => 'Найти новость',
        'parent_item_colon'  => 'Родительская новость:',
        'not_found'          => 'Новости не найдены.',
        'not_found_in_trash' => 'В корзине новостей не найдено.',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'news'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'editor', 'comments'),
    );

    register_post_type('news', $args);
}

add_action('init', 'np_register_news_post_type');

function np_register_news_taxonomy() {
    $labels = array(
        'name'              => 'Категории новостей',
        'singular_name'     => 'Категория новости',
        'search_items'      => 'Найти категории новостей',
        'all_items'         => 'Все категории новостей',
        'parent_item'       => 'Родительская категория новостей',
        'parent_item_colon' => 'Родительская категория новостей:',
        'edit_item'         => 'Редактировать категорию новости',
        'update_item'       => 'Обновить категорию новости',
        'add_new_item'      => 'Добавить новую категорию новости',
        'new_item_name'     => 'Новое имя категории новости',
        'menu_name'         => 'Категории новостей',
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'news-category'),
    );

    register_taxonomy('news_category', array('news'), $args);
}

add_action('init', 'np_register_news_taxonomy');

function np_add_news_meta_boxes() {
    add_meta_box(
        'np_news_meta',
        'Дополнительная информация',
        'np_render_news_meta_boxes',
        'news',
        'normal',
        'high'
    );
}

function np_render_news_meta_boxes($post) {
    $author = get_post_meta($post->ID, '_news_author', true);
    $source = get_post_meta($post->ID, '_news_source', true);
    $publish_date = get_post_meta($post->ID, '_news_publish_date', true);

    echo '<label for="news_author">Автор:</label>';
    echo '<input type="text" id="news_author" name="news_author" value="' . esc_attr($author) . '" size="25" /><br/><br/>';

    echo '<label for="news_source">Источник:</label>';
    echo '<input type="text" id="news_source" name="news_source" value="' . esc_attr($source) . '" size="25" /><br/><br/>';

    echo '<label for="news_publish_date">Дата публикации:</label>';
    echo '<input type="date" id="news_publish_date" name="news_publish_date" value="' . esc_attr($publish_date) . '" size="25" /><br/><br/>';
}

add_action('add_meta_boxes', 'np_add_news_meta_boxes');

function np_save_news_meta($post_id) {
    if (array_key_exists('news_author', $_POST)) {
        update_post_meta($post_id, '_news_author', sanitize_text_field($_POST['news_author']));
    }

    if (array_key_exists('news_source', $_POST)) {
        update_post_meta($post_id, '_news_source', sanitize_text_field($_POST['news_source']));
    }

    if (array_key_exists('news_publish_date', $_POST)) {
        update_post_meta($post_id, '_news_publish_date', sanitize_text_field($_POST['news_publish_date']));
    }
}

add_action('save_post', 'np_save_news_meta');

class News_Category_Widget extends WP_Widget {
    function __construct() {
        parent::__construct(
            'news_category_widget',
            'Список категорий новостей',
            array('description' => 'Отображает список категорий новостей')
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        echo $args['before_title'] . 'Категории новостей' . $args['after_title'];
        echo '<ul>';
        wp_list_categories(array(
            'taxonomy' => 'news_category',
            'title_li' => ''
        ));
        echo '</ul>';
        echo $args['after_widget'];
    }
}

function np_register_news_category_widget() {
    register_widget('News_Category_Widget');
}

add_action('widgets_init', 'np_register_news_category_widget');

class News_List_Widget extends WP_Widget {
    function __construct() {
        parent::__construct(
            'news_list_widget',
            'Список новостей',
            array('description' => 'Отображает список новостей')
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        echo $args['before_title'] . 'Последние новости' . $args['after_title'];
        echo '<ul>';

        $news_query = new WP_Query(array(
            'post_type' => 'news',
            'posts_per_page' => 5
        ));

        while ($news_query->have_posts()) : $news_query->the_post();
            echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
        endwhile;

        wp_reset_postdata();
        echo '</ul>';
        echo $args['after_widget'];
    }
}

function np_register_news_list_widget() {
    register_widget('News_List_Widget');
}

add_action('widgets_init', 'np_register_news_list_widget');

class Single_News_Widget extends WP_Widget {
    function __construct() {
        parent::__construct(
            'single_news_widget',
            'Отдельная новость',
            array('description' => 'Отображает новость по ID')
        );
    }

    public function widget($args, $instance) {
        $post_id = !empty($instance['post_id']) ? $instance['post_id'] : 0;
        
        if ($post_id) {
            $post = get_post($post_id);
            if ($post && $post->post_type == 'news') {
                echo $args['before_widget'];
                echo $args['before_title'] . $post->post_title . $args['after_title'];
                echo '<p>' . apply_filters('the_content', $post->post_content) . '</p>';
                echo $args['after_widget'];
            }
        }
    }

    public function form($instance) {
        $post_id = !empty($instance['post_id']) ? $instance['post_id'] : '';
        echo '<p>';
        echo '<label for="' . $this->get_field_id('post_id') . '">ID новости:</label>';
        echo '<input type="text" id="' . $this->get_field_id('post_id') . '" name="' . $this->get_field_name('post_id') . '" value="' . esc_attr($post_id) . '" size="25" />';
        echo '</p>';
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['post_id'] = (!empty($new_instance['post_id'])) ? strip_tags($new_instance['post_id']) : '';
        return $instance;
    }
}

function np_register_single_news_widget() {
    register_widget('Single_News_Widget');
}

add_action('widgets_init', 'np_register_single_news_widget');

function np_news_categories_shortcode() {
    ob_start();
    wp_list_categories(array(
        'taxonomy' => 'news_category',
        'title_li' => ''
    ));
    return ob_get_clean();
}

add_shortcode('news_categories', 'np_news_categories_shortcode');

function np_news_list_shortcode() {
    ob_start();
    $news_query = new WP_Query(array(
        'post_type' => 'news',
        'posts_per_page' => 5
    ));

    echo '<ul>';
    while ($news_query->have_posts()) : $news_query->the_post();
        echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
    endwhile;
    echo '</ul>';

    wp_reset_postdata();
    return ob_get_clean();
}

add_shortcode('news_list', 'np_news_list_shortcode');

function np_single_news_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id' => 0
    ), $atts, 'single_news');

    $post_id = $atts['id'];

    if ($post_id) {
        $post = get_post($post_id);
        if ($post && $post->post_type == 'news') {
            ob_start();
            echo '<h2>' . esc_html($post->post_title) . '</h2>';
            echo '<div>' . apply_filters('the_content', $post->post_content) . '</div>';
            return ob_get_clean();
        }
    }

    return '';
}

add_shortcode('single_news', 'np_single_news_shortcode');
