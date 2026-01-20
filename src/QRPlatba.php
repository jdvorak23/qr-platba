<?php

namespace Jdvorak23\QrFaktura;

use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelMedium;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeShrink;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\Result\PngResult;
use Jdvorak23\QrFaktura\Enum\Convert;
use Jdvorak23\QrFaktura\Enum\Format;
use Jdvorak23\QrFaktura\Enum\Truncate;
use Jdvorak23\QrFaktura\Exceptions\QRFakturaException;
use Jdvorak23\QrFaktura\Properties\QRPlatba\Amount;
use Jdvorak23\QrFaktura\Properties\QRPlatba\ConstantSymbol;
use Jdvorak23\QrFaktura\Properties\QRPlatba\Currency;
use Jdvorak23\QrFaktura\Properties\QRPlatba\DueDate;
use Jdvorak23\QrFaktura\Properties\QRPlatba\IbanBic;
use Jdvorak23\QrFaktura\Properties\QRPlatba\IbanBicAlts;
use Jdvorak23\QrFaktura\Properties\QRPlatba\Message;
use Jdvorak23\QrFaktura\Properties\QRPlatba\NotificationId;
use Jdvorak23\QrFaktura\Properties\QRPlatba\NotificationMethod;
use Jdvorak23\QrFaktura\Properties\QRPlatba\PayeeIdentifier;
use Jdvorak23\QrFaktura\Properties\QRPlatba\PayeeName;
use Jdvorak23\QrFaktura\Properties\QRPlatba\PayerIdentifier;
use Jdvorak23\QrFaktura\Properties\QRPlatba\PaymentType;
use Jdvorak23\QrFaktura\Properties\QRPlatba\RepeatPayment;
use Jdvorak23\QrFaktura\Properties\QRPlatba\SpecificSymbol;
use Jdvorak23\QrFaktura\Properties\QRPlatba\Url;
use Jdvorak23\QrFaktura\Properties\QRPlatba\VariableSymbol;
use Jdvorak23\QrFaktura\Properties\SpaydProperty;

class QRPlatba
{
	/**
	 * Znak '*' pro uživatelské stringy není povolen
	 */
	public const AlphanumericChars = '0-9A-Z $%+\-.\/:';

	public const Version = '1.0';

	public const DefaultSize = 150;

	protected Format $format;

	protected Convert $convert;

	protected Truncate $truncate;

	/*
	 * Properties použitelné pro všechny platby
	 */

	protected IbanBic $ibanBic;
	protected IbanBicAlts $ibanBicAlts;
	protected Amount $amount;
	protected Currency $currency;
	protected PayeeIdentifier $payeeIdentifier;
	protected PayeeName $payeeName;
	protected DueDate $dueDate;
	protected PaymentType $paymentType;
	protected Message $message;
	protected NotificationMethod $notificationMethod;
	protected NotificationId $notificationId;

	/*
	 * Properties použitelné pouze pro tuzemský bankovní styk
	 */

	protected RepeatPayment $repeatPayment;
	protected VariableSymbol $variableSymbol;
	protected SpecificSymbol $specificSymbol;
	protected ConstantSymbol $constantSymbol;
	protected PayerIdentifier $payerIdentifier;
	protected Url $url;

	/**
	 * @var SpaydProperty[]
	 */
	protected array $properties = [];


	public function __construct()
	{
		$this->format = Format::Alphanumeric();
		$this->convert = Convert::Alphanumeric();
		$this->truncate = Truncate::Yes();

		$this->properties = [
			$this->ibanBic = new IbanBic(),
			$this->ibanBicAlts = new IbanBicAlts(),
			$this->amount = new Amount(),
			$this->currency = new Currency(),
			$this->payeeIdentifier = new PayeeIdentifier(),
			$this->payeeName = new PayeeName(),
			$this->dueDate = new DueDate(),
			$this->paymentType = new PaymentType(),
			$this->message = new Message(),
			$this->notificationMethod = new NotificationMethod(),
			$this->notificationId = new NotificationId(),

			$this->repeatPayment = new RepeatPayment(),
			$this->variableSymbol = new VariableSymbol(),
			$this->specificSymbol = new SpecificSymbol(),
			$this->constantSymbol = new ConstantSymbol(),
			$this->payerIdentifier = new PayerIdentifier(),
			$this->url = new Url(),
		];
	}


	public function getSpayd(): string
	{
		$format = $this->format;
		$convert = $this->convert;
		$truncate = $this->truncate;
		$properties = [];
		foreach ($this->properties as $property) {
			if ( ! $property->hasValue()) {
				if ($property->isRequired()) {
					$class = static::class;
					throw new QRFakturaException("Property '$class' is required.");
				}
				continue;
			}
			$properties[$property->getKey()] = $property->getValue($format, $convert, $truncate);
		}

		$spayd = 'SPD*' . static::Version;

		foreach ($properties as $key => $value) {
			$spayd .= '*' . $key . ':' . $value;
		}

		return $spayd;
	}


