<?php

use Sbj\tools\AppData;

if ( php_sapi_name() != 'cli')
{
    exit('This script can only be executed in CLI mode');
}

if (version_compare(PHP_VERSION, '8.1.0', '<'))
{
    exit('This tool requires PHP 8.1 or higher. You are running '.PHP_VERSION);
}

require_once __DIR__.'/src/AppData.php';

$appDataPathJson = realpath(__DIR__.'/../src/AppData.json');
$appDataPathTxt  = realpath(__DIR__.'/../src/AppData.txt');
$chljsFile       = $argv[1] ?: '';

if (empty($chljsFile) OR !preg_match('/\.chlsj$/iu', $chljsFile))
{
    exit('Valid path to a .chljs file is required'.PHP_EOL);
}

if (!is_writable($appDataPathJson))
{
    exit("Either can't find AppData.json, or the file isn't writable".PHP_EOL);
}

if (!is_writable($appDataPathTxt))
{
    exit("Either can't find AppData.txt, or the file isn't writable".PHP_EOL);
}

$ad = new AppData($appDataPathJson, $appDataPathTxt);

try
{
    $trafficSource   = $ad->loadChljs($chljsFile);
    $importedAppData = $ad->parserChljs($trafficSource);

    $newAppData = $ad->merge($importedAppData);
    if ( $ad->compare($newAppData) )
    {
        echo "No difference sense last update".PHP_EOL;
        exit;
    }

    if ( $ad->updateSource($newAppData) )
    {
        echo "Success! $appDataPathJson and $appDataPathTxt are updated".PHP_EOL;
    }
}
catch ( Exception $e )
{
    echo 'Error: '.$e->getMessage().PHP_EOL;
}