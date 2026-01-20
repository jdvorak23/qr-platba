<?php

namespace Jdvorak23\QrPlatba\Properties\QRPlatba;

use Jdvorak23\QrPlatba\Properties\SpaydProperty;

class Url extends SpaydProperty
{
	protected string $key = 'X-URL';

	protected int $maxLength = 140;

	protected bool $allowsCustomString = true;
}