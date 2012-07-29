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
	protected $cmd;
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
		              'value'       => 'localhost',
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

		$html .= form_dropdown('scans', $options, 'quick');
		
		$html .= form_submit('submit', 'Submit!');
		
		
		$data['form'] = $html;
		
		$this->load->view('nmapForm', $data);
		
		
    }

	public function run()
	{
		$this->load->helper('url');
		
		$scans = Request::post('scans');
		
		switch($scans)
		{
			case "iscan":
				$this->setCommands('-T4 -A -v');
				break;
            case "udp":
				$this->setCommands('-sS -sU -T4 -A -v');
				break;
            case "tcp":
                $this->setCommands('-p 1-65535 T4 -A -v');
                break;
            case "noping":
                $this->setCommands('-T4 -A -v -Pn');
                break;
            case "ping":
                $this->setCommands('-sn');
                break;
            case "quick":
                $this->setCommands('-T4 -F');
                break;
            case "quickplus":
                $this->setCommands('-sV -T4 -O -F --version-light');
                break;
            case "traceroute":
                $this->setCommands('-sn --traceroute');
                break;
            case "slow":
                $this->setCommands('-sS -sU -T4 -A -v -PE -PP -PS80,443 -PA3389 -PU40125 -PY -g 53');
                break;
		}
        
        // $databaseName = $this->GetDatabaseName();

		
        
		//         foreach ($this->post as $key => $var)
		//         {
		// 	echo $key ." ... " .$var. "... ";
		// }
		
		file_put_contents($this->getLogs(), "");
		
		$this->runNmap();
		
		redirect('/nmapweb/logs/', 'refresh');
	}


	public function find()
	{
		echo "foundit";
	}

	private function runNmap()
	{
        $this->cmd = $this->nmap_file_path.' '. $this->getCommands().' '.$this->target.' >> '.$this->logs.' 2>&1 &';
        
		exec($this->cmd);
	}
	
	private function createLogFile()
	{
		exec($this->nmap_file_path.' -v -A '.$this->target.' >> '.$this->logs.' 2>&1 &');
	}

    public function logs()
    {
		$data = array();
		
		$data['cmd'] = $this->cmd;
		
		$data['log_contents'] = file_get_contents($this->getLogs());
		
		// file_put_contents($this->getLogs(), "");
		

		$this->load->view('display_logs', $data);

    }

    public function setCommands($commands)
    {
        $this->commands = $commands;
    }

    public function getCommands()
    {
		if(empty($this->commands))
		{
			$this->commands = '-T4 -F';
		}
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
