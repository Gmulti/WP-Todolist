<?php
// wp-todolist/src/Todolist/WordPress/PostType/Todo.php

namespace Todolist\WordPress\PostType;

use Todolist\Models\HooksInterface;

use Todolist\WordPress\Helpers\PostType;

/**
 * Todo
 *
 * @author Thomas DENEULIN
 * @version 1.0.0
 * @since 1.0.0
 */
class Todo implements HooksInterface{


    /**
     * @see Todolist\Models\HooksInterface
     */
    public function hooks(){

        add_action( "init", array($this, 'initPostType') );

    }



    /**
     * @filter todolist_rewrite_cpt_todo
     * @filter todolist_register_ PostType::CPT_TODO _post_type
     * @see Todolist\WordPress\Helpers\PostType
     */
    public function initPostType(){

        $labels = array(
            'name'               => __('Todos', 'td'),
            'singular_name'      => __('Todos', 'td'),
            'menu_name'          => __('Todos', 'td'),
            'name_admin_bar'     => __('Todos','td'),
            'view'               => __('View todo', 'td'),
            'all_items'          => __('All todos', 'td'),
            'search_items'       => __('Search todos', 'td'),
            'not_found'          => __('Todo not found', 'td'),
            'not_found_in_trash' => __('Todo not found', 'td')
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => apply_filters("todolist_rewrite_cpt_todo", "todos") ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false

        );

        register_post_type(PostType::CPT_TODO , apply_filters("todolist_register_" . PostType::CPT_TODO . "_post_type", $args) );
    }
}

