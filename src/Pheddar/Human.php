<?php

/*
 * This file is part of Pheddar.
 *
 * (c) Brandon Summers <brandon@brandonsummers.name>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pheddar;

class Human
{

	/**
	 * @var  array  Word to number mappings
	 */
	protected static $_mapping = array();

	/**
	 * @var  array  Array of enabled dialects
	 */
	protected static $_enabled_dialects = array();

	/**
	 * Converts a human string to it's equivelant number.
	 * 
	 *     Human::to_number('15 hundred'); // 1500
	 * 
	 * @param  string  $string  Human string to convert
	 */
	public static function to_number($string)
	{
		if (empty(static::$_enabled_dialects))
		{
			static::_initialize();
		}

		foreach (static::$_enabled_dialects as $dialect)
		{
			foreach(static::$_mapping[$dialect] as $number_name => $value)
			{
				$string = preg_replace('/(^|\s)'.$number_name.'($|\s)/i', "* $value", $string);
			}
		}

		$string = 'return '.preg_replace('/[^\d\*\+\.E]/', '', $string).';';
		$number = eval($string);

		// Handle PHP's stupid type juggling
		if ( ! strstr($number, '.'))
		{
			$number = (int) $number;
		}

		return $number;
	}

	/**
	 * Adds a dialect to the collection.
	 * 
	 * @param  string  $name    Name of the dialect
	 * @param  array   $values  Array of word => value mappings
	 */
	public function set_dialect($name, $values)
	{
		// Make sure the defaults are set
		if (empty(static::$_enabled_dialects))
		{
			static::_initialize();
		}

		if (empty(static::$_mapping[$name]))
		{
			static::$_mapping[$name] = $values;
		}
		else
		{
			static::$_mapping[$name] = array_merge(static::$_mapping[$name], $values);
		}

		if ( ! in_array($name, static::$_enabled_dialects))
		{
			static::$_enabled_dialects[] = $name;
		}
	}

	/**
	 * Gets a dialect from the collection.
	 * 
	 * @param   string  $name  Name of the dialect to get
	 * @return  array
	 */
	public function get_dialect($name = NULL)
	{
		if ($name === NULL)
		{
			return static::$_mapping;
		}

		return static::$_mapping[$name];
	}

	/**
	 * Initializes the default dialects.
	 */
	protected static function _initialize()
	{
		// Add standard US dialect
		$dialect = array();
		$dialect['hundred'] = 100;
		$dialect['thousand'] = 1000;

		$words = explode(' ', 'm b tr quadr quint sext sept oct non dec undec duodec tredec quattuordec quindec sexdec septemdec octodec novemdec vigint');
		$curren_num = 1000;

		foreach($words as $i => $word)
		{
			$curren_num = (1000 * $curren_num);
			$dialect["{$word}illion"] = $curren_num;
		}

		static::$_mapping['en_us'] = $dialect;

		// Add slang US dialect
		$dialect = array();

		$words = explode(' ', 'bacon bills bread buck cabbage cash cheddar cheese clams dolla dollar dough green greenback kale lettuce loot moolah paper potato potatoes scratch scrip');
		foreach($words as $word)
		{
			$dialect[$word] = 1;
		}

		$words = explode(' ', 'benjamin c-note jackson twankie');
		foreach($words as $word)
		{
			$dialect[$word] = 100;
		}

		$dialect['dead presidents'] = 1;
		$dialect['long green'] = 1;
		$dialect['fin'] = 5;
		$dialect['sawbuck'] = 10;
		$dialect['double sawbuck'] = 20;
		$dialect['large'] = 1000;
		$dialect['big ones'] = 1000;

		static::$_mapping['slang_us'] = $dialect;
		static::$_enabled_dialects = array('en_us', 'slang_us');
	}

}