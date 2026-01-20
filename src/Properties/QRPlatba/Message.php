<?php

namespace Jdvorak23\QrPlatba\Properties\QRPlatba;

use Jdvorak23\QrPlatba\Properties\SpaydProperty;

class Message extends SpaydProperty
{
	protected string $key = 'MSG';

	protected int $maxLength = 60;

	protected bool $allowsCustomString = true;
}