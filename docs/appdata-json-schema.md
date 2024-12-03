# JSON schema for [AppData.json](../src/AppData.json)

| Attribute                 | Description                                                                        |
|---------------------------|------------------------------------------------------------------------------------|
| meta->updated             | The date and time the file was last modified in ATOM format (similar to ISO-8601)  | 
| meta->timestamp           | Unix timestamp when the file was last updated                                      | 
| apps->banktype            | The type of bank application utilized for authentication purposes                  |
| apps->banktype->appID     | Unique identifier assigned to the application utilized for authentication purposes |
| apps->banktype->useragent | The user agent of the application used for authentication                          |
 
## Sample 
```javascript
{
    "meta": {
        "updated": "2020-01-18T02:01:52+01:00",
        "timestamp": 1579309312
    },
    "apps": {
        "swedbank": {
            "appID": "VFy3zkreQOdoi9wo",
            "useragent": "SwedbankMOBPrivateIOS/7.18.0_(iOS;_13.3)_Apple/iPhone10,6"
        },
        "sparbanken": {
            "appID": "pLjvflMfirASsNE4",
            "useragent": "SavingbankMOBPrivateIOS/7.18.0_(iOS;_13.3)_Apple/iPhone10,6"
        }
    }
}
```