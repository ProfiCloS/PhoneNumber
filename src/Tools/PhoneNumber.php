<?php
namespace ProfiCloS\Tools;

/**
 * Class PhoneNumber
 * @package PYSys\Tools
 */
class PhoneNumber
{

	/**
	 * Countries
	 */
	const COUNTRY_CS = 'cs';
	const COUNTRY_SK = 'sk';
	const COUNTRY_PL = 'pl';
	const COUNTRY_HU = 'hu';
	protected static $countries = [
		self::COUNTRY_CS,
		self::COUNTRY_SK,
		self::COUNTRY_PL,
		self::COUNTRY_HU,
    ];
	protected static $countryAreas = [
		self::COUNTRY_CS => '00420',
		self::COUNTRY_SK => '00421',
		self::COUNTRY_PL => '0048',
		self::COUNTRY_HU => '0036',
    ];

	/**
	 * Formats
	 */
	const FORMAT_INTERNATIONAL = 0;	// 00420123456789
	const FORMAT_GSM = 1;			// +420123456789
	const FORMAT_FULL = 2;			// +420 123 456 789
	const FORMAT_CANONICAL = 3;		// +420 123 456 789
	const FORMAT_STRAIGHT = 4;		// 123456789
	protected static $formats = [
		self::FORMAT_INTERNATIONAL,
		self::FORMAT_GSM,
		self::FORMAT_FULL,
		self::FORMAT_CANONICAL,
		self::FORMAT_STRAIGHT
    ];


	/**
	 * Country Regulars
	 */
	protected static $formatReg = [
		self::COUNTRY_CS => '~^((^\+|^00)420)*[0-9]{9}$~', // u česka může být bez předvolby
		self::COUNTRY_SK => '~^((^\+|^00)421)[0-9]{9}$~', 	// ostatní jen s předvolbou
		self::COUNTRY_PL => '~^((^\+|^00)48)[0-9]{9}$~', 	// ostatní jen s předvolbou
		self::COUNTRY_HU => '~^((^\+|^00)36)[0-9]{8}$~', 	// ostatní jen s předvolbou
    ];
	protected static $areaReg = [
		self::COUNTRY_CS => '~^\+420|^00420~',
		self::COUNTRY_SK => '~^\+421|^00421~',
		self::COUNTRY_PL => '~^\+48|^0048~',
		self::COUNTRY_HU => '~^\+36|^0036~',
    ];

	/**
	 * Messages
	 */
	protected static $messages = [
		'NOT_SUPPORTED' => 'Class PhoneNumber not support country ',
		'BAD_NUMBER' => 'Passed phone number is not valid ',
    ];

	/**
	 * Class settings
	 */
	protected $country = self::COUNTRY_CS;
	protected $number;
	protected $areaCode;
	protected $format = self::FORMAT_GSM;

	/**
	 * @param $phoneNumber
	 * @param string $country
	 * @return PhoneNumber
	 */
	public static function from( $phoneNumber, $country = null)
	{
		$obj = new static;
		$obj->setCountry($country);
		$obj->setNumber(str_replace(' ', '', $phoneNumber));
		return $obj;
	}

	/**
	 * @return bool
	 */
	public function isValid() {
		return (bool) preg_match(self::$formatReg[$this->country],$this->__toString());
	}

    /**
     * @param $phone
     * @param string $country
     * @return bool
     * @throws PhoneNumberException
     */
	public static function isPhone( $phone, $country = null) {
		$phone = str_replace(' ', '', $phone);
		if(!self::isCountrySupported($country)) {
			throw new PhoneNumberException(self::$messages['NOT_SUPPORTED'] . "'{$country}'");
		}
		if($country) {
			return (bool) preg_match(self::$formatReg[$country],$phone);
		}

        $valid = false;
        foreach(self::$countries as $supportedCountry) {
            if((bool) preg_match(self::$formatReg[$supportedCountry], $phone)) {
                $valid = true;
                break;
            }
        }
        return $valid;
	}

