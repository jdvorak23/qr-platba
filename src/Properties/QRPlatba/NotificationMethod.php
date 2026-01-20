<?php

namespace Jdvorak23\QrFaktura\Properties\QRPlatba;

use Jdvorak23\QrFaktura\Properties\SpaydProperty;

class NotificationMethod extends SpaydProperty
{
	protected string $key = 'NT';

	protected string $format = '/^[PE]{1}$/';
}