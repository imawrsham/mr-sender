# send sms and fax

[![Latest Version on Packagist](https://img.shields.io/packagist/v/aksoftware/mr-sender-wrapper.svg?style=flat-square)](https://packagist.org/packages/aksoftware/mr-sender-wrapper)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/aksoftware/mr-sender-wrapper/run-tests?label=tests)](https://github.com/aksoftware/mr-sender-wrapper/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/aksoftware/mr-sender-wrapper.svg?style=flat-square)](https://packagist.org/packages/aksoftware/mr-sender-wrapper)


You can easily send SMS and fax using this package and your account information https://www.mr-sender.com.

## Installation

You can install the package via composer:

```bash
composer require aksoftware/mr-sender-wrapper
```

## Usage

``` php
use AKSoftware\MrSenderWrapper\Sms;
use AKSoftware\MrSenderWrapper\Fax;

// create object class with originator option
$aksoftwaresms = new Sms('<YOUR_USERNAME>', '<YOUR_PASSWORD>');

// set message and recipient with tracking their individual tracking number.

$send = $aksoftwaresms->sendTextSms('<YOUR_SMS_MESSAGE>', 'RECIPIENT_NUMBER'
);

// create object class with originator option
$aksoftwarefax = new Fax('<YOUR_USERNAME>', '<YOUR_PASSWORD>');
// set message and recipient with tracking their individual tracking number.

$send = $aksoftwarefax->sendHtmlFax('<YOUR_HTML_MESSAGE>', 'RECIPIENT_NUMBER'
);


```

## Credits

- [AK Software GmbH](https://ak-software.com/)

