<?php

namespace Jdvorak23\QrPlatba\Properties;

abstract class SpaydProperty extends Property
{
	protected string $key;


	public function getKey(): string
	{
		return $this->key;
	}

}