<?php
namespace Wagento\Prefix\Model;

use Magento\Framework\Model\AbstractModel;

class Type extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Type Constructor
     */
    protected function _construct()
    {
        $this->_init(
            \Wagento\Prefix\Model\ResourceModel\Type::class
        );
    }
}
