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

class AW_Ascurl_Model_Sitemap extends Mage_Sitemap_Model_Sitemap
{
    const MAX_URL_COUNT = 'sitemap/advancedseo/max_url_quantity_per_file';
    const MAX_FILE_SIZE = 'sitemap/advancedseo/max_file_size';
    const USE_IMAGES = 'sitemap/advancedseo/use_images';

    const URLSET = 'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"';

    protected $fileNumber = 0;
    protected $currentUrlCount = 0;
    protected $currentFileSize = 0;
    protected $maxUrlCount;
    protected $maxFileSize;
    protected $filenamesForIndexSitemap = array();
    protected $io;

    /**
     * Create new xml file with several begin tags
     * @return Varien_Io_File 
     */
    public function createFile()
    {
        $fileToCreate = $this->getSitemapFilename();

        $io = new Varien_Io_File();
        $io->setAllowCreateFolders(true);
        $io->open(array('path' => $this->getPath()));

        if ($this->fileNumber)
            $fileToCreate = Mage::helper('ascurl')->insertStringToFilename($this->getSitemapFilename(), '_'.$this->fileNumber);

        if ($io->fileExists($fileToCreate) && !$io->isWriteable($fileToCreate)) {
            Mage::throwException(Mage::helper('sitemap')->__('File "%s" cannot be saved. Please, make sure the directory "%s" is writeable by web server.', $fileToCreate, $this->getPath()));
        }

        $io->streamOpen($fileToCreate);

        $this->filenamesForIndexSitemap[] = $fileToCreate;

        $io->streamWrite('<?xml version="1.0" encoding="UTF-8"?>' . "\n");
        $io->streamWrite('<urlset '.self::URLSET.'>');

        $this->io = $io;
    }

    /**
     * Create additional xml index file with links to other xml files (if number of them more than 1)
     */
    public function createIndexSitemapFile()
    {
        if (sizeof($this->filenamesForIndexSitemap) > 1)
        {
            $io = new Varien_Io_File();
            $io->setAllowCreateFolders(true);
            $io->open(array('path' => $this->getPath()));

            $fileToCreate = Mage::helper('ascurl')->insertStringToFilename($this->getSitemapFilename(), '_index');

            if ($io->fileExists($fileToCreate) && !$io->isWriteable($fileToCreate)) {
            Mage::throwException(Mage::helper('sitemap')->__('File "%s" cannot be saved. Please, make sure the directory "%s" is writeable by web server.', $fileToCreate, $this->getPath()));
            }

            $io->streamOpen($fileToCreate);

            $io->streamWrite('<?xml version="1.0" encoding="UTF-8"?>' . "\n");
            $io->streamWrite('<sitemapindex '.self::URLSET.'>');

            $storeId = $this->getStoreId();
            $baseUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
            $date    = Mage::getSingleton('core/date')->gmtDate('Y-m-d');
            $path = $this->getSitemapPath();
            $fullPath = preg_replace('/(?<=[^:])\/{2,}/', '/', $baseUrl.$path);

            foreach ($this->filenamesForIndexSitemap as $item) {
                $xml = sprintf('<sitemap><loc>%s</loc><lastmod>%s</lastmod></sitemap>',
                    htmlspecialchars($fullPath . $item),
                    $date
                );
                $io->streamWrite($xml);
            }

            $io->streamWrite('</sitemapindex>');
            $io->streamClose();
        }
    }

    /**
     * Close xml file. Set to zero current url and current file size counters. Increments fileNumber.
     * @param Varien_Io_File $io 
     */
    public function closeFile()
    {
        $this->io->streamWrite('</urlset>');
        $this->io->streamClose();
        $this->fileNumber++;
        $this->currentUrlCount = $this->currentFileSize = 0;
    }

    /**
     * Returns true if count of urls in xml more than count of urls in ext configuration
     * @return bool
     */
    public function checkUrlCount()
    {
        if (is_null($this->maxUrlCount))
            $this->maxUrlCount = Mage::getStoreConfig(self::MAX_URL_COUNT, $this->getStoreId());

        if ($this->maxUrlCount == 0) return false;

        if ($this->currentUrlCount > $this->maxUrlCount) return true;
        return false;
    }

