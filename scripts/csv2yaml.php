<?php

try {
  if ($GLOBALS['argc'] < 2) {
    throw new Exception('Use php csv2yaml.php inputPath [keyPrefix modelName]');
  }

  require_once dirname(__FILE__) . DIRECTORY_SEPARATOR .'..' . DIRECTORY_SEPARATOR . 'defines.php';
  require_once 'Zend/Loader/Autoloader.php';
  $inputPath = $GLOBALS['argv'][1];
  $keyPrefix = isset($GLOBALS['argv'][2]) ? $GLOBALS['argv'][2] : 'keyPrefix';
  $modelName = isset($GLOBALS['argv'][3]) ? $GLOBALS['argv'][3] : 'Default_Model_';

  $autoloader = Zend_Loader_Autoloader::getInstance();
  $autoloader->registerNamespace('Factory');
  
  $csv = new Factory_File_Csv($inputPath, true, true);
  $csv
    ->setDelimiter(',')
    ->setEnclosure('"');
  
  print $modelName . ':' . PHP_EOL;
  $cnt = 1;
  foreach ($csv as $line) {
    print '  ' . $keyPrefix . $cnt . ':' . PHP_EOL;
    foreach ($line as $key => $value) {
      print '    ' . $key . ': "' . $value . '"' . PHP_EOL; 
    }
  }
} catch (Exception $e) {
  echo $e->getMessage()."\n";
}
