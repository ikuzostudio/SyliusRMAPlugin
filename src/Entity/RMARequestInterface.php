<?php

namespace Ikuzo\SyliusRMAPlugin\Entity;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

interface RMARequestInterface extends ResourceInterface, TimestampableInterface
{
    public function getId(): ?int;

    public function getChannel(): ?ChannelInterface;
    public function setChannel(?ChannelInterface $channel): void;
    
    public function getOrder(): ?OrderInterface;
    public function setOrder(?OrderInterface $order): void;

    public function getVariants(): ?Collection;
    public function hasVariant(ProductVariantInterface $variant): bool;
    public function addVariant(ProductVariantInterface $variant): void;
    public function removeVariant(ProductVariantInterface $variant): void;

    public function getReason(): string;
    public function setReason(string $reason): void;

    public function getComment(): string;
    public function setComment(string $comment): void;

    public function getState(): string;
    public function setState(string $state): void;
}