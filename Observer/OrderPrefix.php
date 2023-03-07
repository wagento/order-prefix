<?php
namespace Wagento\Prefix\Observer;

class OrderPrefix implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * Observer for event
     *
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Wagento\Prefix\Model\TypeFactory
     */
    protected $typeModel;

    /**
     *
     * @var \Wagento\Prefix\Model\StoreFactory
     *
     */
    protected $storeModel;

    /**
     * OrderPrefix Constructor
     *
     * @param \Wagento\Prefix\Model\TypeFactory $typeModel
     * @param \Wagento\Prefix\Model\StoreFactory $storeModel
     * @param \Magento\Framework\App\RequestInterface $request
     */
    public function __construct(
        \Wagento\Prefix\Model\TypeFactory $typeModel,
        \Wagento\Prefix\Model\StoreFactory $storeModel,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->request = $request;
        $this->typeModel = $typeModel;
        $this->storeModel = $storeModel;
    }

    /**
     * Order Prefix execute function
     *
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $postValue = $this->request->getParams();
        $previousPrefix = [];
        $orders = [
            "order",
            "invoice",
            "shipment",
            "creditmemo"
        ];
        foreach ($orders as $item) {
            $collection = $this->typeModel->create()
                ->getCollection()
                ->addFieldToFilter("entity_type", $item)
                ->addFieldToFilter("store_id", 1)
                ->getFirstItem();
            $data = $this->storeModel->create()
                ->getCollection()
                ->addFieldToFilter("meta_id", $collection->getMetaId())
                ->getFirstItem();
            $previousPrefix[$item] = $data->getPrefix();
        }
        $storeId = 1;
        if (isset($postValue["store"])) {
            $storeId = $postValue["store"];
        }
        $prefix = [];
        $prefix["order"] = $postValue["groups"]["configuration"]["fields"]["order"]["value"] ?? $previousPrefix["order"];
        $prefix["invoice"] = $postValue["groups"]["configuration"]["fields"]["invoice"]["value"] ?? $previousPrefix["invoice"];
        $prefix["shipment"] = $postValue["groups"]["configuration"]["fields"]["shipment"]["value"] ?? $previousPrefix["shipment"];
        $prefix["creditmemo"] = $postValue["groups"]["configuration"]["fields"]["creditmemo"]["value"] ?? $previousPrefix["creditmemo"];
        foreach ($prefix as $key => $value) {
            $collection = $this->typeModel->create()
                ->getCollection()
                ->addFieldToFilter("entity_type", $key)
                ->addFieldToFilter("store_id", $storeId)
                ->getFirstItem();
            $metaId = $collection->getMetaId();
            $data = $this->storeModel->create()
                ->getCollection()
                ->addFieldToFilter("meta_id", $metaId)
                ->getFirstItem();
            if ($data->getId()) {
                $profileId = $data->getProfileId();
                $model = $this->storeModel->create()->load($profileId);
                $model->setPrefix($value)->save();
            }
        }
    }
}
