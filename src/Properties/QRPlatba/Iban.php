<?php

namespace Jdvorak23\QrFaktura\Properties\QRPlatba;

use Jdvorak23\QrFaktura\Properties\Property;

class Iban extends Property
{
	protected string $format = '/^[A-Z]{2}[0-9]{2}[A-Z0-9]{11,30}$/';
}