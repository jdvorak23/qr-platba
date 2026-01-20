<?php

namespace Jdvorak23\QrPlatba\Properties\QRPlatba;

use Jdvorak23\QrPlatba\Properties\SpaydProperty;

/**
 * Ve specifikaci je definováno jako 'Celé číslo', ale int to být nemůže, to by mizely případné nuly na začátku
 */
class SpecificSymbol extends SpaydProperty
{
	protected string $key = 'X-SS';

	protected string $format = '/^[0-9]{1,10}$/';
}