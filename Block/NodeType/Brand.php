<?php

namespace Snowdog\Menu\Block\NodeType;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;
use Snowdog\Menu\Model\TemplateResolver;
use Snowdog\Menu\Model\NodeType\Brand as ModelBrand;
use Dmatthew\Brand\Model\BrandFactory;

class Brand extends AbstractNode
{
    /**
     * @var string
     */
    protected $defaultTemplate = 'menu/node_type/brand.phtml';

    /**
     * @var string
     */
    protected $nodeType = 'brand';
    /**
     * @var array
     */
    protected $nodes;
    /**
     * @var array
     */
    protected $brandUrls;
    /**
     * @var Registry
     */
    private $coreRegistry;

    /**
     * @var ModelBrand
     */
    private $_brandModel;

    protected $brandFactory;

    /**
     * Brand constructor.
     *
     * @param Context $context
     * @param Registry $coreRegistry
     * @param ModelBrand $brandModel
     * @param TemplateResolver $templateResolver
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        ModelBrand $brandModel,
        TemplateResolver $templateResolver,
        BrandFactory $brandFactory,
        array $data = []
    ) {
        parent::__construct($context, $templateResolver, $data);
        $this->coreRegistry = $coreRegistry;
        $this->_brandModel = $brandModel;
        $this->brandFactory = $brandFactory;
    }

    /**
     * @return \Magento\Catalog\Model\Brand|null
     */
    public function getCurrentBrand()
    {
        return $this->coreRegistry->registry('current_brand');
    }

    /**
     * @return array
     */
    public function getNodeCacheKeyInfo()
    {
        $info = [
            'module_' . $this->getRequest()->getModuleName(),
            'controller_' . $this->getRequest()->getControllerName(),
            'route_' . $this->getRequest()->getRouteName(),
            'action_' . $this->getRequest()->getActionName()
        ];

        $brand = $this->getCurrentBrand();
        if ($brand) {
            $info[] = 'brand_' . $brand->getId();
        }

        return $info;
    }

    /**
     * @return string
     */
    public function getJsonConfig()
    {
        $data = $this->_brandModel->fetchConfigData();

        return $data;
    }

    /**
     * @param array $nodes
     */
    public function fetchData(array $nodes)
    {
        $storeId = $this->_storeManager->getStore()->getId();

        list($this->nodes, $this->brandUrls) = $this->_brandModel->fetchData($nodes, $storeId);
    }

    /**
     * @param int $nodeId
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function isCurrentBrand($nodeId)
    {
        if (!isset($this->nodes[$nodeId])) {
            throw new \InvalidArgumentException('Invalid node identifier specified');
        }

        $node = $this->nodes[$nodeId];
        $brandId = (int) $node->getContent();
        $currentBrand = $this->getCurrentBrand();

        return $currentBrand
            ? $currentBrand->getId() == $brandId
            : false;
    }

    /**
     * @param int $nodeId
     * @param int|null $storeId
     * @return string|false
     * @throws \InvalidArgumentException
     */
    public function getBrandUrl($nodeId, $storeId = null)
    {
        // $storeId mainteined as backward compatibility
        if (!isset($this->nodes[$nodeId])) {
            throw new \InvalidArgumentException('Invalid node identifier specified');
        }

        $node = $this->nodes[$nodeId];
        $brandId = (int) $node->getContent();
        /** @var \Dmatthew\Brand\Model\Brand $brandObject */
        $brandObject = $this->brandFactory->create()->loadByAttribute('entity_id', $brandId);

        return $brandObject->getUrl();
    }

    /**
     * @param int $nodeId
     * @param int $level
     * @param int $storeId
     *
     * @return string
     */
    public function getHtml($nodeId, $level, $storeId = null)
    {
        $classes = $level == 0 ? 'level-top' : '';
        $node = $this->nodes[$nodeId];
        $url = $this->getBrandUrl($nodeId, $storeId);
        $title = $node->getTitle();

        return <<<HTML
<a href="$url" class="$classes" role="menuitem"><span>$title</span></a>
HTML;
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __("Brand");
    }
}
