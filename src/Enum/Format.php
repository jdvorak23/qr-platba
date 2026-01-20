<?php

namespace Jdvorak23\QrFaktura\Enum;

abstract class Format
{

	private function __construct()
	{

	}


	/**
	 * Znaky SPAYD řetězce budou z rozpětí QRPlatba::AlphanumericChars,
	 * formát QR kódu bude vždy alfanumerický
	 * @return self
	 */
	public static function Alphanumeric(): self
	{
		static $instance = null;
		return $instance ?? $instance = new class() extends Format {};
	}


	/**
	 * Znaky SPAYD řetězce budou ze sady ISO-8859-1, tj. pokud zde bude znak mimo QRPlatba::AlphanumericChars,
	 * formát QR kódu bude binární
	 * @return self
	 */
	public static function Iso(): self
	{
		static $instance = null;
		return $instance ?? $instance = new class() extends Format {};
	}


	/**
	 * Pro nastavení Convert::Alfanumeric() nemá tato volba smysl, Formát bude vždy Alphanumeric()
	 * Pokusí se vytvořit vše jako Format::Alfanumeric(), až když to "nejde", použije Format::Iso()
	 * Pokud je uživatelský string moc dlouhý, convertuje ho dle Format::Iso() - poté porovná délky obou:
	 * 1) pokud je iso řetězec v limitu délky, pak ho použije a přejde na Format::Iso()
	 * 2) pokud jsou stejně dlouhé, a je nastaveno Truncate::Yes(), pak ořízne a použije Format::Alfanumeric(). Pokud je Truncate::No() - výjimka
	 * 3) pokud jsou oba nad limit a iso řetězec je kratší, a je nastaveno Truncate::Yes(), pak ho použije a přejde na Format::Iso(). Pokud je Truncate::No() - výjimka
	 * @return self
	 */
	public static function Any(): self
	{
		static $instance = null;
		return $instance ?? $instance = new class() extends Format {};
	}

}