    /**
     * Returns true if xml file size more than max size at ext configuration
     * @param int $sizeAdd
     * @return bool
     */
    public function checkFileSize($sizeAdd)
    {
        if (is_null($this->maxFileSize))
            $this->maxFileSize = Mage::getStoreConfig(self::MAX_FILE_SIZE, $this->getStoreId());

        if ($this->maxFileSize == 0) return false;

        if ($this->currentFileSize > $this->maxFileSize*1024-450) return true;
        return false;
    }

    protected function updateCounters($sizeAdd)
    {
        $this->currentUrlCount ++;
        $this->currentFileSize += $sizeAdd;
    }
    /**
     * Generate XML file
     *
     * @return Mage_Sitemap_Model_Sitemap
     */
    public function generateXml()
    {
        $this->createFile();

        $storeId = $this->getStoreId();
        $date    = Mage::getSingleton('core/date')->gmtDate('Y-m-d');
        $baseUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);

        $useImages = Mage::getStoreConfig(self::USE_IMAGES, $storeId);

        /**
         * Generate categories sitemap
         */
        $changefreq = (string)Mage::getStoreConfig('sitemap/category/changefreq', $storeId);
        $priority   = (string)Mage::getStoreConfig('sitemap/category/priority', $storeId);
        $collection = Mage::getResourceModel('sitemap/catalog_category')->getCollection($storeId);
        foreach ($collection as $item) {
            $_im = Mage::getModel('catalog/category')->load($item->getId())->getImageUrl();
            if ($_im && $useImages)
            {
                $xml = sprintf('<url><loc>%s</loc><lastmod>%s</lastmod><image:image><image:loc>%s</image:loc></image:image><changefreq>%s</changefreq><priority>%.1f</priority></url>'. "\n",
                    htmlspecialchars($baseUrl . $item->getUrl()),
                    $date,
                    $_im,
                    $changefreq,
                    $priority
                    );
            }
            else
            {
                $xml = sprintf('<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>',
                    htmlspecialchars($baseUrl . $item->getUrl()),
                    $date,
                    $changefreq,
                    $priority
                    );
            }
            $this->sitemapFileAddLine($xml);
        }
        unset($collection);

        /**
         * Generate products sitemap
         */
        $changefreq = (string)Mage::getStoreConfig('sitemap/product/changefreq', $storeId);
        $priority   = (string)Mage::getStoreConfig('sitemap/product/priority', $storeId);
        $collection = Mage::getResourceModel('sitemap/catalog_product')->getCollection($storeId);
        foreach ($collection as $item) {
            if ($useImages && $item->getImageUrl())
            {
                $xml = sprintf('<url><loc>%s</loc><lastmod>%s</lastmod><image:image><image:loc>%s</image:loc></image:image><changefreq>%s</changefreq><priority>%.1f</priority></url>',
                    htmlspecialchars($baseUrl . $item->getUrl()),
                    $date,
                    $item->getImageUrl(),
                    $changefreq,
                    $priority
                );
            }
            else
            {
                $xml = sprintf('<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>',
                    htmlspecialchars($baseUrl . $item->getUrl()),
                    $date,
                    $changefreq,
                    $priority
                );
            }
            $this->sitemapFileAddLine($xml);
        }
        unset($collection);

        /**
         * Generate cms pages sitemap
         */
        $changefreq = (string)Mage::getStoreConfig('sitemap/page/changefreq', $storeId);
        $priority   = (string)Mage::getStoreConfig('sitemap/page/priority', $storeId);
        $collection = Mage::getResourceModel('sitemap/cms_page')->getCollection($storeId);
        foreach ($collection as $item) {
            $xml = sprintf('<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>',
                htmlspecialchars($baseUrl . $item->getUrl()),
                $date,
                $changefreq,
                $priority
            );
            $this->sitemapFileAddLine($xml);
        }
        unset($collection);

        Mage::dispatchEvent('sitemap_add_xml_block_to_the_end', array('sitemap_object' => $this));

        $this->closeFile();
        $this->createIndexSitemapFile();

        $this->setSitemapTime(Mage::getSingleton('core/date')->gmtDate('Y-m-d H:i:s'));
        $this->save();

        return $this;
    }

    public function sitemapFileAddLine($xml)
    {
        $xmlLength = strlen($xml);
        $this->updateCounters($xmlLength);
        if ($this->checkUrlCount() || $this->checkFileSize($xmlLength))
        {
            $this->closeFile();
            $this->createFile();
            $this->updateCounters($xmlLength);
        }
        $this->io->streamWrite($xml);
    }
}
