<?php

declare(strict_types=1);

/**
 * Asyntai - AI Chatbot for Sylius
 *
 * @category  Asyntai
 * @package   AsyntaiSyliusChatbotPlugin
 * @author    Asyntai <hello@asyntai.com>
 * @copyright Copyright (c) Asyntai
 * @license   MIT License
 */

namespace Asyntai\SyliusChatbotPlugin\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \Asyntai\SyliusChatbotPlugin\Repository\AsyntaiConfigRepository::class)]
#[ORM\Table(name: 'asyntai_config')]
class AsyntaiConfig
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'config_key', type: 'string', length: 255, unique: true)]
    private string $configKey;

    #[ORM\Column(name: 'config_value', type: 'text', nullable: true)]
    private ?string $configValue = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConfigKey(): string
    {
        return $this->configKey;
    }

    public function setConfigKey(string $configKey): self
    {
        $this->configKey = $configKey;
        return $this;
    }

    public function getConfigValue(): ?string
    {
        return $this->configValue;
    }

    public function setConfigValue(?string $configValue): self
    {
        $this->configValue = $configValue;
        return $this;
    }
}
