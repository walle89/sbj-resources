<?php

namespace Sbj\tools;


use DateTime;
use DateTimeInterface;
use DateTimeZone;
use Exception;
use JsonException;

class AppData
{
    /** @var array User agent mapping */
    const uaMapping = [
        'SwedbankMOBPrivate'     => 'swedbank',
        'SavingbankMOBPrivate'   => 'sparbanken',
        'SwedbankMOBCorporate'   => 'swedbank_foretag',
        'SavingbankMOBCorporate' => 'sparbanken_foretag',
    ];

    /** @var string User agent match pattern */
    const userAgentPattern = '#^([a-zA-Z]{16,22})(IOS|Android)/[0-9.]+_#u';

    /** @var string API endpoint domain name */
    const apiEndpoint = 'api.swedbank.se';

    /** @var string[] Txt Data format settings */
    const txtDataFormatSettings = [
        'separator' => ',',
        'enclosure' => '"',
        'escape' => '',
        'eol' => "\n",
    ];

    /** @var array JSON decoded AppData.json */
    private array $sourceData;

    public function __construct(
        private readonly string $sourcePathJson,
        private readonly string $sourcePathTxt,
        private readonly string $jsonSource = '',
    )
    {
    }

    /**
     * AppData.json source file
     *
     * @return mixed
     * @throws JsonException
     */
    protected function getSourceData(): array
    {
        if ( empty($this->sourceData) )
        {
            $dataString       = $this->jsonSource ?: file_get_contents($this->sourcePathJson);
            $this->sourceData = json_decode(json: $dataString, associative: true, flags: JSON_THROW_ON_ERROR);
        }

        return $this->sourceData;
    }

    /**
     * Load .chljs file
     *
     * @param string $chljsFilePath
     *
     * @return mixed
     * @throws AppDataException
     */
    public function loadChljs( string $chljsFilePath )
    {
        if ( !is_readable($chljsFilePath) )
        {
            throw new AppDataException("The .chljs file '{$chljsFilePath}' does not exist or is not readable");
        }

        try
        {
            return json_decode(json: file_get_contents($chljsFilePath), flags: JSON_THROW_ON_ERROR);
        } catch (JsonException $e)
        {
            throw new AppDataException("Malformed .chljs file '{$chljsFilePath}', unable to parse", $e->getCode(), $e);
        }
    }

    /**
     * Extract traffic data
     *
     * @param object $rawChljs
     *
     * @return array
     * @throws AppDataException
     */
    public function parserChljs($rawChljs): array
    {
        $misses     = [];
        $newAppData = [];
        foreach($rawChljs as $i => $r)
        {
            if ( !isset($r->request->header->headers) OR !str_contains($r->host, self::apiEndpoint))
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

        if (empty($newAppData))
        {
            throw new AppDataException("The HTTP dump does not contain any transactions to '".self::apiEndpoint."'. Please make sure the dump includes traffic to this endpoint");
        }

        return $newAppData;
    }

    /**
     * Merge AppData source with imported traffic data
     *
     * @param array $importedAppData
     *
     * @return array
     * @throws JsonException
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
     * Check if app metadata have changed
     *
     * @param array $newAppData
     *
     * @return bool Returns true if new app metadata is identical to current app metadata
     * @throws JsonException
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
     * @throws AppDataException
     * @throws Exception
     */
    public function updateSource( array $appData ): bool
    {
        $base               = $this->getSourceData();
        $newAppData         = $base;
        $newAppData['apps'] = $appData;

        $dt = new DateTime('NOW', new DateTimeZone('Europe/Stockholm'));
        $newAppData['meta']['timestamp'] = $dt->getTimestamp();
        $newAppData['meta']['updated']   = $dt->format(DateTimeInterface::ATOM);

        if ( !file_put_contents($this->sourcePathJson, json_encode($newAppData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) )
        {
            throw new Exception('Update detected, but can\'t write to '.$this->sourcePathJson);
        }

        // TxtData format
        $fp = fopen($this->sourcePathTxt, 'w');
        $fields = ['banktype', 'appID', 'useragent'];

        if (fputcsv($fp, $fields, ...self::txtDataFormatSettings) === false) {
            throw new Exception('Update detected, but can\'t write to '.$this->sourcePathTxt);
        }

        foreach ($appData as $bankType => $r) {
            $tmpFields = [$bankType, $r['appID'], $r['useragent']];
            fputcsv($fp, $tmpFields, ...self::txtDataFormatSettings);
        }

        fclose($fp);

        $tempTxt = file_get_contents($this->sourcePathTxt);
        $txtData =
            "#updated={$newAppData['meta']['updated']}\n".
            "#timestamp={$newAppData['meta']['timestamp']}\n".
            $tempTxt;

        if (!file_put_contents($this->sourcePathTxt, $txtData))
        {
            throw new Exception('Update detected, but can\'t write to '.$this->sourcePathTxt);
        }

        return true;
    }
}