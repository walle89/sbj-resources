# TXT schema for [AppData.txt](../src/AppData.txt)

Based on the CSV file format with comments embedded at the top of the file.

Table data delimited by commas (,), quotation marks (") for encasements, lines separated by LF (\n) and escaped characters with a backslash (\).

| Attribute | Description                                                                       |
|-----------|-----------------------------------------------------------------------------------|
| updated   | Time and date when the file was last updated in ATOM-format (similar to ISO-8601) | 
| timestamp | Unix timestamp when the file was last updated                                     | 
| banktype  | Bank type                                                                         |
| appID     | ID from app used for authentication                                               |
| useragent | User-agent from app used for authentication                                       |

## Sample
```csv
#updated=2024-07-17T01:04:28+02:00
#timestamp=1721171068
banktype,appID,useragent
swedbank,dQNaxHjDjgA7Ga4T,"SwedbankMOBPrivateIOS/7.67.1_(iOS;_17.5)_Apple/iPad8,6"
sparbanken,pLjvflMfirASsNE4,"SavingbankMOBPrivateIOS/7.67.1_(iOS;_17.5)_Apple/iPad8,6"
```