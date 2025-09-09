> [!CAUTION]
> This guide describes a reverse engineering process related to Swedbank and Sparbanken mobile apps.
> This project is not affiliated with, endorsed by, or associated with Swedbank, Sparbanken, or their subsidiaries.
> Use of the described methods involves capturing and analyzing network traffic from proprietary applications and may be subject to legal or policy restrictions in your
jurisdiction.
> Provided for educational and interoperability purposes only.

# Update AppData

This guide provides step-by-step instructions on extracting metadata from Swedbank applications and subsequently updating AppData.json and AppData.txt. The primary objective of
this guide is to document the process of updating these files.

For users seeking pre-updated files that can be immediately utilized, a detailed description is provided in [README.md](../README.md).

## Prerequisites

Required Apps:

* [Swedbank](https://apps.apple.com/us/app/swedbank-private/id344161302)
* [Sparbanken](https://apps.apple.com/th/app/savings-bank-private/id526657154)
* [Swedbank Corporate](https://apps.apple.com/us/app/swedbank-f%C3%B6retag/id606226381)
* [Sparbanken Corporate](https://apps.apple.com/th/app/sparbanken-f%C3%B6retag/id606776716)

## Method 1 - Charles Proxy for iOS

Charles Proxy for iOS has made it quite easy to set up and record on-device HTTPS sessions, and is a cheaper option than purchasing a license for Charles Proxy for desktop.

### Requirements

* Any iOS device, including iPhones and iPads, that is capable
  of [running the Swedbank and Sparbanken apps](https://www.swedbank.se/share/layer-content/privat/digitala-tjanster/vara-appar/for-privatpersoner/detta-kravs-for-att-ladda-ner-appen.html).
* Have [Charles Proxy for iOS](https://apps.apple.com/app/charles-proxy/id1134218562) installed.
  and [set up listen on HTTPS traffic](https://www.charlesproxy.com/documentation/ios/getting-started-1/).
* Include `https://auth.api.swedbank.se` in SSL Proxying settings in Charles.
* PHP 8.1 or newer is required to run `tools/updateAppData.php`.

### Step by step

1. Open Charles.
2. Include `https://auth.api.swedbank.se` in SSL Proxying settings.
3. Start recording.
4. Open each of the Swedbank and Sparbanken apps. Just load the app, no interaction required.
5. Open Charles again and stop recording.
6. Export the recording by sending/sharing the recording. It should result in a .chlsj file.
7. Download the .chlsj file to a computer and place it in the tools/ folder.
8. Run `php tools/updateAppData.php tools/yourSessionSample.chlsj`.
9. Git commit and ask for a pull request.

## Method 2 - iOS device + Charles for desktop

Run the Swedbank and Sparbanken apps on iOS and record the session on a Windows, Mac, or Linux device.
It can be a bit tricky to set up in some cases, but otherwise a reliable method to listen on HTTPS traffic.

### Requirements

* Any iOS device, including iPhones and iPads, that is capable
  of [running the Swedbank and Sparbanken apps](https://www.swedbank.se/share/layer-content/privat/digitala-tjanster/vara-appar/for-privatpersoner/detta-kravs-for-att-ladda-ner-appen.html).
* Have [Charles for desktop](https://www.charlesproxy.com/download/) installed.
  and [set up iOS to listen on HTTPS traffic](https://help.testlio.com/en/articles/1144391-charles-proxy-guide-for-ios).
* Have both the iOS device and desktop connected to the same (Wi-Fi) network.
* Include `https://auth.api.swedbank.se` in SSL Proxying settings in Charles.
* PHP 8.1 or newer is required to run `tools/updateAppData.php`.

### Step by step

1. Open Charles Proxy.
2. Open Wi-Fi settings on the iOS device and configure manual proxy (eg. 192.168.1.149, port 8888).
3. Start recording.
4. Check if any traffic comes in from the device.
5. Open each of the Swedbank and Sparbanken apps on the iOS device. Just load the app, no interaction required.
6. Stop recording.
7. In the menu: File | Export session..., save it as a `.chlsj` file.
8. Run `php tools/updateAppData.php your/path/to/charles/proxy/file.chlsj`.
9. Git commit and ask for a pull request.

## Method 3 - macOS with Apple Silicon (Discontinued)

> [!IMPORTANT]
> As of November 2024, Swedbank no longer indicates that its applications are compatible with macOS in the App Store. Consequently, this method is no longer viable. These
> instructions are retained for historical reference purposes only.

If you have a Mac with Apple Silicon, you don't need an iOS device to capture HTTPS traffic. This method is both relatively easy to set up and it's possible to use Charles Proxy
Desktop for free during the trial period.

### Requirements

* Mac with Apple Silicon chip (non-Intel Mac from 2020 and later).
* Download the Swedbank and Sparbanken iOS apps from the Mac App Store.
* Have [Charles for desktop](https://www.charlesproxy.com/download/) installed
  and [setup SSL proxy MacOS (not iOS)](https://www.charlesproxy.com/documentation/using-charles/ssl-certificates/).
* Include `https://auth.api.swedbank.se` in SSL Proxying settings in Charles.
* PHP 8.1 or newer is required to run `tools/updateAppData.php`.

### Step by step

1. Open Charles Proxy.
2. Start recording, make sure that `Enable macOS proxy` is enabled.
3. Open each of the Swedbank and Sparbanken apps. Just load the app, no interaction required.
4. Stop recording.
5. In the menu: File | Export session..., save it as a `.chlsj` file.
6. Run `php tools/updateAppData.php your/path/to/charles/proxy/file.chlsj`.
7. Git commit and ask for a pull request.