<?php

namespace Jdvorak23\QrFaktura\Properties\QRPlatba;

use Jdvorak23\QrFaktura\Properties\SpaydProperty;

class RepeatPayment extends SpaydProperty
{
	protected string $key = 'X-PER';

	protected string $format = '/^([0-9]|[12][0-9]|30)$/';
}