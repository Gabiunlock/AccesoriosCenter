<?php
/**
 * Created by PhpStorm.
 * User: drubunet
 * Date: 22/09/16
 * Time: 13:23
 */
namespace DrubuNet\Andreani\Model\ResourceModel\Grid;
class Grid extends \Magento\Backend\Block\Widget\Grid
{
    protected function _prepareCollection()
    {
        if ($this->getCollection()) {
            foreach ($this->getDefaultFilter() as $field => $value) {
                $this->getCollection()->addFieldToFilter($field, $value);
            }
        }
        return parent::_prepareCollection();
    }
}