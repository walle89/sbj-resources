<?php

use Sbj\tools\AppData;

if ( php_sapi_name() != 'cli')
{
    exit('This script can only be executed in CLI mode');
}

require_once __DIR__.'/src/AppData.php';

$appDataPath = __DIR__.'/../src/AppData.json';
$chljsFile = $argv[1] ?: '';

if (empty($chljsFile))
{
    exit('Valid path to a .chljs file is required'.PHP_EOL);
}

if (!is_writable($appDataPath))
{
    exit("Either can't find AppData.json, or the file isn't writable".PHP_EOL);
}

$ad = new AppData($appDataPath);

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
        echo "Success! $appDataPath updated".PHP_EOL;
    }
}
catch ( Exception $e )
{
    echo 'Error: '.$e->getMessage().PHP_EOL;
}