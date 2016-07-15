<?php

// wp-todolist/src/Todolist/WordPress/Services/RadioTaxonomy.php
namespace Todolist\WordPress\Services;

use Todolist\Models\HooksInterface;

/**
 * RadioTaxonomy
 *
 * @author Thomas DENEULIN <contact@wp-god.com>
 * @since 1.0.0
 * @version 1.0.0
 */
class RadioTaxonomy implements HooksInterface {

    public function __construct($radioTaxonomyFactory, $taxonomies = array()){

        $this->radioTaxonomyFactory = apply_filters('td_radio_taxonomy_factory', $radioTaxonomyFactory);
        $this->taxonomies           = apply_filters('td_radio_taxonomies', $taxonomies);
        
    }

    /**
     * @see Todolist\Models\HooksInterface
     */
    public function hooks(){
        add_action( 'registered_taxonomy', array( $this, 'loadRadioTaxonomy' ) );
    }

    public function getTaxonomies(){
        return $this->taxonomies;
    }

    public function loadRadioTaxonomy($taxonomy){
        if(!in_array($taxonomy, $this->getTaxonomies())){
            return false;
        }

        $this->radioTaxonomyFactory->createRadioButton($taxonomy);
    }

   
}
