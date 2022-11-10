<?php
namespace Wagento\Prefix\Model\ResourceModel;

class Type extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    protected $_entityTypeId = null;

    protected function _construct()
    {
        $this->_init("sales_sequence_meta", "meta_id");
    }

}