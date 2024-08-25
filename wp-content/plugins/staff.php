<?php
/*
Plugin Name: staff
Description: staff
Version: 1.0
Author: 123
*/

if (!defined('ABSPATH')) {
    exit;
}

function sp_register_staff_post_type() {
    $labels = array(
        'name'               => 'Сотрудники',
        'singular_name'      => 'Сотрудник',
        'menu_name'          => 'Сотрудники',
        'name_admin_bar'     => 'Сотрудник',
        'add_new'            => 'Добавить нового',
        'add_new_item'       => 'Добавить нового сотрудника',
        'new_item'           => 'Новый сотрудник',
        'edit_item'          => 'Редактировать сотрудника',
        'view_item'          => 'Просмотреть сотрудника',
        'all_items'          => 'Все сотрудники',
        'search_items'       => 'Найти сотрудника',
        'parent_item_colon'  => 'Родительский сотрудник:',
        'not_found'          => 'Сотрудники не найдены.',
        'not_found_in_trash' => 'В корзине сотрудников не найдено.',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'staff'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'editor', 'thumbnail'),
    );

    register_post_type('staff', $args);
}

add_action('init', 'sp_register_staff_post_type');

function sp_register_taxonomy() {
    $labels = array(
        'name'              => 'Структурные подразделения',
        'singular_name'     => 'Структурное подразделение',
        'search_items'      => 'Поиск подразделений',
        'all_items'         => 'Все подразделения',
        'parent_item'       => 'Родительское подразделение',
        'parent_item_colon' => 'Родительское подразделение:',
        'edit_item'         => 'Редактировать подразделение',
        'update_item'       => 'Обновить подразделение',
        'add_new_item'      => 'Добавить новое подразделение',
        'new_item_name'     => 'Новое название подразделения',
        'menu_name'         => 'Структурные подразделения',
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'department'),
    );

    register_taxonomy('department', array('staff'), $args);
}

add_action('init', 'sp_register_taxonomy');
