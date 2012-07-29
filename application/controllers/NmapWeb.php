<?php
/**
 * Created by JetBrains PhpStorm.
 * User: lbs
 * Date: 7/28/12
 * Time: 9:02 PM
 * To change this template use File | Settings | File Templates.
 */

require(APPPATH.'/libraries/request.php');


class NmapWeb extends CI_Controller
{
    /**
     * @var to store the commands
     */
    protected $commands;

    /**
     * @var the path to the log file
     */
    protected $logs;

    /**
     * @var the target of the nmap scan
     */
    protected $target;

    /**
     * @var the log file name duh
     */
    protected $log_file_name;

    /**
     * @var string the file path
     */
    protected $nmap_file_path;

    /**
     * @var the actual nmap cmd that gets run
     */
    protected $cmd;

    /**
     * @var the contents of the log file
     */
    public $log_contents;

    /**
     * sets up some defaults
     */
    function __construct()
    {
		parent::__construct();
        $this->setLogs( ROOTDIR.'/log/'.$this->getLogFileName() );
        $this->setTarget("localhost");
		$this->nmap_file_path = "/usr/local/bin/nmap";
        
    }

    /**
     * displays the form
     */
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

    /**
     * processes the form submission and goes to the display logs page
     */
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

		// clears the log file of the old junk
		file_put_contents($this->getLogs(), "");

        // runs the new command
		$this->runNmap();

        // go display the log file when its ready
		redirect('/nmapweb/logs/', 'refresh');
	}


    /**
     * runs the actual nmap command
     */
    private function runNmap()
	{
        $this->cmd = $this->nmap_file_path.' '. $this->getCommands().' '.$this->target.' >> '.$this->logs.' 2>&1 &';

        echo $this->cmd;
        
		exec($this->cmd);
	}
	


    /**
     * displays the log file
     *
     * @todo make ajaxy
     */
    public function logs()
    {
		$data = array();
		
//		$data['cmd'] = "cmd ". $this->cmd;
		
		$data['log_contents'] = file_get_contents($this->getLogs());

		$this->load->view('display_logs', $data);

    }

    /**
     * @param $commands
     */
    public function setCommands($commands)
    {
        $this->commands = $commands;
    }

    /**
     * defaults to quick scan
     *
     * @return mixed
     */
    public function getCommands()
    {
		if(empty($this->commands))
		{
			$this->commands = '-T4 -F';
		}
        return $this->commands;
    }

    /**
     * @param $log_file_name
     */
    public function setLogFileName($log_file_name)
    {
        $this->log_file_name = $log_file_name;
    }

    /**
     * @return mixed
     */
    public function getLogFileName()
    {
        if(empty($this->log_file_name))
        {
            $this->log_file_name = 'log_file.log';
        }
        return $this->log_file_name;
    }

    /**
     * @param $logs
     */
    public function setLogs($logs)
    {
        $this->logs = $logs;
    }

    /**
     * @return mixed
     */
    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * @param $target
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * @return mixed
     */
    public function getTarget()
    {
        return $this->target;
    }


}
