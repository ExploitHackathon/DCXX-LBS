<?php
/**
 * Created by JetBrains PhpStorm.
 * User: logan
 * Date: 7/28/12
 * Time: 9:02 PM
 * To change this template use File | Settings | File Templates.
 */

require(APPPATH.'/libraries/request.php');


class NmapWeb extends CI_Controller
{

    protected $commands;
    protected $logs;
    protected $target;
    protected $log_file_name;
    protected $nmap_file_path;
	public $log_contents;


    function __construct()
    {
		parent::__construct();
        $this->setLogs( ROOTDIR.'/log/'.$this->getLogFileName() );
        $this->setTarget("localhost");
		$this->nmap_file_path = "/usr/local/bin/nmap";
        
    }

    function index()
    {
		$this->load->helper('form');
		$html = "";
		
		$html .= form_open('nmapweb/run');
		
		$target = array(
		              'name'        => 'target',
		              'id'          => 'target',
		              'value'       => '',
		              'maxlength'   => '100',
		              'size'        => '20',
		              'style'       => 'width:10%',
		            );
		
		$html .= form_label('What is your target?', 'target');
		$html .= form_input($target);
		
		
		$options = array(
		    'iscan'  => 'Intense Scan',
		    'udp'    => 'Intense with UDP Ports',
		    'tcp'   => 'Intense all TCP Ports',
		    'noping' => 'Intense with no Ping',
			'ping' => 'Ping scan',
			'quick' => 'Quick Scan',
			'quickplus' => 'Quick Scan plus Unicorns',
			'traceroute' => 'Normal scan',
			'slow' => 'Slow comprehensive scan',
		                );

		$html .= form_dropdown('Scans', $options, 'quick');
		
		$html .= form_submit('submit', 'Submit!');
		
		
		$data['form'] = $html;
		
		$this->load->view('nmapForm', $data);
	

		// $this->runNmap();
		// $this->readLogs();
		
		
    }

	public function run()
	{
		$this->post = Request::post();
        
        // $databaseName = $this->GetDatabaseName();
        
        foreach ($this->post as $key => $var)
        {
			echo $key ." ... " .$var;
		}
	}


	public function find()
	{
		echo "foundit";
	}

	private function runNmap()
	{
		exec($this->nmap_file_path.' -T4 -F '.$this->target.' >> '.$this->logs.' 2>&1 &');
	}
	
	private function createLogFile()
	{
		exec($this->nmap_file_path.' -v -A '.$this->target.' >> '.$this->logs.' 2>&1 &');
	}

    private function readLogs()
    {
		$data = array();
		
		
		$data['log_contents'] = file_get_contents($this->getLogs());
		
		file_put_contents($this->getLogs(), "");
		

		$this->load->view('display_logs', $data);

    }

    public function setCommands($commands)
    {
        $this->commands = $commands;
    }

    public function getCommands()
    {
        return $this->commands;
    }

    public function setLogFileName($log_file_name)
    {
        $this->log_file_name = $log_file_name;
    }

    public function getLogFileName()
    {
        if(empty($this->log_file_name))
        {
            $this->log_file_name = 'log_file.log';
        }
        return $this->log_file_name;
    }

    public function setLogs($logs)
    {
        $this->logs = $logs;
    }

    public function getLogs()
    {
        return $this->logs;
    }

    public function setTarget($target)
    {
        $this->target = $target;
    }

    public function getTarget()
    {
        return $this->target;
    }


}
