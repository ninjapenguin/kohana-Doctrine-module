<?php

class Profiler extends Profiler_Core
{

    public function __construct()
    {
        parent::__construct();
        Event::add('profiler.run', array($this, 'doctrine'));
        
        
        $manager = Doctrine_Manager::getInstance();
        $conn = $manager->getCurrentConnection();
		
		$profiler = new Doctrine_Connection_Profiler();
        $conn->setListener($profiler);
        
        $this->profiler = $profiler;
    }
    
    public function doctrine()
    {
		if ( ! $table = $this->table('doctrine'))
			return;
        
        $table->add_column();
		$table->add_column('kp-column kp-data');
		$table->add_column('kp-column kp-data');
		$table->add_row(array('Doctrine Details', 'Type', 'Time'), 'kp-title', 'background-color: #E0FFE0');
		
		text::alternate();
		$c=0;
		$time = 0;
	
        foreach ($this->profiler as $event) {
            $time += $event->getElapsedSecs();
            $data = array($event->getQuery(), $event->getName(), number_format($event->getElapsedSecs(), 3));
			
			$class = text::alternate('', 'kp-altrow');
			$table->add_row($data, $class);
			$c++;         
        }

		$data = array('','Total: ' . $c, number_format($time, 3));
		$table->add_row($data, 'kp-totalrow');    
		
	}
    
}

?>
