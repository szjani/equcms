<?php
namespace Equ\Log;
use Doctrine\DBAL\Logging\SQLLogger;

class Doctrine implements SQLLogger {

  private $time;

  /**
   *
   * @var \Zend_Log
   */
  private $log;

  public function __construct(\Zend_Log $log) {
    $this->log = $log;
  }

  public function startQuery($sql, array $params = null, array $types = null) {
    $this->time = \microtime(true);
    $this->log->info(print_r(\func_get_args(), true));
  }

  public function stopQuery() {
//    $this->log->info('Last query time in miliseconds: ' . \microtime(true) - $this->time);
  }


}