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

require_once 'Mage/Adminhtml/controllers/Catalog/ProductController.php';

class AW_Ascurl_Adminhtml_IndexController extends Mage_Adminhtml_Catalog_ProductController
{
    /**
     * Get categories tree node AJAX
     */
    public function categoriesJsonAction()
    {
        $this->_initProduct();

        $this->getResponse()->setBody(
                $this->getLayout()->createBlock('ascurl/adminhtml_catalog_product_edit_tab_ascurl')
                    ->setOwnerTreeId(AW_Ascurl_Block_Adminhtml_Catalog_Product_Edit_Tab_Ascurl::OWNER_TREE_ID)
                    ->getCategoryChildrenJson($this->getRequest()->getParam('category'))
            );
    }
}