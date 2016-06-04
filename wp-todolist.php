<?php

/**
 * Plugin Name: WP Todolist
 * Description: Gestion d'une Todolist dans WordPress
 * Version: 1.0.0
 * Author: <a href="http://twitter.com/TDeneulin" target="_blank">Thomas DENEULIN</a>
 */

require_once( dirname( __FILE__ ) . '/vendor/autoload.php' );

use Todolist\Todolist;
use Todolist\WordPress\PostType\Todo;

$actions = array(
    new Todo()
);


$todolist = new Todolist($actions);
$todolist->execute();