<?php

namespace Jdvorak23\QrPlatba\Properties\QRPlatba;

use Jdvorak23\QrPlatba\Properties\SpaydProperty;

class PayerIdentifier extends SpaydProperty
{
	protected string $key = 'X-ID';

	protected int $maxLength = 20;

	protected bool $allowsCustomString = true;
}