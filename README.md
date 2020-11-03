# SBJ Resources

## What is AppData.json and what is it used for?
[AppData.json](src/AppData.json) is a file used by the [SwedbankJson client API](https://github.com/walle89/SwedbankJson) project to automatically download the latest Swedbank mobile app metadata.
This data is used in the authentication process with the Swedbank mobile app API. More information can be found in the [SwedbankJson documentation](https://github.com/walle89/SwedbankJson/blob/master/docs/appdata.md).

You can download the file with this URL: https://raw.githubusercontent.com/walle89/sbj-resources/master/src/AppData.json

## Documentation
See [docs/](docs/) folder.

## I have a project not related to SwedbankJson, can I use AppData.json?
Yes! This repo is under [MIT license](LICENSE).

## Support for Swedbank- and Sparbankerna youth apps removed
As of November 2020, `SwedbankMOBYouthIOS` and  `SavingbankMOBYouthIOS` user agents have been removed from this project.

These app clients have been marked as deprecated by Swedbank and will soon not be functional. Use `SwedbankMOBPrivateIOS` or `SavingbankMOBPrivateIOS` instead.