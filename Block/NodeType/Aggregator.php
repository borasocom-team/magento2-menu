<?php

namespace Snowdog\Menu\Block\NodeType;

class Aggregator extends AbstractNode
{

    /**
     * @var string
     */
    protected $defaultTemplate = 'menu/node_type/aggregator.phtml';

    /**
     * @var string
     */
    protected $nodeType = 'aggregator';

    /**
     * @return string
     */
    public function getJsonConfig()
    {
        $data = [
            "snowMenuSimpleField" => [
                "type" => "aggregator"
            ]
        ];
        return $data;
    }

    /**
     * Fetch additional data required for rendering nodes.
     *
     * Should remember all nodes passed as $nodes param internally and store for use during rendering
     *
     * @param \Snowdog\Menu\Api\Data\NodeInterface[] $nodes
     *
     * @return void
     */
    public function fetchData(array $nodes)
    {

    }

    /**
     * Renders node content.
     *
     * @param int $nodeId ID of node to be rendered (based of data stored during fetchData() call)
     * @param int $level  in tree depth
     *
     * @return string
     */
    public function getHtml($nodeId, $level)
    {
        return <<<HTML
<a href="pippo" class="pluto" role="menuitem"><span>Cippo</span></a>
HTML;
    }

    /**
     * Returns label of "add node" button in edit form
     *
     * @return string|\Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __("Aggregator");
    }

}