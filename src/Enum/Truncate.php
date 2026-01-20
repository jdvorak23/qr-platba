<?php

namespace Jdvorak23\QrFaktura\Enum;

class Truncate
{

	private function __construct()
	{

	}


	/**
	 * Týká se properties, kde je možný uživatelský řetězec a property IbanBicAlts
	 * Pokud je uživatelský řetězec delší, než povolená délka ve specifikaci, bude vyhozena výjimka
	 * @return self
	 */
	public static function No(): self
	{
		static $instance = null;
		return $instance ?? $instance = new class() extends Truncate {};
	}


	/**
	 * Pokud je uživatelský řetězec delší, než povolená délka ve specifikaci, bude oříznut
	 * @return self
	 */
	public static function Yes(): self
	{
		static $instance = null;
		return $instance ?? $instance = new class() extends Truncate {};
	}

}