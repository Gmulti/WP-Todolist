<?php
// wp-todolist/src/Todolist/Todolist.php
namespace Todolist;

if ( ! defined( 'ABSPATH' ) ) exit;

use Todolist\Models\HooksInterface;
use Todolist\Models\HooksFrontInterface;
use Todolist\Models\HooksAdminInterface;

/**
 * Todolist
 *
 * @author Thomas DENEULIN
 * @version 1.0.0
 * @since 1.0.0
 */
class Todolist implements HooksInterface{

    protected $actions   = array();

    /**
     * @param array $actions
     */
    public function __construct($actions = array()){
        $this->actions = $actions;
    }

    /**
     * @return boolean
     */
    protected function canBeLoaded(){
        return true;
    }


    /**
     * Execute plugin
     */
    public function execute(){
        if ($this->canBeLoaded()){
            add_action( 'plugins_loaded' , array($this,'hooks'), 0);
        }
    }

    /**
     * @return array
     */
    public function getActions(){
        return $this->actions;
    }

    /**
     * Implements hooks from HooksInterface
     *
     * @see Todolist\Models\HooksInterface
     *
     * @return void
     */
    public function hooks(){
        foreach ($this->getActions() as $key => $action) {
            switch(true) {  // Cela m'évite de faire un if / else if
                case $action instanceof HooksAdminInterface:
                    if (is_admin()) {
                        $action->hooks();
                    }
                    break;
                case $action instanceof HooksFrontInterface:
                    if (!is_admin()) {
                        $action->hooks();
                    }
                    break;
                case $action instanceof HooksInterface:
                    $action->hooks();
                    break;
            }
        }
    }
}
