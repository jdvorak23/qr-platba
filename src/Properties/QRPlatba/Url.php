<?php

namespace Jdvorak23\QrFaktura\Properties\QRPlatba;

use Jdvorak23\QrFaktura\Properties\SpaydProperty;

class Url extends SpaydProperty
{
	protected string $key = 'X-URL';

	protected int $maxLength = 140;

	protected bool $allowsCustomString = true;
}