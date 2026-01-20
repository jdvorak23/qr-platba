<?php

namespace Jdvorak23\QrPlatba\Properties\QRPlatba;

use Jdvorak23\QrPlatba\Properties\SpaydProperty;

class PaymentType extends SpaydProperty
{
	protected string $key = 'PT';

	protected string $format = '/^IP$/';
}