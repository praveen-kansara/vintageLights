<?php

if(!defined('__URBAN_OFFICE__')) die('Invalid Access!');

class Logger {

  var $mode;
  var $object;
  var $logfile = "site.log";

  // levels of logging
  var $log_levels = Array(
    "info"  => 0
    ,"debug" => 1
    ,"warn"  => 2
    ,"error" => 3
    ,"fatal" => 4

  );

  function __construct($object="") {
    global $app_path;

    $this->logfile = $app_path . DIRECTORY_SEPARATOR. "site.log";

    // TODO: read properties from a physical file expected at the application root
    $this->get_mode();
    if(!isset($this->mode) || $this->mode == "") $this->mode = "fatal";
    $this->object = $object;
  }

  function info($msg="") {

    if($this->log_levels[$this->mode] <= $this->log_levels["info"]) {
      $this->writeLog("info", $msg);
    }

  }


  function debug($msg="") {

    if($this->log_levels[$this->mode] <= $this->log_levels["debug"]) {
      $this->writeLog("debug", $msg);
    }

  }

  function error($msg="") {

    if($this->log_levels[$this->mode] <= $this->log_levels["error"]) {
      $this->writeLog("error", $msg);
    }

  }

  function warn($msg="") {

    if($this->log_levels[$this->mode] <= $this->log_levels["error"]) {
      $this->writeLog("warn", $msg);
    }

  }


  // this is the highest level
  function fatal($msg="") {

    $this->writeLog("fatal", $msg);

  }

  function writeLog($mode, $msg="") {

    $handle = fopen($this->logfile, 'a+');

    if(!$handle) die("Creating logger file failed..last thing you wanted, right!");

    $success = fwrite($handle, date("Y-m-d H:i:s") . " : [$mode] : [$this->object]\n$msg\n");

    if(!$success) die("Writing to logger file failed!");

    fclose($handle);

  }




  // infact it sets the current logging mode
  function get_mode() {

    $prop_file = $this->logfile .".prop";

    if(!is_file($prop_file)) {

      $this->mode = "fatal";

      $handle = fopen($prop_file, 'w');
      $success = fwrite($handle, $this->mode);

      if(!$success) die("Writing logger mode file failed.. last thing you need");

      fclose($handle);


    } else {

      $this->mode =  trim(file_get_contents($prop_file));

    }
  }


}
