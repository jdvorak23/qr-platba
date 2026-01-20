<?php

namespace Jdvorak23\QrPlatba\Properties\QRPlatba;

use Jdvorak23\QrPlatba\Enum\Convert;
use Jdvorak23\QrPlatba\Enum\Format;
use Jdvorak23\QrPlatba\Enum\Truncate;
use Jdvorak23\QrPlatba\Exceptions\QRFakturaException;
use Jdvorak23\QrPlatba\Properties\SpaydProperty;

class NotificationId extends SpaydProperty
{
	protected string $key = 'NTA';

	protected int $maxLength = 320;

	protected ?Phone $phone;

	protected ?Email $email;


	public function hasValue(): bool
	{
		return isset($this->phone) || isset($this->email);
	}

	/**
	 * @param null $value
	 * @return void
	 */
	public function setValue($value): void
	{
		$this->phone = null;
		$this->email = null;
	}

	public function setPhone(string $value)
	{
		$this->email = null;
		$this->phone = new Phone();
		$this->phone->setValue($value);
	}


	public function setEmail(string $value)
	{
		$this->phone = null;
		$this->email = new Email();
		$this->email->setValue($value);
	}


	public function getValue(Format &$format, Convert &$convert, Truncate &$truncate): string
	{
		if (isset($this->phone)) {
			return $this->phone->getValue($format, $convert, $truncate);
		} elseif (isset($this->email)) {
			// Big TODO KODKOVANI
			return $this->email->getValue($format, $convert, $truncate);
		}
		throw new QRFakturaException('Value is not set. Library exception');
	}
}