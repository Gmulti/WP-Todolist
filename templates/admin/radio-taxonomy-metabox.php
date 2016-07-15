<?php wp_nonce_field( 'radio_nonce-' . $args["taxonomy"], '_radio_nonce-' . $args["taxonomy"] ); ?>

<div id="taxonomy-<?php echo $args["taxonomy"]; ?>" class="categorydiv form-no-clear">

    <ul id="<?php echo $args["taxonomy"]; ?>-tabs" class="category-tabs">
        <li class="tabs">
            <a href="#<?php echo $args["taxonomy"]; ?>-all">
                <?php echo $this->getTaxonomyObject()->labels->all_items; ?>    
            </a>
        </li>
        <li class="hide-if-no-js">
            <a href="#<?php echo $args["taxonomy"]; ?>-pop">
                <?php _e('Most Used'); ?>
            </a>
        </li>
    </ul>


    <div id="<?php echo $args["taxonomy"]; ?>-pop" class="tabs-panel" style="display: none;">
        <ul id="<?php echo $args["taxonomy"]; ?>checklist-pop" class="categorychecklist form-no-clear" >
            <?php 
                $termsPopular = get_terms( $args["taxonomy"], array( 
                    'orderby'      => 'count', 
                    'order'        => 'DESC', 
                    'number'       => 10, 
                    'hierarchical' => false 
                    ) 
                );

                $disabled = '';
                if ( ! current_user_can($this->getTaxonomyObject()->cap->assign_terms) ){
                    $disabled = 'disabled="disabled"';
                }

                $termsPopularIds = array(); 
            ?>

                <?php foreach( $termsPopular as $term ): 

                    $termsPopularIds[] = $term->term_id;

                    $value = is_taxonomy_hierarchical( $args["taxonomy"] ) ? $term->term_id : $term->slug;
                    $id    = 'popular-'.$args["taxonomy"].'-'.$term->term_id;
                ?>
                    <li id="<?php echo $id; ?>">
                        <label class='selectit'>
                            <input type='radio' 
                                   id="in-<?php echo $id; ?>" 
                                   <?php checked( $single_term_id, $term->term_id, false );  ?>
                                    value="<?php echo $value; ?>" 
                                    <?php echo $disabled; ?>/>
                                    &nbsp;<?php echo $term->name; ?>
                            <br />

                        </label>
                    </li>
                <?php endforeach; ?>
        </ul>
    </div>

    <div id="<?php echo $args["taxonomy"]; ?>-all" class="tabs-panel">
        <ul id="<?php echo $args["taxonomy"]; ?>checklist" data-wp-lists="list:<?php echo $args["taxonomy"]?>" class="categorychecklist form-no-clear">
            <?php 
                wp_terms_checklist( $post->ID, array( 
                        'taxonomy' => $args["taxonomy"], 
                        'popular_cats' => $termsPopularIds 
                    ) 
                ) 
            ?>
        </ul>
    </div>

    <?php if ( current_user_can( $this->getTaxonomyObject()->cap->edit_terms ) ) : ?>
        <div id="<?php echo $args["taxonomy"]; ?>-adder" class="wp-hidden-children">
            <h4>
                <a id="<?php echo $args["taxonomy"]; ?>-add-toggle" href="#<?php echo $args["taxonomy"]; ?>-add" class="hide-if-no-js">
                    <?php
                        printf( __( '+ %s' ), $this->getTaxonomyObject()->labels->add_new_item );
                    ?>
                </a>
            </h4>
            <p id="<?php echo $args["taxonomy"]; ?>-add" class="category-add wp-hidden-child">
                <label class="screen-reader-text" for="new<?php echo $args["taxonomy"]; ?>">
                    <?php echo $this->getTaxonomyObject()->labels->add_new_item; ?>    
                </label>

                <input type="text" name="new<?php echo $args["taxonomy"]; ?>" id="new<?php echo $args["taxonomy"]; ?>" class="form-required" value="<?php echo esc_attr( $this->getTaxonomyObject()->labels->new_item_name ); ?>" aria-required="true"/>
                <label class="screen-reader-text" for="new<?php echo $args["taxonomy"]; ?>_parent">
                    <?php echo $this->getTaxonomyObject()->labels->parent_item_colon; ?>
                </label>

                <?php if( is_taxonomy_hierarchical( $args["taxonomy"]) ) {
                    wp_dropdown_categories( 
                        array( 
                            'taxonomy' => $args["taxonomy"], 
                            'hide_empty' => 0, 
                            'name' => 'new'.$args["taxonomy"].'_parent', 
                            'orderby' => 'name', 
                            'hierarchical' => 1, 
                            'show_option_none' => '&mdash; ' . $this->getTaxonomyObject()->labels->parent_item . ' &mdash;' 
                        ) 
                    );
                } ?>

                <input type="button" id="<?php echo $args["taxonomy"]; ?>-add-submit" data-wp-lists="add:<?php echo $args["taxonomy"] ?>checklist:<?php echo $args["taxonomy"] ?>-add" class="button category-add-submit" value="<?php echo esc_attr( $this->getTaxonomyObject()->labels->add_new_item ); ?>" tabindex="3" />
                <?php wp_nonce_field( 'add-'.$args["taxonomy"], '_ajax_nonce-add-'.$args["taxonomy"] ); ?>
                <span id="<?php echo $args["taxonomy"]; ?>-ajax-response"></span>
            </p>
        </div>
    <?php endif; ?>
</div>