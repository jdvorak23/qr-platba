<?php

namespace Jdvorak23\QrFaktura\Enum;

abstract class Format
{

	private function __construct()
	{

	}


	public static function Alphanumeric(): self
	{
		static $instance = null;
		return $instance ?? $instance = new class() extends Format {};
	}


	public static function Iso(): self
	{
		static $instance = null;
		return $instance ?? $instance = new class() extends Format {};
	}


	public static function Any(): self
	{
		static $instance = null;
		return $instance ?? $instance = new class() extends Format {};
	}

}