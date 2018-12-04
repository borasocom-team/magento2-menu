<?php
/**
 * Snowdog
 *
 * @author      Paweł Pisarek <pawel.pisarek@snow.dog>.
 * @brand
 * @package
 * @copyright   Copyright Snowdog (http://snow.dog)
 */

namespace Snowdog\Menu\Model\NodeType;

use Dmatthew\Brand\Api\Data\BrandInterface;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Profiler;

class Brand extends AbstractNode
{
    /**
     * @var MetadataPool
     */
    private $metadataPool;

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('Snowdog\Menu\Model\ResourceModel\NodeType\Brand');
        parent::_construct();
    }

    /**
     * Brand constructor.
     *
     * @param Profiler $profiler
     * @param MetadataPool $metadataPool
     */
    public function __construct(
        Profiler $profiler,
        MetadataPool $metadataPool
    ) {
        $this->metadataPool = $metadataPool;
        parent::__construct($profiler);
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function fetchConfigData()
    {
        $this->profiler->start(__METHOD__);
        $metadata = $this->metadataPool->getMetadata(BrandInterface::class);
        $identifierField = $metadata->getIdentifierField();

        $data = $this->getResource()->fetchConfigData();
        $labels = [];

        foreach ($data as $row) {
            $label = [];
            $label[] = $row['name'];
            $labels[$row[$identifierField]] = $label;
        }

        $fieldOptions = [];
        foreach ($labels as $id => $label) {
            $fieldOptions[] = [
                'label' => $label = implode(' > ', $label),
                'value' => $id
            ];
        }

        $data = [
            'snowMenuAutoCompleteField' => [
                'type'    => 'brand',
                'options' => $fieldOptions,
                'message' => __('Brand not found'),
            ],
        ];

        $this->profiler->stop(__METHOD__);

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function fetchData(array $nodes, $storeId)
    {
        $this->profiler->start(__METHOD__);

        $localNodes = [];
        $brandUrls = [];

        foreach ($nodes as $node) {
            $localNodes[$node->getId()] = $node;
            $brandUrls[] = (int)$node->getContent();
        }

        $brandUrls = $this->getResource()->fetchData($storeId, $brandUrls);

        $this->profiler->stop(__METHOD__);

        return [$localNodes, $brandUrls];
    }
}
