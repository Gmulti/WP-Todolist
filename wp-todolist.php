<?php

/**
 * Plugin Name: WP Todolist
 * Description: Gestion d'une Todolist dans WordPress
 * Version: 1.0.0
 * Author: <a href="http://twitter.com/TDeneulin" target="_blank">Thomas DENEULIN</a>
 */

require_once( dirname( __FILE__ ) . '/vendor/autoload.php' );

use Todolist\Todolist;

use Todolist\WordPress\Helpers\Taxonomy;

use Todolist\WordPress\PostType\Todo;

use Todolist\WordPress\Taxonomy\State;

use Todolist\WordPress\Services\RadioTaxonomy;
use Todolist\WordPress\Services\RadioTaxonomyFactory;

define("TD_PLUGIN_PATH", plugin_dir_path( __FILE__ ));
define("TD_PLUGIN_DIR_TEMPLATES", TD_PLUGIN_PATH . "templates" );
define("TD_PLUGIN_DIR_TEMPLATES_ADMIN", TD_PLUGIN_DIR_TEMPLATES . "/admin" );

$actions = array(
    new Todo(),
    new State(),
    new RadioTaxonomy(
        new RadioTaxonomyFactory(),
        array(
            Taxonomy::STATE
        )
    )
);


$todolist = new Todolist($actions);
$todolist->execute();