<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Sales
 * @copyright   Copyright (c) 2011 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Sales Order Invoice PDF model
 *
 * @category   Mage
 * @package    Mage_Sales
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Mage_Sales_Model_Order_Pdf_Invoice extends Mage_Sales_Model_Order_Pdf_Abstract
{
    public function getPdf($invoices = array())
    {
        $this->_beforeGetPdf();
        $this->_initRenderer('invoice');

        $pdf = new Zend_Pdf();
        $this->_setPdf($pdf);
        $style = new Zend_Pdf_Style();
        $this->_setFontBold($style, 10);

        foreach ($invoices as $invoice) {
            if ($invoice->getStoreId()) {
                Mage::app()->getLocale()->emulate($invoice->getStoreId());
                Mage::app()->setCurrentStore($invoice->getStoreId());
            }
            $page = $pdf->newPage(Zend_Pdf_Page::SIZE_LETTER_LANDSCAPE);
            $pdf->pages[] = $page;
            $this->y = $page->getHeight();

            $order = $invoice->getOrder();


            /* Add address */
            $this->insertAddress($page, $invoice->getStore());

            /* Add head */
            $this->insertOrder($page, $order, Mage::getStoreConfigFlag(self::XML_PATH_SALES_PDF_INVOICE_PUT_ORDER_ID, $order->getStoreId()));


            //$page->setFillColor(new Zend_Pdf_Color_GrayScale(1));
            $this->_setFontRegular($page);


            // Invoice number is here 
            $page->drawText(Mage::helper('sales')->__('') . $invoice->getIncrementId(), 270, 571, 'UTF-8');
            // create another copy of the invoice
            $page->drawText(Mage::helper('sales')->__('') . $invoice->getIncrementId(), 689, 571, 'UTF-8');
            

            /* Add table */
            //$page->setFillColor(new Zend_Pdf_Color_RGB(0.93, 0.92, 0.92));
            //$page->setLineColor(new Zend_Pdf_Color_GrayScale(0.5));
            //$page->setLineWidth(0.5);

            //$page->drawRectangle(25, $this->y, 570, $this->y -15);
            //$this->y -=110;

            /* Add table head */
            //$page->setFillColor(new Zend_Pdf_Color_RGB(0.4, 0.4, 0.4));
            /*
            $page->drawText(Mage::helper('sales')->__('Products'), 35, $this->y, 'UTF-8');
            $page->drawText(Mage::helper('sales')->__('SKU'), 255, $this->y, 'UTF-8');
            $page->drawText(Mage::helper('sales')->__('Price'), 380, $this->y, 'UTF-8');
            $page->drawText(Mage::helper('sales')->__('Qty'), 430, $this->y, 'UTF-8');
            $page->drawText(Mage::helper('sales')->__('Tax'), 480, $this->y, 'UTF-8');
            $page->drawText(Mage::helper('sales')->__('Subtotal'), 535, $this->y, 'UTF-8');*/

            // set Y for the items 
            $this->y = 430;

            //$page->setFillColor(new Zend_Pdf_Color_GrayScale(0));

            // ITEMS
            /* Add body */
            foreach ($invoice->getAllItems() as $item){
                if ($item->getOrderItem()->getParentItem()) {
                    continue;
                }

                if ($this->y < 175) {
                    $page = $this->newPage(array('table_header' => true));
                }

                /* Draw item */
                $page = $this->_drawItem($item, $page, $order);
            }

            // TOTALS
            /* Add totals */
            $this->y = 200;

            $page = $this->insertTotals($page, $invoice);

            if ($invoice->getStoreId()) {
                Mage::app()->getLocale()->revert();
            }
        }

        $this->_afterGetPdf();

        return $pdf;
    }

    /**
     * Create new page and assign to PDF object
     *
     * @param array $settings
     * @return Zend_Pdf_Page
     */
    public function newPage(array $settings = array())
    {
        /* Add new table head */
        $page = $this->_getPdf()->newPage(Zend_Pdf_Page::SIZE_LETTER_LANDSCAPE);
        $this->_getPdf()->pages[] = $page;
        //$this->y = $page->getHeight();

        // set Y for the items
        $this->y = 430;


        /*
        if (!empty($settings['table_header'])) {
            $this->_setFontRegular($page);
            //$page->setFillColor(new Zend_Pdf_Color_RGB(0.93, 0.92, 0.92));
            //$page->setLineColor(new Zend_Pdf_Color_GrayScale(0.5));
            //$page->setLineWidth(0.5);
            //$page->drawRectangle(25, $this->y, 570, $this->y-15);
            $this->y -=110;

            //$page->setFillColor(new Zend_Pdf_Color_RGB(0.4, 0.4, 0.4));
            $page->drawText(Mage::helper('sales')->__('Product'), 35, $this->y, 'UTF-8');
            $page->drawText(Mage::helper('sales')->__('SKU'), 255, $this->y, 'UTF-8');
            $page->drawText(Mage::helper('sales')->__('Price'), 380, $this->y, 'UTF-8');
            $page->drawText(Mage::helper('sales')->__('Qty'), 430, $this->y, 'UTF-8');
            $page->drawText(Mage::helper('sales')->__('Tax'), 480, $this->y, 'UTF-8');
            $page->drawText(Mage::helper('sales')->__('Subtotal'), 535, $this->y, 'UTF-8');

            //$page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
            $this->y -=20;
        }*/

        return $page;
    }

    protected function insertTotals($page, $source)
    {
        $this->_setFontRegular($page);

        $order = $source->getOrder();
        // SUBTOTAL
        $page->drawText(Mage::helper('sales')->__(number_format($source->getSubtotal(),2,'.',',')), 285, 148);
        // PROMOTION
        $page->drawText(Mage::helper('sales')->__(number_format($source->getDiscountAmount(),2,'.',',')), 285, 132);
        // TAX
        $page->drawText(Mage::helper('sales')->__(number_format($source->getTaxAmount(),2,'.',',')), 285,116);
        // SHIPPING
        $page->drawText(Mage::helper('sales')->__(number_format($source->getShippingAmount(),2,'.',',')), 285, 100);
        // TOTAL
        $page->drawText(Mage::helper('sales')->__(number_format($source->getBaseGrandTotal(),2,'.',',')), 285, 84);
        // PAYMENT METHOD
        $page->drawText(Mage::helper('sales')->__($source->getOrder()->getPayment()->getMethod()), 285, 69);


    }
}
