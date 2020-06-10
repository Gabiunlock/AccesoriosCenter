<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_ImageOptimizer
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\ImageOptimizer\Ui\Component;

use JsonSerializable;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\AbstractComponent;
use Mageplaza\ImageOptimizer\Helper\Data;

/**
 * Class Action
 * @package Mageplaza\ImageOptimizer\Ui\Component
 */
class Action extends AbstractComponent
{
    const NAME = 'action';

    /**
     * @var array|JsonSerializable
     */
    protected $actions;

    /**
     * @var Data
     */
    protected $helperData;

    /**
     * Action constructor.
     *
     * @param ContextInterface $context
     * @param Data $helperData
     * @param array $components
     * @param array $data
     * @param null $actions
     */
    public function __construct(
        ContextInterface $context,
        Data $helperData,
        array $components = [],
        array $data = [],
        $actions = null
    ) {
        parent::__construct($context, $components, $data);
        $this->actions    = $actions;
        $this->helperData = $helperData;
    }

    /**
     * @inheritDoc
     */
    public function prepare()
    {
        if (!$this->helperData->isEnabled()) {
            $this->setData('config', []);
        }

        if (!empty($this->actions)) {
            $this->setData('config', array_replace_recursive(['actions' => $this->actions], $this->getConfiguration()));
        }

        parent::prepare();
    }

    /**
     * Get component name
     *
     * @return string
     */
    public function getComponentName()
    {
        return static::NAME;
    }
}
