# JSON schema for [AppData.json](../src/AppData.json)

| Attribute | Description |
| --- | --- |
| meta->updated | Time and date when the file was last updated in ISO 8601 format | 
| meta->timestamp | Unix timestamp when the file was last updated | 
| apps->banktype | Bank type (eg. apps->swedbank) |
| apps->banktype->appID | ID from app used for authentication |
| apps->banktype->useragent | User-agent from app used for authentication |
 
 
```javascript
{
    "meta": {
        "updated": "2020-01-18T02:01:52+0100",
        "timestamp": 1579309312
    },
    "apps": {
        "swedbank": {
            "appID": "VFy3zkreQOdoi9wo",
            "useragent": "SwedbankMOBPrivateIOS/7.18.0_(iOS;_13.3)_Apple/iPhone10,6"
        }
    }
}
```

## What's the difference between "updated" and "timestamp"?
It's the same date but in two different formats. Timestamp is in many cases easier develop with without any need for a datetime library.
The "updated" attribute is there mainly for to have a human readable date time format. Can be also be used by a datetime library that supports ISO 8601 as input format.