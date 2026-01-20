<?php

namespace Jdvorak23\QrFaktura\Properties\QRPlatba;

use Jdvorak23\QrFaktura\Properties\SpaydProperty;

class PayeeName extends SpaydProperty
{
	protected string $key = 'RN';

	protected int $maxLength = 35;

	protected bool $allowsCustomString = true;
}