	/**
	 * @return \GdImage|resource
	 */
	public function getQrCodeGd(int $size = self::DefaultSize)
	{
		// Min $size ???
		// Odhad
		$minPixelSize = $size / 25;
		$qrSize = ceil($size - 8 * $minPixelSize);

		$qrCode =  QrCode::create($this->getSpayd())
			->setSize($qrSize)
			->setEncoding(new Encoding('ISO-8859-1'))
			->setErrorCorrectionLevel(new ErrorCorrectionLevelMedium())
			->setMargin(0)
			->setRoundBlockSizeMode(new RoundBlockSizeModeShrink());
		$writer = new PngWriter();
		/** @var PngResult $pngResult */
		$pngResult = $writer->write($qrCode);
		$generatedSize = $pngResult->getMatrix()->getInnerSize();
		$pixelSize = $pngResult->getMatrix()->getBlockSize();
		$gdQr = $pngResult->getImage();
		// Margin
		$marginSize = $generatedSize + 8 * $pixelSize;
		$gdMargin = imagecreatetruecolor($marginSize, $marginSize);
		imagefill($gdMargin,0,0, imagecolorallocate($gdMargin, 255, 255, 255));
		imagecopy($gdMargin, $gdQr, round(($marginSize - $generatedSize) / 2), round(($marginSize  - $generatedSize) / 2) , 0, 0, $generatedSize, $generatedSize);
		// Správná čtvercová velikost + rámeček
		$gdSizedFrame = imagecreatetruecolor($size, $size);
		imagefill($gdSizedFrame,0,0, imagecolorallocate($gdSizedFrame, 0, 0, 0));
		imagecopyresized($gdSizedFrame, $gdMargin, 2, 2 , 0, 0, $size - 4, $size - 4, $marginSize, $marginSize);
		$pixelSize = (($size - 4) / $generatedSize) * $pixelSize;
		// Label už s mezerou před
		$qdLabel = imagecreate(ceil(18 * $pixelSize), ceil(4 * $pixelSize + 2));
		imagefill($qdLabel,0,0, imagecolorallocate($qdLabel, 255, 255, 255));
		$fontSize = floor(16 * $pixelSize / 6.6);
		imagettftext($qdLabel, $fontSize, 0, ceil(2 * $pixelSize), $fontSize + 2, imagecolorallocate($qdLabel, 0, 0, 0), __DIR__ . '/../assets/arial_bold.ttf', 'QR platba');
		// Výsledek
		$gdResult = imagecreatetruecolor($size, ceil($size + 4 * $pixelSize - 2));
		imagefill($gdResult,0,0, imagecolorallocate($gdResult, 255, 255, 255));
		imagecopy($gdResult, $gdSizedFrame, 0, 0, 0, 0, $size, $size);
		imagecopy($gdResult, $qdLabel, ceil(2 * $pixelSize), $size - 2, 0, 0, ceil(18 * $pixelSize), ceil(4 * $pixelSize + 2));

		return $gdResult;
	}


	public function getQrCodeString(int $size = self::DefaultSize): string
	{
		ob_start();
		imagepng($this->getQrCodeGd($size));
		return ob_get_clean();
	}


	public function getQrCodeUri(int $size = self::DefaultSize): string
	{
		return 'data:image/png;base64,' . base64_encode($this->getQrCodeString($size));
	}


	/**
	 * @param string $iban
	 * @param string|null $bic
	 * @param bool $emptyStringAsNull
	 * @return static
	 */
	public function setAccount(string $iban, ?string $bic = null, bool $emptyStringAsNull = true)
	{
		if ($emptyStringAsNull) {
			$bic = $bic === '' ? null : $bic;
		}
		$this->ibanBic->setIban($iban);
		$this->ibanBic->setBic($bic);
		return $this;
	}


	/**
	 * @param string|null $iban
	 * @param string|null $bic
	 * @param bool $emptyStringAsNull
	 * @return static
	 */
	public function addAltAccount(?string $iban = null, ?string $bic = null, bool $emptyStringAsNull = true)
	{
		if ($emptyStringAsNull) {
			$iban = $iban === '' ? null : $iban;
			$bic = $bic === '' ? null : $bic;
		}
		if ($iban === null) {
			return $this;
		}
		$this->ibanBicAlts->add($iban, $bic);
		return $this;
	}


	/**
	 * @param float|null $amount
	 * @param bool $autoRound
	 * @param bool $zeroAsNull
	 * @return static
	 */
	public function setAmount(?float $amount = null, bool $autoRound = true, bool $zeroAsNull = true)
	{
		if ($zeroAsNull && $amount === 0.0) {
			$amount = null;
		}
		if ($amount === null) {
			$this->amount->setValue($amount);
			return $this;
		}
		if ($autoRound) {
			$amount = round($amount, Amount::MaxDecimals);
		}
		$this->amount->setValue($amount);
		return $this;
	}


