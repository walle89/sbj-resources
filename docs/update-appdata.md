# Update appdata.json

Note: Work in progress.

Apps that need to be installed:
* [Swedbank](https://apps.apple.com/us/app/swedbank-private/id344161302)
* [Sparbanken](https://apps.apple.com/th/app/savings-bank-private/id526657154)
* [Swedbank Coperate](https://apps.apple.com/us/app/swedbank-f%C3%B6retag/id606226381)
* [Sparbanken Coperate](https://apps.apple.com/th/app/sparbanken-f%C3%B6retag/id606776716)

## Method 1 - MacOS with Apple Silicon
If you have a mac with Apple Silicon, you don't need an ios device to capture HTTPS traffic. This method is both relatively easy to setup and it's possible to use Charles Proxy Desktop for free during the trail period.

### Requirements
* Mac with Apple Silicon chip (non-Intel mac from 2020 and later).
* Download the Swedbank and Sparbanken Ios apps from the Mac App Store.
* Have [Charles for desktop](https://www.charlesproxy.com/download/) installed and [setup SSL proxy Macos (not ios)](https://www.charlesproxy.com/documentation/using-charles/ssl-certificates/).
* Include `https://auth.api.swedbank.se` in SSL Proxying settings in Charles.

### Step by step
1. Open Charles Proxy.
2. Start recording, make sure that `Enable macOS proxy` is enabled.
3. Open each of the Swedbank and Sparbanken apps. Just load the app, no interaction required.
4. Stop recording.
5. In menu: File | Export session..., save it as a `.chlsj` file.
6. Run `php tools/updateAppData.php your/path/to/charles/proxy/file.chlsj`.
7. Git commit and ask for a pull request.

## Method 2 - Charles Proxy for Ios
Charles Proxy for Ios have made it quite easy to setup and record on device HTTPS sessions, and is a cheaper option than purchase a licence for Charles Proxy for desktop.

### Requirements
* Any ios device (eg. Iphone and Ipad) that [can run Swedbank and Sparbanken apps](https://www.swedbank.se/share/layer-content/privat/digitala-tjanster/vara-appar/for-privatpersoner/detta-kravs-for-att-ladda-ner-appen.html).
* Have [Charles proxy for ios](https://apps.apple.com/app/charles-proxy/id1134218562) installed and [setup listen on HTTPS traffic](https://www.charlesproxy.com/documentation/ios/getting-started-1/).
* Include `https://auth.api.swedbank.se` in SSL Proxying settings in Charles.

### Step by step
1. Open Charles.
2. Include `https://auth.api.swedbank.se` in SSL Proxying settings.
3. Start recording
4. Open each of the Swedbank and Sparbanken apps. Just load the app, no interaction required.
5. Open Charles again and stop recording.
6. Export the recoding by send/share the recording. It should result in a .chlsj file.
7. Download the .chlsj file to a computer and place it in the tools/ folder.
8. Run `php tools/updateAppData.php tools/yourSessionSample.chlsj`.
9. Git commit and ask for a pull request.

## Method 3 - Ios device + Charles for desktop
Run the Swedbank and Sparbanken apps on ios and record the session on a Windows, Mac or Linux device.
It can be a bit tricky to setup in some cases, but otherwise a reliable method to listen on HTTPS traffic.  

### Requirements
* Any ios device (eg. Iphone and Ipad) that [can run Swedbank and Sparbanken apps](https://www.swedbank.se/share/layer-content/privat/digitala-tjanster/vara-appar/for-privatpersoner/detta-kravs-for-att-ladda-ner-appen.html).
* Have [Charels for desktop](https://www.charlesproxy.com/download/) installed and [setup Ios to listen on HTTPS traffic](https://help.testlio.com/en/articles/1144391-charles-proxy-guide-for-ios).
* Have both the Ios device and desktop connected to the same (Wifi) network.
* Include `https://auth.api.swedbank.se` in SSL Proxying settings in Charles.

### Step by step
1. Open Charles Proxy.
3. Open Wifi settings on ios device and configure manual proxy (eg. 192.168.1.149, port 8888).
4. Start recording.
5. Check if any traffic comes in from the device.
6. Open each of the Swedbank and Sparbanken apps on the ios device. Just load the app, no interaction required.
7. Stop recording.
8. In menu: File | Export session..., save it as a `.chlsj` file.
9. Run `php tools/updateAppData.php your/path/to/charles/proxy/file.chlsj`.
10. Git commit and ask for a pull request.