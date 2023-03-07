<?php
namespace Wagento\Prefix\Model;

use Magento\Framework\Model\AbstractModel;

class Store extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Store Constructor
     */
    protected function _construct()
    {
        $this->_init(
            \Wagento\Prefix\Model\ResourceModel\Store::class
        );
    }
}
