<?php

declare(strict_types=1);

namespace Ikuzo\SyliusRMAPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class IkuzoSyliusRMAPlugin extends Bundle
{
    use SyliusPluginTrait;
}
