<?php

// wp-todolist/src/Todolist/WordPress/Walker/RadioTaxonomyWalker.php
namespace Todolist\WordPress\Walker;


/**
 * RadioTaxonomyWalker
 *
 * @author Thomas DENEULIN <contact@wp-god.com>
 * @since 1.0.0
 * @version 1.0.0
 */
class RadioTaxonomyWalker extends \Walker_Category {


    /**
     * Start the element output.
     *
     * @see Walker::start_el()
     *
     * @since 2.5.1
     *
     * @param string $output   Passed by reference. Used to append additional content.
     * @param object $category The current term object.
     * @param int    $depth    Depth of the term in reference to parents. Default 0.
     * @param array  $args     An array of arguments. @see wp_terms_checklist()
     * @param int    $id       ID of the current term.
     */
    public function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
        
        $taxonomy = 'category';
        if ( !empty( $args['taxonomy'] ) ) {
            $taxonomy = $args['taxonomy'];
        }


        $name                  = 'radio_tax_input['.$taxonomy.']';
        
        $args['popular_cats']  = empty( $args['popular_cats'] ) ? array() : $args['popular_cats'];
        $class                 = in_array( $category->term_id, $args['popular_cats'] ) ? ' class="popular-category"' : '';
        
        $args['selected_cats'] = empty( $args['selected_cats'] ) ? array() : $args['selected_cats'];
        
        $selected_term         = !empty( $args['selected_cats'] ) && ! is_wp_error( $args['selected_cats'] ) ? array_pop( $args['selected_cats'] ) : false;
    
        $selected_id           = ( $selected_term ) ? $selected_term : 0;

        if ( ! empty( $args['list_only'] ) ) {
            $aria_cheched = 'false';
            $inner_class = 'category';

            if ( in_array( $category->term_id, $args['selected_cats'] ) ) {
                $inner_class .= ' selected';
                $aria_cheched = 'true';
            }

            $output .= "\n" . '<li' . $class . '>' .
                '<div class="' . $inner_class . '" data-term-id=' . $category->term_id .
                ' tabindex="0" role="radio" aria-checked="' . $aria_cheched . '">' .
                esc_html( apply_filters( 'the_category', $category->name ) ) . '</div>';
        } else {

            $output .= "\n<li id='{$taxonomy}-{$category->term_id}'$class>" .
                '<label class="selectit"><input value="' . $category->term_id . '" type="radio" name="'.$name.'[]" id="in-'.$taxonomy.'-' . $category->term_id . '"' .
                checked( $category->term_id, $selected_id, false ) .
                disabled( empty( $args['disabled'] ), false, false ) . ' /> ' .
                esc_html( apply_filters( 'the_category', $category->name ) ) . '</label>';
        }
    }

    /**
     * Ends the element output, if needed.
     *
     * @since 2.1.0
     * @access public
     *
     * @see Walker::end_el()
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $page   Not used.
     * @param int    $depth  Optional. Depth of category. Not used.
     * @param array  $args   Optional. An array of arguments. Only uses 'list' for whether should append
     *                       to output. See wp_list_categories(). Default empty array.
     */
    public function end_el( &$output, $page, $depth = 0, $args = array() ) {

        $output .= "</li>\n";
    }



}
