<?php

namespace Jdvorak23\QrPlatba\Properties\QRPlatba;

use Jdvorak23\QrPlatba\Properties\Property;

class Email extends Property
{
	// Big TODO KODOVANI
	protected string $format = '/^[^@]{1,64}@[^@]{1,255}$/';
}