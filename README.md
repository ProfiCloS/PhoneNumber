[![GitHub version](https://badge.fury.io/gh/ProfiCloS%PhoneNumber.svg)](http://badge.fury.io/gh/ProfiCloS%PhoneNumber)
[![travis-ci.com](https://travis-ci.com/ProfiCloS/PhoneNumber.svg?branch=master)](https://travis-ci.com/ProfiCloS/PhoneNumber)
[![codecov.io](https://codecov.io/github/ProfiCloS/PhoneNumber/coverage.svg?branch=master)](https://codecov.io/github/ProfiCloS/PhoneNumber?branch=master)

# PhoneNumber validator & parser (CZ & SK & ...)

# Install with composer
```sh
$ composer require proficlos/phonenumber
```

# How to use
```php
use ProfiCloS\Tools\PhoneNumber;

// validation
PhoneNumber::isPhone('+420777666555'); // true
PhoneNumber::isPhone('777666555'); // true
PhoneNumber::isPhone('77766655'); // false

// parser
$phone = PhoneNumber::from('+420777666555');

$phone->setFormat( PhoneNumber::FORMAT_INTERNATIONAL );
echo $phone; // 00420777666555

$phone->setFormat( PhoneNumber::FORMAT_GSM );
echo $phone; // +420777666555

$phone->setFormat( PhoneNumber::FORMAT_CANONICAL );
echo $phone; // +420 777 666 555
```

# Buy us a coffee <3
[![Buy me a Coffee](https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=E8NK53NGKVDHS)

# Donate us <3
```
ETH: 0x7D771A56735500f76af15F589155BDC91613D4aB
UBIQ: 0xAC08C7B9F06EFb42a603d7222c359e0fF54e0a13
```

