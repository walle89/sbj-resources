<?php

if (php_sapi_name() != 'cli')
{
    exit('This script can only be executed in CLI mode');
}

$appDataPath = __DIR__.'/../src/AppData.json';
$chljsFile = $argv[1] ?: '';

$appsMapping = [
    'SwedbankMOBPrivate'     => 'swedbank',
    'SavingbankMOBPrivate'   => 'sparbanken',
    'SwedbankMOBCorporate'   => 'swedbank_foretag',
    'SavingbankMOBCorporate' => 'sparbanken_foretag',
    'SwedbankMOBYouth'       => 'swedbank_ung',
    'SavingbankMOBYouth'     => 'sparbanken_ung',
];

// Matching useragent (eg. SwedbankMOBCorporate/0.0.0_) for one of the apps mappings
$userAgentPattern = '#^([a-zA-Z]{16,22})(IOS|Android)/[0-9\.]+_#u';

if (empty($chljsFile))
{
    exit('Valid path to a .chljs file is required'.PHP_EOL);
}

if(!is_readable($chljsFile) OR !preg_match('/\.chlsj$/iu', $chljsFile))
{
    exit("'$chljsFile' dose not exists or is not readable".PHP_EOL);
}

if (!is_writable($appDataPath))
{
    exit("Either can't find AppData.json, or the file isn't writable".PHP_EOL);
}

$trafficSource = json_decode(file_get_contents($chljsFile));

$appDataSource = json_decode(file_get_contents($appDataPath), true);

$misses     = [];
$newAppData = $appDataSource;
foreach ($trafficSource as $i => $r)
{
    if (!isset($r->request->header->headers) OR !strpos($r->host, 'api.swedbank.se'))
    {
        continue;
    }

    $temp = ['appID'=>'','useragent'=>'',];
    foreach ($r->request->header->headers as $h)
    {
        switch ($h->name)
        {
            case "User-Agent":
                $temp['useragent'] = $h->value;
            break;
            case "Authorization":
                $decoded   = base64_decode($h->value);
                $temp['appID'] = explode(':', $decoded)[0];
            break;
            default:
                continue 2;
        }

        if (!$temp['appID'] OR !$temp['useragent'])
        {
            continue;
        }
        elseif (!preg_match($userAgentPattern, $temp['useragent'], $m))
        {
            continue 2;
        }

        $bankType = $appsMapping[$m[1] ?? ''] ?? null;

        if (isset($newAppData['apps'][$bankType]))
        {
            $newAppData['apps'][$bankType] = $temp;
            break;
        }

        $misses[] = $temp;
    }
}

if ($newAppData !== $appDataSource)
{
    $dt = new DateTime('NOW', new DateTimeZone('Europe/Stockholm'));

    $newAppData['meta']['timestamp'] = $dt->getTimestamp();
    $newAppData['meta']['updated']   = $dt->format(DateTime::ISO8601);

    if (file_put_contents($appDataPath, json_encode($newAppData, JSON_PRETTY_PRINT)))
    {
        echo "Success! appdata.json updated".PHP_EOL;
    }
    else
    {
        echo "Error: Update detected, but can't write to file".PHP_EOL;
    }
}
else
{
    echo "No difference sense {$newAppData['meta']['updated']} update".PHP_EOL;
}

//print_r($newAppData);