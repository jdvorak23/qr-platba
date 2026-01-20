<?php

namespace Jdvorak23\QrPlatba\Properties\QRPlatba;

use Jdvorak23\QrPlatba\Properties\Property;

class Bic extends Property
{
	protected string $format = '/^[A-Z]{6}[A-Z0-9]{2}([A-Z0-9]{3})?$/';
}