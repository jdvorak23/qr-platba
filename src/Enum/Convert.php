<?php

namespace Jdvorak23\QrFaktura\Enum;

abstract class Convert
{

	private function __construct()
	{

	}


	public static function None(): self
	{
		static $instance = null;
		return $instance ?? $instance = new class() extends Convert {};
	}


	public static function Alphanumeric(): self
	{
		static $instance = null;
		return $instance ?? $instance = new class() extends Convert {};
	}


	public static function Urlencode(): self
	{
		static $instance = null;
		return $instance ?? $instance = new class() extends Convert {};
	}

}