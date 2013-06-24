<?php
/**
* aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * aheadWorks does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * aheadWorks does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Ascurl
 * @version    1.3.2
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
 */


class AW_Ascurl_Model_Source_Metarobots
{
    public static $metarobots = array(
        'IF' => 'INDEX,FOLLOW',
        'NIF' => 'NOINDEX,FOLLOW',
        'INF' => 'INDEX,NOFOLLOW',
        'NINF' => 'NOINDEX,NOFOLLOW'
    );

    public static function toOptionArray()
    {
        $res = array();
        foreach (self::$metarobots as $value => $label)
        { $res[] = array('value' => $value, 'label'  => Mage::helper('ascurl')->__($label)); }

        return $res;
    }
}