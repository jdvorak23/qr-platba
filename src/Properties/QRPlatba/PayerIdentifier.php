<?php

namespace Jdvorak23\QrFaktura\Properties\QRPlatba;

use Jdvorak23\QrFaktura\Properties\SpaydProperty;

class PayerIdentifier extends SpaydProperty
{
	protected string $key = 'X-ID';

	protected int $maxLength = 20;

	protected bool $allowsCustomString = true;
}