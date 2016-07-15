<?php

// wp-todolist/src/Todolist/WordPress/Handler/RadioTaxonomyHandler.php
namespace Todolist\WordPress\Handler;

use Todolist\Models\RadioTaxonomyInterface;
use Todolist\WordPress\Walker\RadioTaxonomyWalker;

/**
 * RadioTaxonomyHandler
 *
 * @author Thomas DENEULIN <contact@wp-god.com>
 * @since 1.0.0
 * @version 1.0.0
 */
class RadioTaxonomyHandler implements RadioTaxonomyInterface {

    public function __construct(){
        $this->viewNonce = true;
    }

    public function setTaxonomy($taxonomy){
        $this->taxonomy = $taxonomy;
        return $this;
    }

    public function getTaxonomy(){
        return $this->taxonomy;
    }

    public function setTaxonomyObject($taxonomyObject){
        $this->taxonomyObject = $taxonomyObject;
        return $this;
    }

    public function getTaxonomyObject(){
        return $this->taxonomyObject;
    }

    /**
     * @see Todolist\Models\RadioTaxonomyInterface
     */
    public function createRadioButton(){

        add_action( 'admin_menu', array( $this, 'removeMetaBox' ) );
        add_action( 'add_meta_boxes', array( $this, 'addMetaBox' ) );
        add_filter( 'wp_terms_checklist_args', array( $this, 'filterChecklistArgs' ) );

        add_action( 'save_post', array( $this, 'saveOneTerm' ) );
        add_action( 'edit_attachment', array( $this, 'saveOneTerm' ) );

        add_action( 'load-edit.php', array( $this, 'hierarchicalTaxonomy' ) );
        add_action( 'quick_edit_custom_box', array( $this, 'quickEditCustomNonce' ) );  
    }



    public function removeMetaBox() {
        foreach ( $this->getTaxonomyObject()->object_type as $postType ){
            $id = (!is_taxonomy_hierarchical( $this->getTaxonomy() )) ? 'tagsdiv-'.$this->getTaxonomy() : $this->getTaxonomy() . 'div';

            remove_meta_box( $id, $postType, 'side' );   
        }
    }

    public function addMetaBox() {
        foreach ( $this->getTaxonomyObject()->object_type as $postType ){
            $label = $this->getTaxonomyObject()->labels->singular_name;
            $id    = (!is_taxonomy_hierarchical( $this->getTaxonomy() )) ? 'radio-tagsdiv-' . $this->getTaxonomy() : 'radio-' . $this->getTaxonomy() . 'div' ;

            add_meta_box( $id, $label ,array( $this,'radioMetabox' ), $post_type , 'side', 'core', array( 'taxonomy'=>$this->getTaxonomy() ) );
        }
    }


  
    public function radioMetabox( $post, $metabox ) {
        $defaults = array(
            'taxonomy' => 'category'
        );
        $args     = array();
        if (isset($metabox['args']) || is_array($metabox['args']) ){
            $args = $metabox['args'];
        }

        $args           = wp_parse_args($args, $defaults);
        $checked_terms  = $post->ID ? get_the_terms( $post->ID, $args["taxonomy"] ) : array();
        
        $single_term    = ! empty( $checked_terms ) && ! is_wp_error( $checked_terms ) ? array_pop( $checked_terms ) : false;
        $single_term_id = $single_term ? (int) $single_term->term_id : 0;

        include_once(apply_filters("td_radio_taxonomy_metabox", TD_PLUGIN_DIR_TEMPLATES_ADMIN . "/radio-taxonomy-metabox.php"));
    }

    public function filterChecklistArgs($args){

        if( !array_key_exists("taxonomy", $args) && $this->getTaxonomy() != $args['taxonomy'] ) {
            return $args;
        }

        $args['walker']        = new RadioTaxonomyWalker();
        $args['checked_ontop'] = false;

        return $args;
    }


    public function saveOneTerm( $post_id ) {

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
            return $post_id;
        }

        if(function_exists( 'ms_is_switched' ) && ms_is_switched() ){
            return $post_id;
        }

        if(isset( $_REQUEST['post_type'] ) && !in_array ( $_REQUEST['post_type'], $this->getTaxonomyObject()->object_type ) ){
            return $post_id;
        } 

        if (isset( $_POST["_radio_taxonomy_nonce_" + $this->getTaxonomy()]) && 
            !wp_verify_nonce( $_REQUEST["_radio_taxonomy_nonce_" + $this->getTaxonomy()], "radio_nonce-{$this->getTaxonomy()}") 
        ){
            return $post_id;
        } 

        if ( !isset( $_REQUEST["radio_tax_input"][$this->getTaxonomy()])){
            return $post_id;
        }

        $terms = (array) $_REQUEST["radio_tax_input"][$this->getTaxonomy()]; 

        if ( $this->getTaxonomy() === "category" && empty($terms)) {
            $single_term = intval(get_option('default_category'));
        }

        $singleTerm = intval(array_shift($terms));

        if ( current_user_can( $this->getTaxonomyObject()->cap->assign_terms ) ) {
            wp_set_object_terms( $post_id, $singleTerm, $this->getTaxonomy() );
        }

        return $post_id;
    }

    public function hierarchicalTaxonomy() {
        global $wp_taxonomies;
        $wp_taxonomies[$this->getTaxonomy()]->hierarchical = true;
    }

    public function quickEditCustomNonce() {
        if ( $this->viewNonce ) {
            $this->viewNonce = false;
            wp_nonce_field( 'radio_nonce-' . $this->getTaxonomy(), '_radio_taxonomy_nonce_' . $this->getTaxonomy() );
        }
        
    }



   
}
