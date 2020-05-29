<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Preference\Model\Order\Pdf;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Payment\Helper\Data;
use Magento\Sales\Api\CreditmemoRepositoryInterface;
use Magento\Sales\Model\Order\Address\Renderer;
use Magento\Sales\Model\Order\Pdf\Config;
use Magento\Sales\Model\Order\Pdf\Creditmemo as BaseCreditmemo;
use Magento\Sales\Model\Order\Pdf\ItemsFactory;
use Magento\Sales\Model\Order\Pdf\Total\Factory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Unexpected\DeliveryTime\Helper\Render;

class Creditmemo extends BaseCreditmemo
{
    /** @var Render */
    private $render;

    /** @var Http */
    private $request;

    /** @var CreditmemoRepositoryInterface */
    private $creditmemoRepository;

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
     * @param Render $render
     * @param Http $request
     * @param CreditmemoRepositoryInterface $creditmemoRepository
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
        Render $render,
        Http $request,
        CreditmemoRepositoryInterface $creditmemoRepository,
        LoggerInterface $logger,
        array $data = []
    ) {
        $this->render = $render;
        $this->request = $request;
        $this->creditmemoRepository = $creditmemoRepository;
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
        $layout = $this->request->getFullActionName();
        $id = $this->request->getParam('creditmemo_id');
        $items = $this->getItems($id);

        $this->_setFontRegular($page, 10);
        $page->setFillColor(new \Zend_Pdf_Color_Rgb(0.93, 0.92, 0.92));
        $page->setLineColor(new \Zend_Pdf_Color_GrayScale(0.5));
        $page->setLineWidth(0.5);
        $page->drawRectangle(25, $this->y, 570, $this->y - 30);
        $this->y -= 10;
        $page->setFillColor(new \Zend_Pdf_Color_Rgb(0, 0, 0));

        //columns headers
        $lines[0][] = ['text' => __('Products'), 'feed' => 35];

        if ($this->render->canShowOnItems($layout, $items)) {
            $lines[0][] = ['text' => $this->render->getLabel(), 'feed' => 140];
        }

        $lines[0][] = [
            'text' => $this->string->split(__('SKU'), 12, true, true),
            'feed' => 255,
            'align' => 'right',
        ];

        $lines[0][] = [
            'text' => $this->string->split(__('Total (ex)'), 12, true, true),
            'feed' => 330,
            'align' => 'right',
        ];

        $lines[0][] = [
            'text' => $this->string->split(__('Discount'), 12, true, true),
            'feed' => 380,
            'align' => 'right',
        ];

        $lines[0][] = [
            'text' => $this->string->split(__('Qty'), 12, true, true),
            'feed' => 445,
            'align' => 'right',
        ];

        $lines[0][] = [
            'text' => $this->string->split(__('Tax'), 12, true, true),
            'feed' => 495,
            'align' => 'right',
        ];

        $lines[0][] = [
            'text' => $this->string->split(__('Total (inc)'), 12, true, true),
            'feed' => 565,
            'align' => 'right',
        ];

        $lineBlock = ['lines' => $lines, 'height' => 10];

        try {
            $this->drawLineBlocks($page, [$lineBlock], ['table_header' => true]);
        } catch (LocalizedException $e) {
            $this->logger->error($e->getMessage());
        }
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $this->y -= 20;
    }

    /**
     * @param int $creditmemoId
     * @return array
     */
    private function getItems(int $creditmemoId): array
    {
        $creditmemo = $this->creditmemoRepository->get($creditmemoId);
        $order = $creditmemo->getOrder();
        return $order->getAllItems();
    }
}
