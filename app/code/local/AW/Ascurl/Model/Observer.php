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


class AW_Ascurl_Model_Observer
{
    /**
     * Not for Magento 1.3. Add 2 fields to admin cms page edit
     * @param object $observer
     */
    public function addFieldsToCmsMeta($observer) {
        $form = $observer->getForm();
        $fieldset = $form->getElement('meta_fieldset');

        $helper = Mage::helper('ascurl');

        $fieldset->addField('sitemap_include', 'select', array(
            'name' => 'sitemap_include',
            'label' => $helper->__('Include in Google Sitemap'),
            'values' => array(
                array(
                    'value' => 0,
                    'label' => $helper->__('No'),
                ),
                array(
                    'value' => 1,
                    'label' => $helper->__('Yes'),
                )
            ),
        ));

        $fieldset->addField('meta_robots', 'select', array(
            'name' => 'meta_robots',
            'label' => $helper->__('Meta Robots'),
            'values' => AW_Ascurl_Model_Source_Metarobots::toOptionArray()
        ));
    }

    /**
     * Save 3 params to aw_ascurl_cms table which interacts to cms_page table
     * @param <type> $observer 
     */
    public function cmsPageSaveAfter($observer) {
        $page = $observer->getObject();
        Mage::getModel('ascurl/cms')
                ->load($page->getPageId(), 'page_id')
                ->setPageId($page->getPageId())
                ->setSitemapInclude($page->getSitemapInclude())
                ->setMetaRobots($page->getMetaRobots())
                ->save();
    }

    /**
     * Add to page object 3 params from aw_ascurl_cms table
     * @param object $observer 
     */
    public function cmsPageLoadAfter($observer) {
        $page = $observer->getObject();
        $ascurlPage = Mage::getModel('ascurl/cms')->load($page->getPageId(), 'page_id');
        $page->setSitemapInclude($ascurlPage->getSitemapInclude())
                ->setMetaRobots($ascurlPage->getMetaRobots());
        
    }

    /**
     * Add canonical url to the head of page (Not for Magento 1.3). 
     * Add metarobots to the head of cms pages
     * @param object $observer
     */
    public function addCannonicalUrlToHead($observer) {
        try {
            $block = $observer->getBlock();
            if ($block instanceof Mage_Catalog_Block_Product_View) {
                /* TODO: Remove when extension become 1.3 uncompatible */
                if (preg_match('/^1.3/', Mage::getVersion()))
                    return;

                $headBlock = $block->getLayout()->getBlock('head');
                if (Mage::helper('ascurl')->useCanonicalUrl()) {
                    $product = Mage::registry('product');
                    if (!$product)
                        return;
                    $params = array('_ignore_category' => true);
                    $headBlock->addLinkRel('canonical', $product->getUrlModel()->getUrl($product, $params));
                }
            }
            if ($block instanceof Mage_Catalog_Block_Category_View) {
                /* TODO: Remove when extension become 1.3 uncompatible */
                if (preg_match('/^1.3/', Mage::getVersion()))
                    return;
                $headBlock = $block->getLayout()->getBlock('head');
                if (Mage::helper('ascurl')->useCanonicalUrl()) {
                    $category = Mage::registry('current_category');
                    if (!$category)
                        return;
                    $headBlock->addLinkRel('canonical', $category->getUrl());
                }
            }
            if ($block instanceof Mage_Cms_Block_Page) {
                $head = $block->getLayout()->getBlock('head');
                if ($block->getPage()->getMetaRobots())
                    $head->setRobots(AW_Ascurl_Model_Source_Metarobots::$metarobots[$block->getPage()->getMetaRobots()]);
            }
        } catch (Exception $ex) {
            
        }
    }
    
    public function pageLoadBefore($observer) {

        if (!Mage::getStoreConfig('advanced/modules_disable_output/AW_Ascurl')) {

// models/catalog
            $catalog_rewrite_node = Mage::getConfig()->getNode('global/models/catalog/rewrite');
            $d_catalog_rewrite_node = Mage::getConfig()->getNode('global/models/catalog/d_rewrite_product_url/product_url');

            $catalog_rewrite_node->appendChild($d_catalog_rewrite_node);

// models/sitemap
            $sitemap_rewrite_node = Mage::getConfig()->getNode('global/models/sitemap/rewrite');
            $d_sitemap_rewrite_node = Mage::getConfig()->getNode('global/models/sitemap/d_rewrite_sitemap/sitemap');

            $sitemap_rewrite_node->appendChild($d_sitemap_rewrite_node);

// models/sitemap_mysql4 
            $sitemap_mysql4_cp_rewrite_node = Mage::getConfig()->getNode('global/models/sitemap_mysql4/rewrite');
            $d_sitemap_mysql4_cp_rewrite_node = Mage::getConfig()->getNode('global/models/sitemap_mysql4/d_rewrite_sitemap_mysql4');

            foreach ($d_sitemap_mysql4_cp_rewrite_node->children() as $dnode) {
                $sitemap_mysql4_cp_rewrite_node->appendChild($dnode);
            }

// models/sitemap
            $cms_page_node = Mage::getConfig()->getNode('global/blocks/adminhtml/rewrite');
            $d_cms_page_node = Mage::getConfig()->getNode('global/blocks/adminhtml/d_rewrite/cms_page_edit_tab_meta');

            $cms_page_node->appendChild($d_cms_page_node);
        }
    }
}