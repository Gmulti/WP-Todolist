<?php

// wp-todolist/src/Todolist/WordPress/Taxonomy/State.php
namespace Todolist\WordPress\Taxonomy;

use Todolist\Models\HooksInterface;
use Todolist\WordPress\Helpers\Taxonomy;
use Todolist\WordPress\Helpers\PostType;

/**
 * State
 *
 * @author Thomas DENEULIN <contact@wp-god.com>
 * @since 1.0.0
 * @version 1.0.0
 */
class State implements HooksInterface {

    public function hooks(){
        add_action( "init", array($this, 'initTaxonomy') );
    }

    public function initTaxonomy() {

        $labels = array(
            'name'              => __( 'State', 'td' ),
        );

        $args = array(
            'labels'             => $labels,
        );

        register_taxonomy( Taxonomy::STATE, array( PostType::CPT_TODO ), $args );        

    }
   
}
