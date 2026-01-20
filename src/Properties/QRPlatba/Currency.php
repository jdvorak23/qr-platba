<?php

namespace Jdvorak23\QrPlatba\Properties\QRPlatba;

use Jdvorak23\QrPlatba\Properties\SpaydProperty;

class Currency extends SpaydProperty
{
	protected string $key = 'CC';

	protected string $format = '/^[A-Z]{3}$/';
}