<?php

namespace Jdvorak23\QrFaktura\Properties\QRPlatba;

use Jdvorak23\QrFaktura\Properties\SpaydProperty;

class PaymentType extends SpaydProperty
{
	protected string $key = 'PT';

	protected string $format = '/^IP$/';
}