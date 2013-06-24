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

class AW_Ascurl_Helper_Data extends Mage_Core_Helper_Abstract
{
    const USE_CANONICAL_LINK_FOR_PRODUCTS = 'catalog/advancedseo/use_canonical_link_for_products';
    const USE_CATEGORY_AT_URL = 'catalog/seo/product_use_categories';

    protected $storeId;
    protected $storeCategories;

    public function insertStringToFilename($filename, $toInsert)
    {
        $dotPosition = strrpos($filename, '.');
        if ($dotPosition === false)
            $filename = $filename.$toInsert;
        else
        {
            $part1 = substr($filename, 0, $dotPosition);
            $part2 = substr($filename, $dotPosition);
            $filename = $part1.$toInsert.$part2;
        }
        return $filename;
    }

    public function useCanonicalUrl() {

        $resultToReturn = false;

        if (!Mage::getStoreConfig('advanced/modules_disable_output/AW_Ascurl') &&
                Mage::getStoreConfig(self::USE_CANONICAL_LINK_FOR_PRODUCTS)) {

            $resultToReturn = true;
        }

        return $resultToReturn;
    }

    public function getCategoryIdAsc($product, $forGoogleSitemap = false)
    {
        if (!$this->storeId) $this->storeId = $product->getStoreId();

        $category = null;
        if (!Mage::getStoreConfig(self::USE_CATEGORY_AT_URL, $this->storeId)) return $category;
        
        if ($product->getCategoryIdAsc()) $category = $product->getCategoryIdAsc();
        else
        {
            if (!$forGoogleSitemap) $productCategoriesIds = $product->getCategoryIds();
            else
            {
                if (!$product->getData('category_ids')) $productCategoriesIds = array();
                else $productCategoriesIds = explode(',', $product->getData('category_ids'));
            }
            
            foreach ($productCategoriesIds as $productCategoryId)
            {
                if (in_array($productCategoryId, $this->getCategoriesIdsForStore()))
                {
                    $category = $productCategoryId;
                    break;
                }
            }
        }
        return $category;
    }

    public function getCategoriesIdsForStore()
    {
        if (!$this->storeCategories)
            $this->storeCategories = Mage::getModel('catalog/category')->getCategories(Mage::app()->getStore($this->storeId)->getRootCategoryId(), false, false, true)->getItems();

        if (is_array($this->storeCategories)) return array_keys($this->storeCategories);
        return array();
    }

    public function extensionEnabled($extension_name)
	{
		$modules = (array)Mage::getConfig()->getNode('modules')->children();
		if (!isset($modules[$extension_name])
			|| $modules[$extension_name]->descend('active')->asArray()=='false'
			|| Mage::getStoreConfig('advanced/modules_disable_output/'.$extension_name)
		) return false;
		return true;
	}
}