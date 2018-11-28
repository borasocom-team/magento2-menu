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

use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Catalog\Model\Category as CoreCategory;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Store\Model\Store;

class CategoryPriceFiltered extends Category
{
    /**
     * @param int   $storeId
     * @param array $categoryIds
     *
     * @return array
     */
    public function fetchData($storeId = Store::DEFAULT_STORE_ID, $categoryIds = [])
    {
        $connection = $this->getConnection('read');
        $table = $this->getTable('url_rewrite');
        $select = $connection
            ->select()
            ->from($table, ['entity_id', 'request_path'])
            ->where('entity_type = ?', 'category')
            ->where('redirect_type = ?', 0)
            ->where('store_id = ?', $storeId)
            ->where('entity_id IN (' . implode(',', $categoryIds) . ')');

        $categoryUrls = $connection->fetchPairs($select);

        foreach ($categoryUrls as $index => $categoryUrl){
            $categoryUrls[$index] = $categoryUrl . '?price=-100';
        }

        return $categoryUrls;
    }
}
