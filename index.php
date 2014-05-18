<?php

/**
 * @author Martin OK2UEC Nakládal <ok2uec@gmail.com>
 * @version 1.0
 */
class EcholinkStatus {

    private $domainVerification = "http://echolink.org/logins.jsp";

    /**
     * Developer debug mode
     */
    private $debug = false;
 
    //----------------------------- 
    private $repeaterList = array();
    private $timeStep;

    public function __construct() {
        $this->timeStep = microtime(true);
        $this->debugMessage("Start");
    }

    private function debugMessage($text) {
        if ($this->debug) {
            $time_end = microtime(true);
            $executionTimeActual = ($time_end - $this->timeStep);
            echo '<br />DEBUG: (' . $executionTimeActual . "sec):" . $text;
            // problem \r\n
        }
    }

    /**
     * Adding a repeater to a list that will check whether it is connected. 
     *  @param string $call Callname repeater
     */
    public function addRepeater($call) {
        if (!array_key_exists($call, $this->repeaterList)) {
            $this->debugMessage("Add repeater: " . $call); 
            $this->repeaterList[] = array("repeater" => strtoupper($call), "status" => null, "update" => null);
        } else {
            //If you want to return a value or. error
        }
    }

    /**
     * List of repeaters in field 
     */
    public function GetRepeater() {
        return $this->repeaterList;
    }

    /**
     * desolation control, data is downloaded from the web and check whether 
     * there is a converter in the data and accordingly selects the online / offline
     * */
    public function check() {
        $this->debugMessage("Start check repeater: ");
        /**
         * verify that the server is enabled to obtain data from remote server. 
         * The function can be enabled only for local files and not on remote servers.
         */
        if (!file_get_contents("data:,ok")) {
            die("Houston, we have a stream wrapper problem.");
        }
        $this->debugMessage("Check repeater - file_get_contents is Enabled");
        $options = array(
            'http' => array(
                'method' => "GET",
                'header' => "Accept-language: en\r\n" .
                "Cookie: foo=bar\r\n" . // check function.stream-context-create on php.net
                "User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad 
            )
        );

        $context = stream_context_create($options);
        $file = @file_get_contents($this->domainVerification, false, $context);

        $this->debugMessage("Start check repeater - data from server finish");

        if (count($file) > 0) {
            foreach ($this->repeaterList as &$repeater) {
                $repS = $repeater["repeater"];
                $this->debugMessage("Start check repeater - data " . strtoupper($repS) . "-R SET OK!");
                $repeater["status"] = strpos($file, strtoupper($repS) . "-R")? true : false;
                $repeater["update"] = time();
                $repeater["date"] = StrFTime("%d/%m/%Y %H:%M:%S", Time());
            }
        } else {
            $this->debugMessage("SERVER PROBLEM!");
            //EchoLink server is unavailable
        }
        echo "Finish script";
        $this->debugMessage("End check repeater: OK");
    }

}

$e = new EcholinkStatus();

$e->addRepeater("ok0mar");
$e->addRepeater("ok0bhd");
$e->addRepeater("ok0uec");
$e->check();


//array [..] = array("repeater","status","update","date")
var_dump($e->GetRepeater());
