# TXT schema for [AppData.txt](../src/AppData.txt)

Based on the Comma-Separated Values (CSV) file format, which includes embedded comments at the file's beginning.

Table data is delimited by commas (,), quotation marks ("), and lines are separated by the line feed character (\n).

| Attribute | Description                                                                        |
|-----------|------------------------------------------------------------------------------------|
| updated   | The date and time the file was last modified in ATOM format (similar to ISO-8601)  | 
| timestamp | Unix timestamp when the file was last updated                                      | 
| banktype  | The type of bank application utilized for authentication purposes                  |
| appID     | Unique identifier assigned to the application utilized for authentication purposes |
| useragent | The user agent of the application used for authentication                          |

## Sample
```csv
#updated=2024-07-17T01:04:28+02:00
#timestamp=1721171068
banktype,appID,useragent
swedbank,dQNaxHjDjgA7Ga4T,"SwedbankMOBPrivateIOS/7.67.1_(iOS;_17.5)_Apple/iPad8,6"
sparbanken,pLjvflMfirASsNE4,"SavingbankMOBPrivateIOS/7.67.1_(iOS;_17.5)_Apple/iPad8,6"
```

> [!NOTE]  
> Swedbank and Sparbanken are registered trademarks of their respective owners.  
> This project is not affiliated with or endorsed by these organizations.