<?php

namespace Jdvorak23\QrPlatba\Enum;

abstract class Convert
{

	private function __construct()
	{

	}


	/**
	 * Uživatelův vstup musí odpovídat zvolenému Format a Truncate, jinak bude vyhozena výjimka
	 * @return self
	 */
	public static function None(): self
	{
		static $instance = null;
		return $instance ?? $instance = new class() extends Convert {};
	}


	/**
	 * Všechny uživatelské řetězce budou transformovány do znaků QRPlatba::AlphanumericChars
	 * Všem písmenům bude odebrána diakritika a budou převedeny na velké
	 * Další znaky mimo QRPlatba::AlphanumericChars budou vynechány
	 * @return self
	 */
	public static function Alphanumeric(): self
	{
		static $instance = null;
		return $instance ?? $instance = new class() extends Convert {};
	}


	/**
	 * Znaky, které nespadají do zvoleného Format, budou urlencode
	 * @return self
	 */
	public static function Urlencode(): self
	{
		static $instance = null;
		return $instance ?? $instance = new class() extends Convert {};
	}

}