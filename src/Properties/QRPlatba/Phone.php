<?php

namespace Jdvorak23\QrPlatba\Properties\QRPlatba;

use Jdvorak23\QrPlatba\Properties\Property;

class Phone extends Property
{
	protected string $format = '/^(\d{1,14}|\+\d{1,12})$/';
}