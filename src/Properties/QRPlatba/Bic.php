<?php

namespace Jdvorak23\QrFaktura\Properties\QRPlatba;

use Jdvorak23\QrFaktura\Properties\Property;

class Bic extends Property
{
	protected string $format = '/^[A-Z]{6}[A-Z0-9]{2}([A-Z0-9]{3})?$/';
}