<?php

namespace Jdvorak23\QrPlatba\Properties\QRPlatba;

use Jdvorak23\QrPlatba\Properties\SpaydProperty;

class NotificationMethod extends SpaydProperty
{
	protected string $key = 'NT';

	protected string $format = '/^[PE]{1}$/';
}