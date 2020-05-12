# Update appdata.json

1. Run Charles proxy, open updated Swedbank or sparbanken app(s) and export as .chlsj file
1. Run `php tools/updateAppData.php path/to/charles/proxy/file.chlsj`