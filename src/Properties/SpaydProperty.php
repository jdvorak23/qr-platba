<?php

namespace Jdvorak23\QrFaktura\Properties;

abstract class SpaydProperty extends Property
{
	protected string $key;


	public function getKey(): string
	{
		return $this->key;
	}

}