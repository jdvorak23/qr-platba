<?php

namespace Jdvorak23\QrFaktura\Properties\QRPlatba;

use Jdvorak23\QrFaktura\Enum\Convert;
use Jdvorak23\QrFaktura\Enum\Format;
use Jdvorak23\QrFaktura\Enum\Truncate;
use Jdvorak23\QrFaktura\Exceptions\QRFakturaException;
use Jdvorak23\QrFaktura\Properties\SpaydProperty;

class IbanBicAlts extends SpaydProperty
{
	public const ItemsDelimiter = ',';

	protected string $key = 'ALT-ACC';

	protected int $maxLength = 93;

	protected bool $required = false;

	/**
	 * @var IbanBic[]
	 */
	protected array $ibanBics = [];


	public function add(string $iban, ?string $bic): void
	{
		$ibanBic = new IbanBic();
		$ibanBic->setIban($iban);
		$ibanBic->setBic($bic);
		$this->ibanBics[] = $ibanBic;
	}


	public function hasValue(): bool
	{
		return ! empty($this->ibanBics);
	}


	public function getValue(Format &$format, Convert &$convert, Truncate &$truncate): string
	{
		$result = '';
		foreach ($this->ibanBics as $ibanBic) {
			if ($result === '') {
				$result = $ibanBic->getValue($format, $convert, $truncate);
				continue;
			}
			$newResult = $result . self::ItemsDelimiter . $ibanBic->getValue($format, $convert, $truncate);
			if (strlen($newResult) > $this->maxLength) {
				if ($truncate === Truncate::No()) {
					$class = static::class;
					throw new QRFakturaException("Value '$newResult' for property '$class' is above maximum limit of $this->maxLength characters.");
				}
				return $result;
			}

			$result = $newResult;
		}

		return $result;
	}
}