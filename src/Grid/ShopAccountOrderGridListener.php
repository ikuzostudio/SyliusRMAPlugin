<?php

namespace Ikuzo\SyliusRMAPlugin\Grid;

use Sylius\Component\Grid\Event\GridDefinitionConverterEvent;

final class ShopAccountOrderGridListener
{
    public function editFields(GridDefinitionConverterEvent $event): void
    {
        $grid = $event->getGrid();
        // TODO : Remove action send_rma if order is not STATUS_FULFILLED yet
        // $grid->removeActionGroup('send_rma');
    }
}