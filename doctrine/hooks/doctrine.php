<?php defined('SYSPATH') OR die('No direct access allowed.');
class DoctrineLoader
{
    /**
     * Enables and registers doctrine
     * Acts as our doctrine bootstrapper
     */
    public static function enable()
    {
        require_once Kohana::find_file('vendor', 'doctrine/lib/Doctrine');
        
        //Register the doctrine autoloader
        spl_autoload_register(array('Doctrine', 'autoload'));
        
        //Register our own autoloader so that the doctrine models can go in our models dir
        spl_autoload_register(array('DoctrineLoader', 'autoload'));
                
        $manager = Doctrine_Manager::getInstance();
        
        //Create a default connection - we use the Kohana database config file
        $config = Kohana::config('database.default');

		//Format the DNS
		$dsn = $config['connection']['type'] . '://' . $config['connection']['user'] . ':' . $config['connection']['pass'] . '@' . $config['connection']['host'] . '/' . $config['connection']['database'];
		
		$conn = Doctrine_Manager::connection($dsn, 'default');
		
		//Only include the file when we need it - this allows kohanas extensions to work as well
		$manager->setAttribute(Doctrine::ATTR_MODEL_LOADING, Doctrine::MODEL_LOADING_CONSERVATIVE);
		
		//Global settings can be made here
		$manager->setAttribute(Doctrine::ATTR_EXPORT, Doctrine::EXPORT_ALL);
		
		//Allow table extensions
		$manager->setAttribute(Doctrine::ATTR_AUTOLOAD_TABLE_CLASSES, true);	
    }
    
    public static function autoload($class)
    {
        //Make use of Kohanas transparent file structure
		foreach (Kohana::include_paths() as $path) {
		  $path .= "models".DIRECTORY_SEPARATOR.strtolower($class).'.php';
		  //Only add the directory if it is valid
		  if(file_exists($path)) 
		  {
		      include $path;
		      return true;
	      }
		}
    }
    
}

Event::add('system.ready', array('DoctrineLoader', 'enable'));

?>