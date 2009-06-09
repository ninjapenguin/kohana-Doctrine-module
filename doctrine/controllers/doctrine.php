<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Default Kohana controller. This controller should NOT be used in production.
 * It is for demonstration purposes only!
 *
 * @package    Core
 * @author     Kohana Team
 * @copyright  (c) 2007-2008 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
class Doctrine_Controller extends Controller {

    public function index()
    {
        //Only accessible through CLI
        if (PHP_SAPI !== 'cli') Kohana::show_404();
        
        //Clear the output buffer in preperation for any user prompts
        ob_end_flush();
        
        //Default configuration settings
        $config = array('data_fixtures_path'  =>  MODPATH.'doctrine'.DIRECTORY_SEPARATOR.'fixtures'.DIRECTORY_SEPARATOR,
                        'models_path'         =>  MODPATH.'doctrine'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR,
                        'migrations_path'     =>  MODPATH.'doctrine'.DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR,
                        'sql_path'            =>  MODPATH.'doctrine'.DIRECTORY_SEPARATOR.'schema'.DIRECTORY_SEPARATOR,
                        'yaml_schema_path'    =>  MODPATH.'doctrine'.DIRECTORY_SEPARATOR.'schema'.DIRECTORY_SEPARATOR,
                        'generateTableClasses' => true);

        $cli = new Doctrine_Cli($config);
        
        //remove our routing path
        $args = $_SERVER['argv'];
        $call = array_shift($args);
        $route = array_shift($args);
        array_unshift($args,$call);
        
        $cli->run($args);    
    }

} // End Welcome Controller