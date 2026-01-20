<?php

namespace Jdvorak23\QrFaktura\Enum;

class Truncate
{

	private function __construct()
	{

	}


	public static function No(): self
	{
		static $instance = null;
		return $instance ?? $instance = new class() extends Truncate {};
	}


	public static function Yes(): self
	{
		static $instance = null;
		return $instance ?? $instance = new class() extends Truncate {};
	}


	public static function Alphanumeric(): self
	{
		static $instance = null;
		return $instance ?? $instance = new class() extends Truncate {};
	}

}