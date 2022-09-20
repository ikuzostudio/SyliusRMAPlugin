<?php

declare(strict_types=1);

namespace Ikuzo\SyliusRMAPlugin\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuBuilder
{
    public function addItems(MenuBuilderEvent $event): void
    {
        /** @var ItemInterface $menu */
        $menu = $event->getMenu()->getChild('catalog');

        $menu
            ->addChild('ikuzo_rma', ['route' => 'ikuzo_rma_admin_rma_request_index'])
            ->setLabel('ikuzo_rma.ui.rma_requests')
            ->setLabelAttribute('icon', 'undo')
        ;
    }
}
