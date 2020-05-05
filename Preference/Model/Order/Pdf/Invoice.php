<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Preference\Model\Order\Pdf;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Payment\Helper\Data;
use Magento\Sales\Model\Order\Address\Renderer;
use Magento\Sales\Model\Order\Pdf\Config;
use Magento\Sales\Model\Order\Pdf\Invoice as BaseInvoice;
use Magento\Sales\Model\Order\Pdf\ItemsFactory;
use Magento\Sales\Model\Order\Pdf\Total\Factory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class Invoice extends BaseInvoice
{
    /** @var LoggerInterface */
    private $logger;

    /**
     * @param Data $paymentData
     * @param StringUtils $string
     * @param ScopeConfigInterface $scopeConfig
     * @param Filesystem $filesystem
     * @param Config $pdfConfig
     * @param Factory $pdfTotalFactory
     * @param ItemsFactory $pdfItemsFactory
     * @param TimezoneInterface $localeDate
     * @param StateInterface $inlineTranslation
     * @param Renderer $addressRenderer
     * @param StoreManagerInterface $storeManager
     * @param ResolverInterface $localeResolver
     * @param LoggerInterface $logger
     * @param array $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        Data $paymentData,
        StringUtils $string,
        ScopeConfigInterface $scopeConfig,
        Filesystem $filesystem,
        Config $pdfConfig,
        Factory $pdfTotalFactory,
        ItemsFactory $pdfItemsFactory,
        TimezoneInterface $localeDate,
        StateInterface $inlineTranslation,
        Renderer $addressRenderer,
        StoreManagerInterface $storeManager,
        ResolverInterface $localeResolver,
        LoggerInterface $logger,
        array $data = []
    )
    {
        $this->logger = $logger;
        parent::__construct(
            $paymentData,
            $string,
            $scopeConfig,
            $filesystem,
            $pdfConfig,
            $pdfTotalFactory,
            $pdfItemsFactory,
            $localeDate,
            $inlineTranslation,
            $addressRenderer,
            $storeManager,
            $localeResolver,
            $data
        );
    }

    /**
     * @inheritDoc
     */
    protected function _drawHeader(\Zend_Pdf_Page $page): void
    {
        /* Add table head */
        $this->_setFontRegular($page, 10);
        $page->setFillColor(new \Zend_Pdf_Color_Rgb(0.93, 0.92, 0.92));
        $page->setLineColor(new \Zend_Pdf_Color_GrayScale(0.5));
        $page->setLineWidth(0.5);
        $page->drawRectangle(25, $this->y, 570, $this->y - 15);
        $this->y -= 10;
        $page->setFillColor(new \Zend_Pdf_Color_Rgb(0, 0, 0));

        //columns headers
        $lines[0][] = ['text' => __('Products'), 'feed' => 35];

        $lines[0][] = ['text' => __('SKU'), 'feed' => 290, 'align' => 'right'];

        $lines[0][] = ['text' => __('Delivery Time'), 'feed' => 220, 'align' => 'right'];

        $lines[0][] = ['text' => __('Qty'), 'feed' => 435, 'align' => 'right'];

        $lines[0][] = ['text' => __('Price'), 'feed' => 360, 'align' => 'right'];

        $lines[0][] = ['text' => __('Tax'), 'feed' => 495, 'align' => 'right'];

        $lines[0][] = ['text' => __('Subtotal'), 'feed' => 565, 'align' => 'right'];

        $lineBlock = ['lines' => $lines, 'height' => 5];

        try {
            $this->drawLineBlocks($page, [$lineBlock], ['table_header' => true]);
        } catch (LocalizedException $e) {
            $this->logger->error($e->getMessage());
        }
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $this->y -= 20;
    }
}
