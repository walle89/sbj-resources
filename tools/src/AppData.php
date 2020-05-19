<?php

namespace Sbj\tools;


use DateTime;
use DateTimeZone;
use Exception;

class AppData
{
    /** @var array User agent mapping */
    const uaMapping = [
        'SwedbankMOBPrivate'     => 'swedbank',
        'SavingbankMOBPrivate'   => 'sparbanken',
        'SwedbankMOBCorporate'   => 'swedbank_foretag',
        'SavingbankMOBCorporate' => 'sparbanken_foretag',
        'SwedbankMOBYouth'       => 'swedbank_ung',
        'SavingbankMOBYouth'     => 'sparbanken_ung',
    ];

    /** @var string User agent match pattern */
    const userAgentPattern = '#^([a-zA-Z]{16,22})(IOS|Android)/[0-9\.]+_#u';

    /** @var string Full path to AppData.json */
    private $sourcePath;

    /** @var array JSON decoded AppData.json */
    private $sourceData;

    /** @var string AppData.json string data */
    private $jsonSource;

    public function __construct(string $sourcePath, string $jsonSource='')
    {
        $this->sourcePath = $sourcePath;
        $this->jsonSource = $jsonSource;
    }

    /**
     * AppData.json source file
     *
     * @return mixed
     */
    protected function getSourceData(): array
    {
        if ( !$this->sourceData )
        {
            $dataString = $this->jsonSource ?: file_get_contents($this->sourcePath);
            $this->sourceData = json_decode($dataString, true);
        }

        return $this->sourceData;
    }


    /**
     * Load .chljs file
     *
     * @param string $chljsFilePath
     *
     * @return mixed
     * @throws Exception
     */
    public function loadChljs( string $chljsFilePath )
    {
        if ( !is_readable($chljsFilePath) )
        {
            throw new Exception("'$chljsFilePath' dose not exists or is not readable");
        }

        return json_decode(file_get_contents($chljsFilePath));
    }

    /**
     * Extract traffic data
     *
     * @param object $rawChljs
     *
     * @return array
     */
    public function parserChljs($rawChljs): array
    {
        $misses     = [];
        $newAppData = [];
        foreach($rawChljs as $i => $r)
        {
            if ( !isset($r->request->header->headers) OR !strpos($r->host, 'api.swedbank.se') )
            {
                continue;
            }

            $temp = [ 'appID' => '', 'useragent' => '', ];
            foreach($r->request->header->headers as $h)
            {
                switch ($h->name)
                {
                    case "User-Agent":
                        $temp['useragent'] = $h->value;
                        break;
                    case "Authorization":
                        $decoded       = base64_decode($h->value);
                        $temp['appID'] = explode(':', $decoded)[0];
                        break;
                    default:
                        continue 2;
                }

                if ( !$temp['appID'] OR !$temp['useragent'] )
                {
                    continue;
                }
                elseif ( !preg_match(self::userAgentPattern, $temp['useragent'], $m) )
                {
                    continue 2;
                }

                $bankType = self::uaMapping[ $m[1] ?? '' ] ?? null;

                if(isset($bankType)) {
                    $newAppData[ $bankType ] = $temp;
                    break;
                }

                $misses[] = $temp;
            }
        }

        return $newAppData;
    }

    /**
     * Merge AppData source with imported traffic data
     *
     * @param array $importedAppData
     *
     * @return array
     * @throws Exception
     */
    public function merge(array $importedAppData): array
    {
        $appDataSource = $this->getSourceData();

        $tempNewApps = [];
        foreach($appDataSource['apps'] as $a => $d)
        {
            if ( isset($importedAppData[ $a ]) )
            {
                $d = $importedAppData[ $a ];
            }
            $tempNewApps[ $a ] = $d;
        }

        return $tempNewApps;
    }

    /**
     * Check if anything have changed
     *
     * @param array $newAppData
     *
     * @return bool
     */
    public function compare( array $newAppData ): bool
    {
        return $newAppData === $this->getSourceData()['apps'];
    }

    /**
     * Update AppData source
     *
     * @param array $appData
     *
     * @return bool
     * @throws Exception
     */
    public function updateSource( array $appData ): bool
    {
        $base               = $this->getSourceData();
        $newAppData         = $base;
        $newAppData['apps'] = $appData;

        try
        {
            $dt = new DateTime('NOW', new DateTimeZone('Europe/Stockholm'));
        }
        catch ( Exception $e )
        {
            throw new Exception('DateTime error');
        }

        $newAppData['meta']['timestamp'] = $dt->getTimestamp();
        $newAppData['meta']['updated']   = $dt->format(DateTime::ISO8601);

        if ( !file_put_contents($this->sourcePath, json_encode($newAppData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) )
        {
            throw new Exception('Update detected, but can\'t write to file');
        }

        return true;
    }
}