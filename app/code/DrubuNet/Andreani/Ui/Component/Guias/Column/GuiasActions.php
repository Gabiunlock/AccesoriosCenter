<?php
namespace DrubuNet\Andreani\Ui\Component\Guias\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
use DrubuNet\Andreani\Helper\Data as AndreaniHelper;

class GuiasActions extends Column
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var AndreaniHelper
     */
    protected $_andreaniHelper;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        AndreaniHelper $andreaniHelper,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder       = $urlBuilder;
        $this->_andreaniHelper  = $andreaniHelper;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $helper = $this->_andreaniHelper;
            $storeId = $this->context->getFilterParam('store_id');

            foreach ($dataSource['data']['items'] as &$item) {
                $orderShipment = $helper->loadByIncrementId($item['increment_id']);

                if($orderShipment->getAndreaniDatosGuia())
                {
                    $item[$this->getData('name')]['imprimir'] = [
                        'href' => $this->urlBuilder->getUrl(
                            'andreani/guias/imprimir',
                            [
                                'id'            => $item['entity_id'],
                                'increment_id'  => $item['increment_id'],
                                'order_id'      => $orderShipment->getOrderId(),
                                'store'         => $storeId]
                        ),
                        'label'     => __('Imprimir Guía Andreani'),
                        'hidden'    => false,
                    ];
                }

            }
        }

        return $dataSource;
    }
}