	/**
	 * Je přednastavena hodnota 'CZK' todo
	 * @param string|null $currency
	 * @param bool $emptyStringAsNull
	 * @return static
	 */
	public function setCurrency(?string $currency = null, bool $emptyStringAsNull = true)
	{
		if ($emptyStringAsNull && $currency === '') {
			$currency = null;
		}
		$this->currency->setValue($currency);
		return $this;
	}


	/**
	 * Ve specifikaci je definováno jako 'Celé číslo', ale int to být nemůže, to by mizely případné nuly na začátku
	 * @param string|null $payeeIdentifier
	 * @param bool $emptyStringAsNull
	 * @return static
	 */
	public  function setPayeeIdentifier(?string $payeeIdentifier = null, bool $emptyStringAsNull = true)
	{
		if ($emptyStringAsNull && $payeeIdentifier === '') {
			$payeeIdentifier = null;
		}
		$this->payeeIdentifier->setValue($payeeIdentifier);
		return $this;
	}


	/**
	 * @param string|null $payeeName
	 * @param bool $emptyStringAsNull
	 * @return static
	 */
	public function setPayeeName(?string $payeeName = null, bool $emptyStringAsNull = true)
	{
		if ($emptyStringAsNull && $payeeName === '') {
			$payeeName = null;
		}
		$this->payeeName->setValue($payeeName);
		return $this;
	}


	/**
	 * @param \DateTime|null $dueDate
	 * @return static
	 */
	public function setDueDate(?\DateTime $dueDate = null)
	{
		$this->dueDate->setValue($dueDate);
		return $this;
	}


	/**
	 * @param bool $paymentType
	 * @return static
	 */
	public function setPaymentType(bool $paymentType = false)
	{
		$this->paymentType->setValue($paymentType ? 'PT' : null);
		return $this;
	}


	/**
	 * @param string|null $message
	 * @param bool $emptyStringAsNull
	 * @return static
	 */
	public function setMessage(?string $message = null, bool $emptyStringAsNull = true)
	{
		if ($emptyStringAsNull && $message === '') {
			$message = null;
		}
		$this->message->setValue($message);
		return $this;
	}


	/**
	 * @param bool $isPhone
	 * @param string|null $emailOrPhone
	 * @param bool $emptyStringAsNull
	 * @return static
	 */
	public function setNotification(bool $isPhone = true, ?string $emailOrPhone = null, bool $emptyStringAsNull = true)
	{
		if ($emptyStringAsNull && $emailOrPhone === '') {
			$emailOrPhone = null;
		}

		if ($emailOrPhone === null) {
			$this->notificationMethod->setValue(null);
			$this->notificationId->setValue(null);
			return $this;
		}

		if ($isPhone) {
			$this->notificationMethod->setValue('P');
			$this->notificationId->setPhone($emailOrPhone);
		} else {
			$this->notificationMethod->setValue('E');
			$this->notificationId->setEmail($emailOrPhone);
		}

		return $this;
	}


	/**
	 * @param int|null $repeatPayment 0-30, 0 nezkoušet
	 * @return static
	 */
	public function setRepeatPayment(?int $repeatPayment = null)
	{
		$this->repeatPayment->setValue($repeatPayment);
		return $this;
	}


	/**
	 * @param string|null $variableSymbol
	 * @param bool $emptyStringAsNull
	 * @return static
	 */
	public function setVariableSymbol(?string $variableSymbol = null, bool $emptyStringAsNull = true)
	{
		if ($emptyStringAsNull && $variableSymbol === '') {
			$variableSymbol = null;
		}
		$this->variableSymbol->setValue($variableSymbol);
		return $this;
	}


	/**
	 * @param string|null $specificSymbol
	 * @param bool $emptyStringAsNull
	 * @return static
	 */
	public function setSpecificSymbol(?string $specificSymbol = null, bool $emptyStringAsNull = true)
	{
		if ($emptyStringAsNull && $specificSymbol === '') {
			$specificSymbol = null;
		}
		$this->specificSymbol->setValue($specificSymbol);
		return $this;
	}


	/**
	 * @param string|null $constantSymbol
	 * @param bool $emptyStringAsNull
	 * @return $this
	 */
	public function setConstantSymbol(?string $constantSymbol = null, bool $emptyStringAsNull = true)
	{
		if ($emptyStringAsNull && $constantSymbol === '') {
			$constantSymbol = null;
		}
		$this->constantSymbol->setValue($constantSymbol);
		return $this;
	}


	/**
	 * @param string|null $payerIdentifier
	 * @param bool $emptyStringAsNull
	 * @return static
	 */
	public function setPayerIdentifier(?string $payerIdentifier = null, bool $emptyStringAsNull = true)
	{
		if ($emptyStringAsNull && $payerIdentifier === '') {
			$payerIdentifier = null;
		}
		$this->payerIdentifier->setValue($payerIdentifier);
		return $this;
	}


	/**
	 * @param string|null $url
	 * @param bool $emptyStringAsNull
	 * @return static
	 */
	public function setUrl(?string $url = null, bool $emptyStringAsNull = true)
	{
		if ($emptyStringAsNull && $url === '') {
			$url = null;
		}
		$this->url->setValue($url);
		return $this;
	}
}