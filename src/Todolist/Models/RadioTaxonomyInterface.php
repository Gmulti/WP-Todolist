<?php 
// wp-todolist/src/Todolist/Models/RadioTaxonomyInterface.php

namespace Todolist\Models;

/**
 *
 * @author Thomas DENEULIN
 * 
 */
interface RadioTaxonomyInterface{
    
    public function setTaxonomy($taxonomy);

    public function getTaxonomy();

    public function setTaxonomyObject($taxonomyObject);

    public function getTaxonomyObject();

    /**
     * @return void
     */
    public function createRadioButton();
}
