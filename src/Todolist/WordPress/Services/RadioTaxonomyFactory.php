<?php

// wp-todolist/src/Todolist/WordPress/Services/RadioTaxonomyFactory.php
namespace Todolist\WordPress\Services;

use Todolist\Exception\TaxonomyObjectNotExist;
use Todolist\Exception\InterfaceException;
use Todolist\WordPress\Handler\RadioTaxonomyHandler;
use Todolist\Models\RadioTaxonomyInterface;

/**
 * RadioTaxonomyFactory
 *
 * @author Thomas DENEULIN <contact@wp-god.com>
 * @since 1.0.0
 * @version 1.0.0
 */
class RadioTaxonomyFactory {


    public function createRadioButton($taxonomy){
        $taxonomyObject = get_taxonomy( $taxonomy );

        if($taxonomyObject === false){
            throw new TaxonomyObjectNotExist(__("Taxonomy does not exist", "td"));
        }

        $radioTaxonomyHandler = apply_filters('td_radio_taxonomy_handler', new RadioTaxonomyHandler());

        if(!$radioTaxonomyHandler instanceOf RadioTaxonomyInterface){
            throw new InterfaceException(__("Handler not implements RadioTaxonomyInterface", "td"));    
        }

        $radioTaxonomyHandler->setTaxonomy($taxonomy)
                             ->setTaxonomyObject($taxonomyObject)
                             ->createRadioButton();

    }
   
}
