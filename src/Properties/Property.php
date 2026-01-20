<?php

namespace Jdvorak23\QrFaktura\Properties;

use Jdvorak23\QrFaktura\Enum\Convert;
use Jdvorak23\QrFaktura\Enum\Format;
use Jdvorak23\QrFaktura\Enum\Truncate;
use Jdvorak23\QrFaktura\Exceptions\QRFakturaException;
use Jdvorak23\QrFaktura\Helpers\Helper;

class Property
{
	/**
	 * @var string|int|float|\DateTime|null
	 * null = není nastaveno
	 */
	protected $value = null;

	protected int $maxLength;

	protected string $format;

	protected bool $required = false;

	protected bool $allowsCustomString = false;


	/**
	 * @return bool
	 */
	public function hasValue(): bool
	{
		return isset($this->value);
	}


	/**
	 * @param string|int|float|\DateTime|null $value
	 * @return void
	 */
	public function setValue($value): void
	{
		$this->value = is_string($value) ? trim($value) : $value;
	}


	/**
	 * @return bool
	 */
	public function isRequired(): bool
	{
		return $this->required;
	}


	/**
	 * @param Format $format Format::Alphanumeric(), Format::Any()
	 * @param Convert $convert
	 * @param Truncate $truncate
	 * @return string
	 */
	public function getValue(Format &$format, Convert &$convert, Truncate &$truncate): string
	{
		if ( ! isset($this->value) ) {
			throw new QRFakturaException('Value is not set. Library exception');
		}

		$class = static::class;

		if ($this->value === '') {
			throw new QRFakturaException("Value '$this->value' for property '$class' is not valid. Empty string is not allowed.");
		}

		if ($this->value instanceof \DateTime) {
			$value = $this->value->format('Ymd');
		} else {
			$value = (string) $this->value;
		}

		if (isset($this->format)) {
			if (preg_match($this->format, $value) !== 1) {
				throw new QRFakturaException("Value '$value' for property '$class' is not valid. Format: '$this->format'.");
			}
		}

		if ($this->allowsCustomString) {
			if ($convert === Convert::None()) {
				if ($format === Format::Alphanumeric() || $format === Format::Any()) {
					if (Helper::isAlphanumeric($value)) {
						if (strlen($value) > $this->maxLength) {
							if ($truncate === Truncate::No()) {
								throw new QRFakturaException("Value '$value' for property '$class' is above maximum limit of $this->maxLength characters.");
							}
							$value = substr($value, 0, $this->maxLength);
						}
						return $value;
					} elseif ($format === Format::Alphanumeric()) {
						// Format není alphanumeric a striktně ho vyžadujeme bez konverze
						throw new QRFakturaException("Value '$value' for property '$class' is not valid for alphanumeric strict setup.'");
					}
					// Zde je Format::Any(), tato vlastnost už nemůže být alphanumeric, takže měníme formát pro další property
					$format = Format::Iso();
				}
				// Zde je $format === Format::Iso()
				if (Helper::isIso($value)) {
					if (mb_strlen($value) > $this->maxLength) {
						if ($truncate === Truncate::No()) {
							throw new QRFakturaException("Value '$value' for property '$class' is above maximum limit of $this->maxLength characters.");
						}
						$value = mb_substr($value, 0, $this->maxLength);
					}
					return $value;
				}
				throw new QRFakturaException("Value '$value' for property '$class' is not valid for ISO-8859-1 strict setup.'");
			} elseif ($convert === Convert::Alphanumeric()) {
				// Zde nás formát nezajímá, je jasné, že bude alphanumeric
				$value = Helper::convertToAlphanumeric($value);
				if (strlen($value) > $this->maxLength) {
					if ($truncate === Truncate::No()) {
						throw new QRFakturaException("Value '$value' for property '$class' is above maximum limit of $this->maxLength characters.");
					}
					$value = mb_substr($value, 0, $this->maxLength);
				}
				return $value;
			} elseif ($convert === Convert::Urlencode()) {
				if ($format === Format::Alphanumeric()) {
					$alphanumericValue = Helper::urlencodeAlphanumeric($value);
					if (strlen($alphanumericValue) > $this->maxLength) {
						if ($truncate === Truncate::No()) {
							throw new QRFakturaException("Value '$value' for property '$class' is above maximum limit of $this->maxLength characters when urlencoded.");
						}
						$alphanumericValue = Helper::urlencodeAlphanumeric($value, $this->maxLength);
					}

					return $alphanumericValue;
				} elseif ($format === Format::Any()) {
					$alphanumericValue = Helper::urlencodeAlphanumeric($value);
					if (strlen($alphanumericValue) <= $this->maxLength) {
						return $alphanumericValue;
					}
					$isoValue = Helper::urlencodeIso($value);
					if (mb_strlen($isoValue) > $this->maxLength) {
						// Alpanumeric i Iso jsou delší
						if ($truncate === Truncate::No()) {
							throw new QRFakturaException("Value '$isoValue' for property '$class' is above maximum limit of $this->maxLength characters.");
						}
						$truncatedAlphanumericValue = Helper::urlencodeAlphanumeric($value, $this->maxLength);
						$truncatedIsoValue = Helper::urlencodeIso($value, $this->maxLength);
						if (mb_strlen(urldecode($truncatedAlphanumericValue)) === mb_strlen(urldecode($truncatedIsoValue))) {
							// Truncated řetězce jsou stejné
							return $truncatedAlphanumericValue;
						}
						// Zde víme, že do ISO se vešlo víc znaků, takže přecházíme na Format::Iso()
						$format = Format::Iso();
					} else {
						// Alpanumeric byla přes maxLength, ISO nikoli => přecházíme na Format::Iso()
						$format = Format::Iso();
					}
				}
				// Zde je $format === Format::Iso()
				$isoValue ??= Helper::urlencodeIso($value, $this->maxLength);
				if (mb_strlen($isoValue) > $this->maxLength) {
					if ($truncate === Truncate::No()) {
						throw new QRFakturaException("Value '$isoValue' for property '$class' is above maximum limit of $this->maxLength characters.");
					}
					$isoValue = Helper::urlencodeAlphanumeric($value, $this->maxLength);
				}
				return $isoValue;
			}
		}

		return $value;
	}

}