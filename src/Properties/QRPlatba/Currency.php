<?php

namespace Jdvorak23\QrFaktura\Properties\QRPlatba;

use Jdvorak23\QrFaktura\Properties\SpaydProperty;

class Currency extends SpaydProperty
{
	protected string $key = 'CC';

	protected string $format = '/^[A-Z]{3}$/';
}