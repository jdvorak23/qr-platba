<?php

namespace Jdvorak23\QrFaktura\Properties\QRPlatba;

use Jdvorak23\QrFaktura\Properties\Property;

class Phone extends Property
{
	protected string $format = '/^(\d{1,14}|\+\d{1,12})$/';
}