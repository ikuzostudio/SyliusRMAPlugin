<?php

declare(strict_types=1);

namespace Ikuzo\SyliusRMAPlugin\Model;

use Doctrine\ORM\Mapping as ORM;

trait RMAChannelTrait {

    /**
     * @ORM\Column(name="rma_enabled", type="boolean")
     **/
    protected bool $rmaEnabled = false;

    /**
     * @ORM\Column(name="rma_email", type="string", length=255, nullable=true)
     **/
    protected string $rmaEmail;

    public function isRMAEnabled(): bool 
    {
        return $this->rmaEnabled;
    }

    public function setRMAEnabled(bool $input): void 
    {
        $this->rmaEnabled = $input;
    }

    public function getRMAEmail(): ?string 
    {
        return $this->rmaEmail;
    }
    
    public function setRMAEmail(string $input): void 
    {
        $this->rmaEmail = $input;
    }
}