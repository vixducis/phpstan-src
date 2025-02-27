<?php

namespace NonEmptyString;

use function htmlspecialchars;
use function lcfirst;
use function PHPStan\Testing\assertType;
use function strtolower;
use function strtoupper;
use function ucfirst;

class Foo
{

	public function doFoo(string $s): void
	{
		if ($s === '') {
			return;
		}

		assertType('non-empty-string', $s);
	}

	public function doBar(string $s): void
	{
		if ($s !== '') {
			assertType('non-empty-string', $s);
			return;
		}

		assertType('\'\'', $s);
	}

	public function doFoo2(string $s): void
	{
		if (strlen($s) === 0) {
			return;
		}

		assertType('non-empty-string', $s);
	}

	public function doBar2(string $s): void
	{
		if (strlen($s) > 0) {
			assertType('non-empty-string', $s);
			return;
		}

		assertType('\'\'', $s);
	}

	public function doBar3(string $s): void
	{
		if (strlen($s) >= 1) {
			assertType('non-empty-string', $s);
			return;
		}

		assertType('\'\'', $s);
	}

	public function doFoo5(string $s): void
	{
		if (0 === strlen($s)) {
			return;
		}

		assertType('non-empty-string', $s);
	}

	public function doBar4(string $s): void
	{
		if (0 < strlen($s)) {
			assertType('non-empty-string', $s);
			return;
		}

		assertType('\'\'', $s);
	}

	public function doBar5(string $s): void
	{
		if (1 <= strlen($s)) {
			assertType('non-empty-string', $s);
			return;
		}

		assertType('\'\'', $s);
	}

	public function doFoo3(string $s): void
	{
		if ($s) {
			assertType('non-empty-string', $s);
		} else {
			assertType('\'\'|\'0\'', $s);
		}
	}

	/**
	 * @param non-empty-string $s
	 */
	public function doFoo4(string $s): void
	{
		assertType('array<int, string>&nonEmpty', explode($s, 'foo'));
	}

	/**
	 * @param non-empty-string $s
	 */
	public function doWithNumeric(string $s): void
	{
		if (!is_numeric($s)) {
			return;
		}

		assertType('non-empty-string', $s);
	}

	public function doEmpty(string $s): void
	{
		if (empty($s)) {
			return;
		}

		assertType('non-empty-string', $s);
	}

	public function doEmpty2(string $s): void
	{
		if (!empty($s)) {
			assertType('non-empty-string', $s);
		}
	}

	/**
	 * @param non-empty-string $nonEmpty
	 * @param positive-int $positiveInt
	 * @param 1|2|3 $postiveRange
	 * @param -1|-2|-3 $negativeRange
	 */
	public function doSubstr(string $s, $nonEmpty, $positiveInt, $postiveRange, $negativeRange): void
	{
		assertType('string', substr($s, 5));

		assertType('string', substr($s, -5));
		assertType('non-empty-string', substr($nonEmpty, -5));
		assertType('non-empty-string', substr($nonEmpty, $negativeRange));

		assertType('string', substr($s, 0, 5));
		assertType('non-empty-string', substr($nonEmpty, 0, 5));
		assertType('non-empty-string', substr($nonEmpty, 0, $postiveRange));

		assertType('string', substr($nonEmpty, 0, -5));

		assertType('string', substr($s, 0, $positiveInt));
		assertType('non-empty-string', substr($nonEmpty, 0, $positiveInt));
	}
}

class ImplodingStrings
{

	/**
	 * @param array<string> $commonStrings
	 */
	public function doFoo(string $s, array $commonStrings): void
	{
		assertType('string', implode($s, $commonStrings));
		assertType('string', implode(' ', $commonStrings));
		assertType('string', implode('', $commonStrings));
		assertType('string', implode($commonStrings));
	}

	/**
	 * @param non-empty-array<string> $nonEmptyArrayWithStrings
	 */
	public function doFoo2(string $s, array $nonEmptyArrayWithStrings): void
	{
		assertType('string', implode($s, $nonEmptyArrayWithStrings));
		assertType('string', implode('', $nonEmptyArrayWithStrings));
		assertType('non-empty-string', implode(' ', $nonEmptyArrayWithStrings));
		assertType('string', implode($nonEmptyArrayWithStrings));
	}

	/**
	 * @param array<non-empty-string> $arrayWithNonEmptyStrings
	 */
	public function doFoo3(string $s, array $arrayWithNonEmptyStrings): void
	{
		assertType('string', implode($s, $arrayWithNonEmptyStrings));
		assertType('string', implode('', $arrayWithNonEmptyStrings));
		assertType('string', implode(' ', $arrayWithNonEmptyStrings));
		assertType('string', implode($arrayWithNonEmptyStrings));
	}

