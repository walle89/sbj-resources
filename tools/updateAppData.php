<?php

use Sbj\tools\AppData;
use Sbj\tools\AppDataException;
use Sbj\tools\Console;

if (php_sapi_name() != 'cli')
{
    exit('This tool can only be executed in CLI mode'.PHP_EOL);
}

if (version_compare(PHP_VERSION, '8.1.0', '<'))
{
    exit('This tool requires PHP 8.1 or higher. You are running '.PHP_VERSION.PHP_EOL);
}

require_once __DIR__.'/src/Console.php';
require_once __DIR__.'/src/AppDataException.php';
require_once __DIR__.'/src/AppData.php';

$chljsFile       = $argv[1] ?: '';
$appDataPathJson = realpath(__DIR__.'/../src/AppData.json');
$appDataPathTxt  = realpath(__DIR__.'/../src/AppData.txt');

if (empty($chljsFile) OR !preg_match('/\.chlsj$/iu', $chljsFile))
{
    Console::error('Valid path to a .chljs file is required');
}

if (!is_writable($appDataPathJson))
{
    Console::error("Either can't find AppData.json, or the file isn't writable");
}

if (!is_writable($appDataPathTxt))
{
    Console::error("Either can't find AppData.txt, or the file isn't writable");
}

$currPath         = getcwd().DIRECTORY_SEPARATOR;
$relativePathJson = str_replace($currPath, '', $appDataPathJson);
$relativePathTxt  = str_replace($currPath, '', $appDataPathTxt);

try
{
    $appData         = new AppData($appDataPathJson, $appDataPathTxt);
    $trafficSource   = $appData->loadChljs($chljsFile);
    $importedAppData = $appData->parserChljs($trafficSource);
    $newAppData      = $appData->merge($importedAppData);

    if ( $appData->compare($newAppData) )
    {
        Console::info("No difference sense last update");
        exit(0);
    }

    if ( $appData->updateSource($newAppData) )
    {
        Console::success("Success! $relativePathJson and $relativePathTxt are updated");
    }
}
catch ( AppDataException $e )
{
    Console::error('Error: '.$e->getMessage());
}
catch ( Throwable $e )
{
    Console::error('Unexpected error: '.$e->getMessage());
}