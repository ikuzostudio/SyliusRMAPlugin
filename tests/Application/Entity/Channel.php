<?php

declare(strict_types=1);

namespace Tests\Ikuzo\SyliusRMAPlugin\Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ikuzo\SyliusRMAPlugin\Model\RMAChannelInterface;
use Ikuzo\SyliusRMAPlugin\Model\RMAChannelTrait;
use Sylius\Component\Core\Model\Channel as BaseChannel;

/**
 * @ORM\Table(name="sylius_channel")
 * @ORM\Entity()
 */
class Channel extends BaseChannel implements RMAChannelInterface
{
    use RMAChannelTrait;
}