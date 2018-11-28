<?php
/**
 * Snowdog
 *
 * @author      Paweł Pisarek <pawel.pisarek@snow.dog>.
 * @category
 * @package
 * @copyright   Copyright Snowdog (http://snow.dog)
 */

namespace Snowdog\Menu\Model\NodeType;

use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Profiler;

class CategoryPriceFiltered extends Category
{

    public function _construct()
    {
        $this->_init('Snowdog\Menu\Model\ResourceModel\NodeType\CategoryPriceFiltered');

        parent::_construct();
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function fetchConfigData()
    {
        $data = parent::fetchConfigData();

        $data['snowMenuAutoCompleteField']['type'] = 'category_price_filtered';

        return $data;
    }

}