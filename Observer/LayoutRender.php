<?php

declare(strict_types=1);

namespace Magelearn\Story\Observer;

use Magelearn\Story\Model\ConfigProvider;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\LayoutInterface;

class LayoutRender implements ObserverInterface
{
    public const HEADER_LINKS = 'header.links';
    public const TOP_LINKS = 'top.links';

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var LayoutInterface
     */
    private $layout;

    public function __construct(
        ConfigProvider $configProvider,
        LayoutInterface $layout
    ) {
        $this->configProvider = $configProvider;
        $this->layout = $layout;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        if ($this->configProvider->isAddLinkToToolbar()) {
            $parent = null;

            if ($this->layout->hasElement(self::HEADER_LINKS)) {
                $parent = self::HEADER_LINKS;
            } elseif ($this->layout->hasElement(self::TOP_LINKS)) {
                $parent = self::TOP_LINKS; // Compatibility with Magento_Blank theme
            }

            if ($parent) {
                $this->layout->addBlock(
                    \Magelearn\Story\Block\Link::class,
                    'mlstory_top_link',
                    $parent
                );
            }
        }
    }
}
