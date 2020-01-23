# SBJ resources

## What is AppData.json and what is it used for?
[AppData.json](src/AppData.json) is a file used by the [SwedbankJson client API](https://github.com/walle89/SwedbankJson) project to automatically download the latest Swedbank mobile app meta data.
This data is used in the authentication process with the Swedbank mobile app API. More information can be found in the [SwedbankJson documentation](https://github.com/walle89/SwedbankJson/blob/master/docs/appdata.md).

You can download the file with this URL: https://raw.githubusercontent.com/walle89/sbj-resources/src/AppData.json

## JSON schema for AppData.json
```javascript
{
   "meta": {
       "updated": "2014-01-01T00:00:00+0100", // ISO 8601
       "timestamp": 1388530800 // Attribute "updated" in Unix timestamp format
   },
   "apps": {
     "swedbank": { // Bank app string
       "appID": "cQuk7Rp8k7f4xZFo", // ID string
       "useragent": "SwedbankMOBPrivateIOS..." // User agent string
     }
  }
}
```

## I have a project not related to SwedbankJson, can I use AppData.json?
Yes! This repo is under [MIT license](LICENSE).