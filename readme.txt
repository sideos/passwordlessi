=== PasswordleSSI ===
Contributors:      Sideos GmbH
Requires at least: 6.0
Tested up to:      6.1
Requires PHP:      5.6
Stable tag:        1.0.0
License:           GPLv2 or later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html
Tags:              passwordless, login, qrcode login, ssi, authentication

## _Passwordless login for Worpress powewred by Self Sovereign Identity and Sideos Gmbh_

[![N|Solid](https://sideos-publicimages.s3.eu-west-1.amazonaws.com/assets/powered.png)](https://sideos-publicimages.s3.eu-west-1.amazonaws.com/assets/powered.png)

This plugin allows to achieve a passwordless login for Worpdress using SSI as a decentralized technology. Sideos has deployed a proxy service for you to use with your Wordpress Instance. If you wish to use your own server, check the documentation on how to deploy your own SSI service integration. 

## Features

- Adds a QRCode on the login page to scan for log in
- Ability to send credentials to users, using their email as ID
- Ability to give credentials based on the domain e.g. email-domain is the key to allow login
- Ability to disable completely the username/password submission to avoid possible brute force attacks
- Ability to enable-disable username/password via http post secure call

## Installation

You need to do the following steps in order to have a functional passwordless login in your worpress instance powered by SSI & Sideos

- Create a free account on https://juno.sideos.io 
- Install the plugin in your worpress instance
- Copy from the Juno account the following values and save them in the settings SSI page:

| Item | Where |
| ------ | ------ |
| TOKEN | The TOKEN from the settings page in Juno |
| DID |The DID (Digital Identifier) from the settings page in Juno|
| Template ID| The Template ID you created following the steps in the documentation |

## Disable Username/Password submit

The plug in has a checkbox that when checked, disable the submission of the login page. This is useful to allow only login via the PasswordleSSI plugin. If you want to enable the feature back, you can use the REST API endpoint, and use the Juno Token as a header token to reset the status. After the call you can login again with Username/Password.

The CURL command is the following:  
``curl -d '{}' -H "X-Token: <YOUR TOKEN>" -H "Content-Type: application/json" -X POST <YOUR WEBSITE>/wp-json/sideos-ssi/v1/enable``
Replace <YOUR TOKEN> with the token you have in the options, and <YOUR WEBSITE> with the instance of your WordPress website.

## Frequently Asked Questions 

### What is the purpose of this plugin?

The primary purpose of the PasswordleSSI plugin is to allow authentication and access to the WordPress administration area without password. It uses a new technology, Self Sovereign Identity, or SSI, to perform the issuance and verification of credentials. 

### Can I use this plugin on my production site? 

Yes you can however it relies on a Proxy Server managed by Sideos GmbH. If you wish to create your own implementation of the service there is documentation on https://docs.sideos.io.

### Where can I submit my plugin feedback? 

Feedback is encouraged and much appreciated, especially since this plugin is a small use case of waht SSI is capable of. If you have suggestions or requests for new features, you can [submit them as an issue in the Sideos GitHub repository](https://github.com/sideos/wp-ssi-login). 

== Changelog ==

= 1.0.0 =

**Enhancements**

* First Release