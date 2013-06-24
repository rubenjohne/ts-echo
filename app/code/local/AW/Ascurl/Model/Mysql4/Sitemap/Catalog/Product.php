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

class AW_Ascurl_Model_Mysql4_Sitemap_Catalog_Product extends Mage_Sitemap_Model_Mysql4_Catalog_Product
{
    const USE_CANONICAL_URL_AT_GOOGLE_SITEMAP = 'catalog/advancedseo/use_canonical_url_at_google_sitemap';

    protected $rewriteQuery;

    protected function initQueries()
    {
        $this->rewriteQuery = 
            $this->_getWriteAdapter()->select()
                ->from(array('ur' => $this->getTable('core/url_rewrite')), array('request_path'))
                ->where('id_path = ?', 'to_replace')
                ->assemble();
    }
    /**
     * Prepare product
     *
     * @param array $productRow
     * @return Varien_Object
     */
    protected function _prepareProduct(array $productRow)
    {
        $product = new Varien_Object();
        $product->setId($productRow[$this->getIdFieldName()]);
        $productLoaded = Mage::getModel('catalog/product')->setData($productRow);

        $requestPath = $productLoaded->getUrl();
        
        $idPath = sprintf('product/%d', $productLoaded->getEntityId());
        if (($categoryIdAsc = Mage::helper('ascurl')->getCategoryIdAsc($productLoaded, true)) && Mage::getStoreConfig(self::USE_CANONICAL_URL_AT_GOOGLE_SITEMAP, $productLoaded->getStoreId())) {
            $idPath = sprintf('%s/%d', $idPath, $categoryIdAsc);
        }

        $rewrited = $this->_getWriteAdapter()
                        ->query(str_replace('to_replace', $idPath, $this->rewriteQuery))
                        ->fetch();
        if (isset($rewrited['request_path'])) $product->setUrl($rewrited['request_path']);

        try
        { 
            if (Mage::getStoreConfig(AW_Ascurl_Model_Sitemap::USE_IMAGES, $productLoaded->getStoreId()))
                $product->setImageUrl($productLoaded->getImageUrl());
        }
        catch (Exception $ex)
        {}
        return $product;
    }

    /**
     * Get category collection array
     *
     * @return array
     */
    public function getCollection($storeId)
    {
        $this->initQueries();

        $products = array();

        $store = Mage::app()->getStore($storeId);
        /* @var $store Mage_Core_Model_Store */

        if (!$store) {
            return false;
        }

        $urCondions = array(
            'e.entity_id=ur.product_id',
            'ur.category_id IS NULL',
            $this->_getWriteAdapter()->quoteInto('ur.store_id=?', $store->getId()),
            $this->_getWriteAdapter()->quoteInto('ur.is_system=?', 1),
        );
        $this->_select = $this->_getWriteAdapter()->select()
            ->from(array('e' => $this->getMainTable()), array($this->getIdFieldName()))
            ->join(
                array('w' => $this->getTable('catalog/product_website')),
                'e.entity_id=w.product_id',
                array()
            )
            ->where('w.website_id=?', $store->getWebsiteId());

        $this->_addFilter($storeId, 'visibility', Mage::getSingleton('catalog/product_visibility')->getVisibleInSiteIds(), 'in');
        $this->_addFilter($storeId, 'status', Mage::getSingleton('catalog/product_status')->getVisibleStatusIds(), 'in');

        $this->addProductAttributeToSelect($storeId, 'image');
        $this->addProductAttributeToSelect($storeId, AW_Ascurl_Block_Adminhtml_Catalog_Product_Edit_Tab_Ascurl::OWNER_TREE_ID);

        $this->_select
            ->joinLeft(
                array('catalog_category_product' => $this->getTable('catalog/category_product')),
                'catalog_category_product.product_id = e.entity_id'
                );
        $this->_select->columns(array('category_ids' => new Zend_Db_Expr('CONVERT(GROUP_CONCAT(catalog_category_product.category_id) USING utf8)')));
        $this->_select->group('e.entity_id');

        $query = $this->_getWriteAdapter()->query($this->_select);
        while ($row = $query->fetch()) {
            $row['store_id'] = $storeId;
            $product = $this->_prepareProduct($row);
            $products[$product->getId()] = $product;
        }

        return $products;
    }

    protected function addProductAttributeToSelect($storeId, $attributeCode)
    {
        if (!isset($this->_attributesCache[$attributeCode])) {
            $attribute = Mage::getSingleton('catalog/product')->getResource()->getAttribute($attributeCode);

            $this->_attributesCache[$attributeCode] = array(
                'entity_type_id'    => $attribute->getEntityTypeId(),
                'attribute_id'      => $attribute->getId(),
                'table'             => $attribute->getBackend()->getTable(),
                'is_global'         => $attribute->getIsGlobal() == Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
                'backend_type'      => $attribute->getBackendType()
            );
        }

        $attribute = $this->_attributesCache[$attributeCode];

        if (!$this->_select instanceof Zend_Db_Select) {
            return false;
        }

        $this->_select->joinLeft(
            array('t1_'.$attributeCode => $attribute['table']),
            'e.entity_id=t1_'.$attributeCode.'.entity_id 
                AND t1_'.$attributeCode.'.store_id=0
                    AND t1_'.$attributeCode.'.attribute_id='. $attribute['attribute_id'],
            array()
        );

        if (!$attribute['is_global'])
        {
             $this->_select->joinLeft(
                array('t2_'.$attributeCode => $attribute['table']),
                $this->_getWriteAdapter()->quoteInto('t1_'.$attributeCode.'.entity_id = t2_'.$attributeCode.'.entity_id AND t1_'.$attributeCode.'.attribute_id = t2_'.$attributeCode.'.attribute_id AND t2_'.$attributeCode.'.store_id=?', $storeId),
                array()
            );
        
	    $this->_select->columns(array($attributeCode => 'IFNULL(t2_'.$attributeCode.'.value, t1_'.$attributeCode.'.value)'));
	}
        return $this->_select;
    }
}
