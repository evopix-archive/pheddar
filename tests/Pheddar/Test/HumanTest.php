<?php

/*
 * This file is part of Pheddar.
 *
 * (c) Brandon Summers <brandon@brandonsummers.name>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pheddar\Test;

use Pheddar\Human;

class HumanTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * Provides test data for test_convert_integers()
	 * 
	 * @return  array
	 */
	public function provider_convert_integers()
	{
		return array(
			'5'  => 5,
			'10' => 10,
		);
	}

	/**
	 * Tests that integers are properly converted.
	 * 
	 * @test
	 * @dataProvider  provider_convert_integers
	 * @param  string   $str       Human string to parse
	 * @param  integer  $expected  Expected integer
	 */
	public function test_convert_integers($str, $expected)
	{
		$result = Human::to_number($str);
		$this->assertSame($expected, $result);
	}

	/**
	 * Provides test data for test_convert_floats()
	 * 
	 * @return  array
	 */
	public function provider_convert_floats()
	{
		return array(
			'5.5'  => 5.5,
			'10.5' => 10.5,
		);
	}

	/**
	 * Tests that floats are properly converted.
	 * 
	 * @test
	 * @dataProvider  provider_convert_floats
	 * @param  string  $str       Human string to parse
	 * @param  float   $expected  Expected float
	 */
	public function test_convert_floats($str, $expected)
	{
		$result = Human::to_number($str);
		$this->assertSame($expected, $result);
	}

	/**
	 * Tests that hundreds are properly converted.
	 * 
	 * @test
	 */
	public function test_convert_hundreds()
	{
		$result = Human::to_number('5 hundred');
		$this->assertSame(500, $result);
	}

	/**
	 * Tests that thousands are properly converted.
	 * 
	 * @test
	 */
	public function test_convert_thousands()
	{
		$result = Human::to_number('5 thousand');
		$this->assertSame(5000, $result);
	}

	/**
	 * Tests that millions are properly converted.
	 * 
	 * @test
	 */
	public function test_convert_millions()
	{
		$result = Human::to_number('5 million');
		$this->assertSame(5000000, $result);
	}

	/**
	 * Tests that decimals are converted before millions
	 *  are properly converted.
	 * 
	 * @test
	 */
	public function test_convert_decimals()
	{
		$result = Human::to_number('5.5 million');
		$this->assertSame(5500000, $result);
	}

	/**
	 * Provides test data for test_strip_unknown_entities()
	 * 
	 * @return  array
	 */
	public function provider_strip_unknown_entities()
	{
		return array(
			'5.5 & thousand' => 5500,
			'5,500' => 5500,
		);
	}

	/**
	 * Tests that unknown entities are stripped from 
	 * string before conversion.
	 * 
	 * @test
	 * @dataProvider  provider_strip_unknown_entities
	 * @param  string   $str       Human string to parse
	 * @param  integer  $expected  Expected integer
	 */
	public function test_strip_unknown_entities($str, $expected)
	{
		$result = Human::to_number($str);
		$this->assertSame($expected, $result);
	}

	/**
	 * Tests that numbers up to vigintillion are 
	 * properly converted.
	 * 
	 * @test
	 */
	public function test_convert_vigintillion()
	{
		$result = Human::to_number('3.2 vigintillion');
		$this->assertSame((3.2 * pow(10, 63)), $result);
	}

	/**
	 * Provides test data for test_convert_money()
	 * 
	 * @return  array
	 */
	public function provider_convert_money()
	{
		return array(
			'$5.2 million' => 5200000,
			'10 big ones'  => 10000,
			'10 large'     => 10000,
		);
	}

	/**
	 * Tests that monetary values are properly converted.
	 * 
	 * @test
	 * @dataProvider  provider_convert_money
	 * @param  string   $str       Human string to parse
	 * @param  integer  $expected  Expected integer
	 */
	public function test_convert_money($str, $expected)
	{
		$result = Human::to_number($str);
		$this->assertSame($expected, $result);
	}

}