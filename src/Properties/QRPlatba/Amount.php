<?php

namespace Jdvorak23\QrPlatba\Properties\QRPlatba;

use Jdvorak23\QrPlatba\Properties\SpaydProperty;

class Amount extends SpaydProperty
{
	public const MaxDecimals = 2;

	protected string $key = 'AM';

	protected string $format = '/^\d{1,7}(\.\d{1,2})?$/';
}