<?php
/**
 * Snowdog
 *
 * @author      Paweł Pisarek <pawel.pisarek@snow.dog>.
 * @category
 * @package
 * @copyright   Copyright Snowdog (http://snow.dog)
 */

namespace Snowdog\Menu\Model\ResourceModel\NodeType;

use Dmatthew\Brand\Api\Data\BrandInterface;
use Dmatthew\Brand\Model\Brand as CoreBrand;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Store\Model\Store;

class Brand extends AbstractNode
{
    /**
     * @var MetadataPool
     */
    private $metadataPool;

    /**
     * @param ResourceConnection $resource
     * @param MetadataPool $metadataPool
     */
    public function __construct(
        ResourceConnection $resource,
        MetadataPool $metadataPool
    ) {
        $this->metadataPool = $metadataPool;
        parent::__construct($resource);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function fetchConfigData()
    {
        $metadata = $this->metadataPool->getMetadata(BrandInterface::class);
        $identifierField = $metadata->getIdentifierField();
        $linkField = $metadata->getLinkField();
        $connection = $this->getConnection('read');

        $select = $connection->select()->from(
            ['a' => $this->getTable('eav_attribute')],
            ['attribute_id']
        )->join(
            ['t' => $this->getTable('eav_entity_type')],
            't.entity_type_id = a.entity_type_id',
            []
        )->where('t.entity_type_code = ?', CoreBrand::ENTITY)->where(
            'a.attribute_code = ?',
            'name'
        );

        $nameAttributeId = $connection->fetchOne($select);

        $select = $connection->select()->from(
            ['e' => $this->getTable('brand_entity')],
            [$identifierField]
        )->join(
            ['v' => $this->getTable('brand_entity_varchar')],
            'v.' . $linkField . ' = e.' . $linkField . ' AND v.store_id = 0 
            AND v.attribute_id = ' . $nameAttributeId,
            ['name' => 'v.value']
        )->order(
            'e.' . $linkField . ' DESC'
        );

        return $connection->fetchAll($select);
    }

    /**
     * @param int   $storeId
     * @param array $brandIds
     *
     * @return array
     */
    public function fetchData($storeId = Store::DEFAULT_STORE_ID, $brandIds = [])
    {
        $connection = $this->getConnection('read');
        $table = $this->getTable('url_rewrite');
        $select = $connection
            ->select()
            ->from($table, ['entity_id', 'request_path'])
            ->where('entity_type = ?', 'brand')
            ->where('redirect_type = ?', 0)
            ->where('store_id = ?', $storeId)
            ->where('entity_id IN (' . implode(',', $brandIds) . ')');

        return $connection->fetchPairs($select);
    }
}
