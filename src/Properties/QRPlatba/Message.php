<?php

namespace Jdvorak23\QrFaktura\Properties\QRPlatba;

use Jdvorak23\QrFaktura\Properties\SpaydProperty;

class Message extends SpaydProperty
{
	protected string $key = 'MSG';

	protected int $maxLength = 60;

	protected bool $allowsCustomString = true;
}