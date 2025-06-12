> [!CAUTION]
> This project is not affiliated with, endorsed by, or associated with Swedbank, Sparbanken, or any of their subsidiaries.
> All trademarks and brand names are the property of their respective owners.
> Use of the information and resources provided in this repository is for educational and interoperability purposes only and may be subject to legal or policy restrictions.

# SBJ Resources

## What is AppData.json?
`AppData.json` is a metadata file used by the [SBJ client API](https://github.com/walle89/SwedbankJson) to authenticate requests to Swedbank’s mobile app API.

- [Download AppData.json](https://raw.githubusercontent.com/walle89/sbj-resources/master/src/AppData.json)
- [JSON schema specification](docs/appdata-json-schema.md)

More details in the [SBJ client documentation](https://github.com/walle89/SwedbankJson/blob/master/docs/appdata.md).

## What is AppData.txt?
Tabulated version of `AppData.json` in a CSV inspired format.

- [Download AppData.txt](https://raw.githubusercontent.com/walle89/sbj-resources/master/src/AppData.txt)
- [TXT schema specification](docs/appdata-txt-schema.md).

## Important notes
- Metadata contained in `AppData.json` and `AppData.txt` is reverse-engineered from Swedbank's mobile apps and usage may be subject to legal or policy restrictions.
- Use this data responsibly and only for intended educational or interoperability purposes.
- The data may change without notice as it depends on Swedbank’s mobile app updates.
- This repository does not provide any official support or guarantee for the continued functionality of Swedbank’s API.

## Documentation
See [docs/](docs/) folder.

## I have a project unrelated to SBJ client or SwedbankJson. Can I use AppData.json and AppData.txt?
Yes! This repository is licensed under the [MIT license](LICENSE), which permits reuse in other projects.

## Support for Swedbank- and Sparbankerna youth apps removed
As of November 2020, `SwedbankMOBYouthIOS` and `SavingbankMOBYouthIOS` user agents have been removed from this project.

These app clients have been marked as deprecated by Swedbank and will soon not be functional. Use `SwedbankMOBPrivateIOS` or `SavingbankMOBPrivateIOS` instead.