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

/* Only for Magento 1.3 */
class AW_Ascurl_Block_Canonical extends Mage_Core_Block_Template
{
    /**
     * Return canonical url for current product
     * @return string
     */
    public function getCanonicalUrl()
    {
        $product = Mage::registry('product');
        $category = Mage::registry('current_category');
        if ($product) {
            $params = array('_ignore_category'=>true);
            return $product->getUrlModel()->getUrl($product, $params);
        }
        elseif($category){
            return $category->getUrl();
        }
        return '';
    }
}