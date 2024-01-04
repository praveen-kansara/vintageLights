<?php

define("__URBAN_OFFICE__", true);

# Set include path
$app_path = dirname(__FILE__);
$app_path = substr($app_path, 0, strripos($app_path, DIRECTORY_SEPARATOR));
set_include_path(get_include_path().PATH_SEPARATOR.$app_path);

# Turning off notices
error_reporting(E_ERROR | E_WARNING | E_PARSE);

$startTime = microtime();

# Project configuration
require_once("config.php");

# Core files
require_once("include/utils.inc.php");
require_once("include/functions.inc.php");

$tasks = array(
  #array('php -f '.NON_PUBLIC_CRON_PATH.'cron/ReportTasks.php', '> /dev/null 2>&1 &'),
  array('php -f '.NON_PUBLIC_CRON_PATH.'cron/CronSendEmailToCustomer.php', '> /dev/null 2>&1 &'),
  array('php -f '.NON_PUBLIC_CRON_PATH.'cron/CronS3MediaUpload.php', '> /dev/null 2>&1 &'),
  array('php -f '.NON_PUBLIC_CRON_PATH.'cron/CronS3PdfUpload.php', '> /dev/null 2>&1 &'),
);

foreach ($tasks as $task) {
  $psAux   = `ps aux | grep "{$task[0]}"`;
  $lines   = explode("\n", $psAux);
  $running = false;

  foreach ($lines as $line) {
    if (strpos($line, $task[0]) !== false && strpos($line, 'grep') === false) {
      $running = true;
    }
  }

  if (!$running) `{$task[0]} {$task[1]}`;
}

exit(0);