    /**
     * @param $country
     * @throws PhoneNumberException
     */
	public function setCountry($country) {
		if(!self::isCountrySupported($country)) {
			throw new PhoneNumberException(self::$messages['NOT_SUPPORTED'] . "'{$country}'");
		}
		$this->country = $country;
	}

	/**
	 * @param $country
	 * @return bool
	 */
	public static function isCountrySupported($country) {
        return !( $country !== null && !in_array( $country, self::$countries, true ) );
	}

    /**
     * @param $number
     * @throws PhoneNumberException
     */
	public function setNumber($number) {
		if(!self::isPhone($number)) {
			throw new PhoneNumberException(self::$messages['BAD_NUMBER']);
		}
		$parts = $this->parseNumber($number);
		$this->setAreaPart($parts['area']);
		$this->setNumberPart($parts['number']);
	}

	/**
	 * @param $area
	 */
	public function setAreaPart($area) {
		$area = str_replace('+','00',$area);
		$this->areaCode = $area;
	}

	/**
	 * @param $number
	 */
	public function setNumberPart($number) {
		$this->number = $number;
	}

	public function tryGetCountryFromNumber($number) {
		foreach(self::$countries as $country) {
			if((bool) preg_match(self::$formatReg[$country],$number)) {
				return $country;
			}
		}
		return false;
	}

    /**
     * @param $number
     * @return array
     */
	protected function parseNumber($number) {
		$parts = array();

		if(!$this->country) {
			$this->country = $this->tryGetCountryFromNumber($number);
		}

		if (preg_match(self::$areaReg[$this->country], $number, $matches)) {
			$parts['area'] = $matches[0];
			$parts['number'] = str_replace( [ $matches[0], ' ' ], '', $number);
		} else {
			$parts['area'] = self::$countryAreas[$this->country];
			$parts['number'] = str_replace(' ', '', $number);
		}

		return $parts;
	}

	/**
	 * @return string
	 */
	public function getAreaPart() {
		return $this->areaCode;
	}

	/**
	 * @return string
	 */
	public function getNumberPart() {
		return $this->number;
	}

	/**
	 * @return string
	 */
	public function getNumberPartWithSpaces() {
		$number_part = $this->getNumberPart();

		if($this->country === self::COUNTRY_CS) {
			$number_part = number_format($number_part, 0, '', ' ');
		} elseif($this->country === self::COUNTRY_SK) {
			$number_part = number_format($number_part, 0, '', ' ');
		} elseif($this->country === self::COUNTRY_PL) {
			$number_part = mb_substr($number_part, 0, 2) . ' ' . mb_substr($number_part, 2, 3) . ' ' . mb_substr($number_part, 5, 2) . ' ' . mb_substr($number_part, 7, 2);
		} elseif($this->country === self::COUNTRY_HU) {
			$number_part = mb_substr($number_part, 0, 1) . ' ' . mb_substr($number_part, 1, 3) . ' ' . mb_substr($number_part, 4, 4);
		}

		return $number_part;
	}

    /**
     * @param $format
     * @throws PhoneNumberException
     */
	public function setFormat($format) {
		if(!in_array( $format, self::$formats, true ) ) {
			throw new PhoneNumberException( 'Use format from class' );
		}
		$this->format = $format;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		switch ($this->format) {
			case self::FORMAT_INTERNATIONAL:
			    return $this->getAreaPart() . $this->getNumberPart();
			case self::FORMAT_GSM:
			    return str_replace( '00', '+',$this->getAreaPart()) . $this->getNumberPart();
			case self::FORMAT_FULL:
			    return str_replace( '00', '+',$this->getAreaPart()) . ' ' . $this->getNumberPartWithSpaces();
			case self::FORMAT_CANONICAL:
			    return str_replace( '00', '+',$this->getAreaPart()) . ' ' . $this->getNumberPartWithSpaces();
			case self::FORMAT_STRAIGHT:
			    return $this->getNumberPart();
			default:
			    return '';
		}
	}

}