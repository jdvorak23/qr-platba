<?php

namespace Jdvorak23\QrPlatba\Properties\QRPlatba;

use Jdvorak23\QrPlatba\Enum\Convert;
use Jdvorak23\QrPlatba\Enum\Format;
use Jdvorak23\QrPlatba\Enum\Truncate;
use Jdvorak23\QrPlatba\Properties\SpaydProperty;

class IbanBic extends SpaydProperty
{
	public const IbanBicDelimiter = '+';

	protected string $key = 'ACC';

	protected int $maxLength = 46;

	protected bool $required = true;

	protected Iban $iban;

	protected Bic $bic;


	public function __construct()
	{
		$this->iban = new Iban();
		$this->bic = new Bic();
	}


	public function setIban(?string $iban)
	{
		$this->iban->setValue($iban);
	}


	public function setBic(?string $bic)
	{
		$this->bic->setValue($bic);
	}


	public function hasValue(): bool
	{
		// V tomto případě nás zajímá IBAN
		return $this->iban->hasValue();
	}


	public function getValue(Format &$format, Convert &$convert, Truncate &$truncate): string
	{
		$iban = $this->iban->getValue($format, $convert, $truncate);
		return $this->bic->hasValue()
			? $iban . self::IbanBicDelimiter . $this->bic->getValue($format, $convert, $truncate)
			: $iban;
	}
}