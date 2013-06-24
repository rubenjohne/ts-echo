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


/**
 * Sitemap cms page edit 'Meta' tab block
 */
class AW_Ascurl_Block_Adminhtml_Cms_Page_Edit_Tab_Meta
    extends Mage_Adminhtml_Block_Cms_Page_Edit_Tab_Meta
{
    /**
     * Hack for Magento 1.3. 2 additional fields added
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        if(!preg_match('/^1.3/', Mage::getVersion()))
            return parent::_prepareForm();

        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('page_');

        $model = Mage::registry('cms_page');

        $fieldset = $form->addFieldset('meta_fieldset', array('legend' => Mage::helper('cms')->__('Meta Data'), 'class' => 'fieldset-wide'));

    	$fieldset->addField('meta_keywords', 'editor', array(
            'name' => 'meta_keywords',
            'label' => Mage::helper('cms')->__('Keywords'),
            'title' => Mage::helper('cms')->__('Meta Keywords'),
        ));

    	$fieldset->addField('meta_description', 'editor', array(
            'name' => 'meta_description',
            'label' => Mage::helper('cms')->__('Description'),
            'title' => Mage::helper('cms')->__('Meta Description'),
        ));

        $helper = Mage::helper('ascurl');

        $fieldset->addField('sitemap_include', 'select', array(
            'name'      => 'sitemap_include',
            'label'     => $helper->__('Include in Google Sitemap'),
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
            'name'      => 'meta_robots',
            'label'     => $helper->__('Meta Robots'),
            'values'    => AW_Ascurl_Model_Source_Metarobots::toOptionArray()
        ));

        $form->setValues($model->getData());

        $this->setForm($form);

        return Mage_Adminhtml_Block_Widget_Form::_prepareForm();
    }

}
