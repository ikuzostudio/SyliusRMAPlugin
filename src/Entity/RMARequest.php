<?php

namespace Ikuzo\SyliusRMAPlugin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;

class RMARequest implements RMARequestInterface
{
    use TimestampableTrait;

    public function __construct()
    {
        $this->variants = new ArrayCollection();
    }

    protected $id;

    protected $channel;

    protected $order;

    protected $variants;

    protected $reason;

    protected $comment;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChannel(): ?ChannelInterface
    {
        return $this->channel;
    }
    
    public function setChannel(?ChannelInterface $channel): void
    {
        $this->channel = $channel;
    }
    
    public function getOrder(): ?OrderInterface
    {
        return $this->order;
    }

    public function setOrder(?OrderInterface $order): void
    {
        $this->order = $order;
    }

    public function getVariants(): ?Collection
    {
        return $this->variants;
    }

    public function hasVariant(ProductVariantInterface $variant): bool
    {
        return $this->variants->contains($variant);
    }

    public function addVariant(ProductVariantInterface $variant = null): void
    {
        if (!$this->hasVariant($variant)) {
            $this->variants->add($variant);
        }
    }

    public function removeVariant(ProductVariantInterface $variant = null): void
    {
        if ($this->hasVariant($variant)) {
            $this->variants->removeElement($variant);
        }
    }

    public function getReason(): string
    {
        return $this->reason;
    }
    public function setReason(string $reason): void
    {
        $this->reason = $reason;
    }

    public function getComment(): string
    {
        return $this->comment;
    }
    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

}