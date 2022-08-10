<?php

declare(strict_types=1);

namespace Ikuzo\SyliusRMAPlugin\Model;

interface RMAChannelInterface {
    public function isRMAEnabled(): bool;
    public function setRMAEnabled(bool $input): void;
    public function getRMAEmail(): ?string;
    public function setRMAEmail(string $input): void;
}