	/**
	 * @param non-empty-array<non-empty-string> $nonEmptyArrayWithNonEmptyStrings
	 */
	public function doFoo4(string $s, array $nonEmptyArrayWithNonEmptyStrings): void
	{
		assertType('non-empty-string', implode($s, $nonEmptyArrayWithNonEmptyStrings));
		assertType('non-empty-string', implode('', $nonEmptyArrayWithNonEmptyStrings));
		assertType('non-empty-string', implode(' ', $nonEmptyArrayWithNonEmptyStrings));
		assertType('non-empty-string', implode($nonEmptyArrayWithNonEmptyStrings));
	}

	public function sayHello(): void
	{
		// coming from issue #5291
		$s = array(1, 2);

		assertType('non-empty-string', implode("a", $s));
	}

	/**
	 * @param non-empty-string $glue
	 */
	public function nonE($glue, array $a)
	{
		// coming from issue #5291
		if (empty($a)) {
			return "xyz";
		}

		assertType('non-empty-string', implode($glue, $a));
	}

	public function sayHello2(): void
	{
		// coming from issue #5291
		$s = array(1, 2);

		assertType('non-empty-string', join("a", $s));
	}

	/**
	 * @param non-empty-string $glue
	 */
	public function nonE2($glue, array $a)
	{
		// coming from issue #5291
		if (empty($a)) {
			return "xyz";
		}

		assertType('non-empty-string', join($glue, $a));
	}

}

class LiteralString
{

	function x(string $tableName, string $original): void
	{
		assertType('non-empty-string', "from `$tableName`");
	}

	/**
	 * @param non-empty-string $nonEmpty
	 */
	function concat(string $s, string $nonEmpty): void
	{
		assertType('string', $s . '');
		assertType('non-empty-string', $nonEmpty . '');
		assertType('non-empty-string', $nonEmpty . $s);
	}

}

class GeneralizeConstantStringType
{

	/**
	 * @param array<non-empty-string, int> $a
	 * @param non-empty-string $s
	 */
	public function doFoo(array $a, string $s): void
	{
		$a[$s] = 2;

		// there might be non-empty-string that becomes a number instead
		assertType('array<string, int>&nonEmpty', $a);
	}

	/**
	 * @param array<non-empty-string, int> $a
	 * @param non-empty-string $s
	 */
	public function doFoo2(array $a, string $s): void
	{
		$a[''] = 2;
		assertType('array<string, int>&nonEmpty', $a);
	}

}

class MoreNonEmptyStringFunctions
{

	/**
	 * @param non-empty-string $nonEmpty
	 */
	public function doFoo(string $s, string $nonEmpty, int $i)
	{
		assertType('string', strtoupper($s));
		assertType('non-empty-string', strtoupper($nonEmpty));
		assertType('string', strtolower($s));
		assertType('non-empty-string', strtolower($nonEmpty));
		assertType('string', mb_strtoupper($s));
		assertType('non-empty-string', mb_strtoupper($nonEmpty));
		assertType('string', mb_strtolower($s));
		assertType('non-empty-string', mb_strtolower($nonEmpty));
		assertType('string', lcfirst($s));
		assertType('non-empty-string', lcfirst($nonEmpty));
		assertType('string', ucfirst($s));
		assertType('non-empty-string', ucfirst($nonEmpty));
		assertType('string', ucwords($s));
		assertType('non-empty-string', ucwords($nonEmpty));
		assertType('string', htmlspecialchars($s));
		assertType('non-empty-string', htmlspecialchars($nonEmpty));
		assertType('string', htmlentities($s));
		assertType('non-empty-string', htmlentities($nonEmpty));

		assertType('string', urlencode($s));
		assertType('non-empty-string', urlencode($nonEmpty));
		assertType('string', urldecode($s));
		assertType('non-empty-string', urldecode($nonEmpty));
		assertType('string', rawurlencode($s));
		assertType('non-empty-string', rawurlencode($nonEmpty));
		assertType('string', rawurldecode($s));
		assertType('non-empty-string', rawurldecode($nonEmpty));

		assertType('string', sprintf($s));
		assertType('non-empty-string', sprintf($nonEmpty));
		assertType('string', vsprintf($s, []));
		assertType('non-empty-string', vsprintf($nonEmpty, []));

		assertType('0', strlen(''));
		assertType('int<0, max>', strlen($s));
		assertType('int<1, max>', strlen($nonEmpty));

		assertType('non-empty-string', str_pad($nonEmpty, 0));
		assertType('non-empty-string', str_pad($nonEmpty, 1));
		assertType('string', str_pad($s, 0));
		assertType('non-empty-string', str_pad($s, 1));

		assertType('non-empty-string', str_repeat($nonEmpty, 1));
		assertType('\'\'', str_repeat($nonEmpty, 0));
		assertType('string', str_repeat($nonEmpty, $i));
		assertType('\'\'', str_repeat($s, 0));
		assertType('string', str_repeat($s, 1));
		assertType('string', str_repeat($s, $i));
	}

}
