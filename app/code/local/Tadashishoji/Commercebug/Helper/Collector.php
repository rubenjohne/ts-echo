<?php
	class Tadashishoji_Commercebug_Helper_Collector extends Tadashishoji_Commercebug_Helper_Abstract
	{
		static protected $items;
		static public function saveItem($key, $value)
		{
			self::$items[$key] = $value;
		}
	}