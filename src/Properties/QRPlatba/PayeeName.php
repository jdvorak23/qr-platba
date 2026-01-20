<?php

namespace Jdvorak23\QrPlatba\Properties\QRPlatba;

use Jdvorak23\QrPlatba\Properties\SpaydProperty;

class PayeeName extends SpaydProperty
{
	protected string $key = 'RN';

	protected int $maxLength = 35;

	protected bool $allowsCustomString = true;
}