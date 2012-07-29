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


    function __construct()
    {
        $this->logs = ROOTDIR.'/log';
    }

    function index()
    {
//        echo exec('whoami');

        echo $this->logs;
        echo exec('/usr/local/bin/nmap -v -A scanme.nmap.org >> '.$this->logs.'/log_file.log 2>&1 &');

    }

}
