<?php
/**
 * Created by JetBrains PhpStorm.
 * User: logan
 * Date: 7/28/12
 * Time: 9:02 PM
 * To change this template use File | Settings | File Templates.
 */
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
        $this->setLogs( ROOTDIR.'/log/'.$this->getLogFileName() );
        $this->setTarget("localhost");
		$this->nmap_file_path = "/usr/local/bin/nmap";
        
    }

    function index()
    {
        // echo exec('whoami');
        // echo "executing nmap against localhost";
        // echo $this->logs;
		$this->runNmap();
		$this->readLogs();
        // echo exec('cat '.$this->logs);
    }


	private function findNmap()
	{
		
	}

	private function runNmap()
	{
		exec($this->nmap_file_path.' -v -A '.$this->target.' >> '.$this->logs.' 2>&1 &');
		
	}

    private function readLogs()
    {
		$data = array();

        if ($stream = fopen($this->getLogs(), 'r')) {
            
            $data['log_contents'] = stream_get_contents($stream, -1, 0);

            fclose($stream);
        }

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
