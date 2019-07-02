<?php
namespace Microweber\Utils\Backup;

/**
 * Microweber - Backup Module Database Save
 *
 * @namespace Microweber\Utils\Backup
 * @package DatabaseWriter
 * @author Bozhidar Slaveykov
 */
class EncodingFix
{

	/**
	 *
	 * @param array $item
	 * @return array
	 */
	public static function decode($content)
	{
		array_walk_recursive($content, function (&$item) {
			if (is_string($item)) {
				$item = utf8_decode($item);
			}
		});

		return $content;
	}

	/**
	 *
	 * @param array $item
	 * @return array
	 */
	public static function encode($content)
	{
		array_walk_recursive($content, function (&$item) {
			if (is_string($item)) {
				$item = utf8_encode($item);
			}
		});
		
		return $content;
	}
}