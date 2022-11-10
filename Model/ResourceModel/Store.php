<?php
namespace Wagento\Prefix\Model\ResourceModel;

class Store extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    protected $_store = null;

    protected function _construct()
    {
        $this->_init("sales_sequence_profile", "profile_id");
    }

}