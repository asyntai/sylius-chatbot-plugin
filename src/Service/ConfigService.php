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

namespace Asyntai\SyliusChatbotPlugin\Service;

use Asyntai\SyliusChatbotPlugin\Repository\AsyntaiConfigRepository;

class ConfigService
{
    private const KEY_SITE_ID = 'site_id';
    private const KEY_SCRIPT_URL = 'script_url';
    private const KEY_ACCOUNT_EMAIL = 'account_email';
    private const DEFAULT_SCRIPT_URL = 'https://asyntai.com/static/js/chat-widget.js';

    public function __construct(
        private readonly AsyntaiConfigRepository $configRepository
    ) {
    }

    public function getSiteId(): ?string
    {
        return $this->configRepository->getValue(self::KEY_SITE_ID);
    }

    public function getScriptUrl(): string
    {
        return $this->configRepository->getValue(self::KEY_SCRIPT_URL) ?? self::DEFAULT_SCRIPT_URL;
    }

    public function getAccountEmail(): ?string
    {
        return $this->configRepository->getValue(self::KEY_ACCOUNT_EMAIL);
    }

    public function isConnected(): bool
    {
        $siteId = $this->getSiteId();
        return $siteId !== null && $siteId !== '';
    }

    public function saveSettings(string $siteId, ?string $scriptUrl = null, ?string $accountEmail = null): void
    {
        $this->configRepository->setValue(self::KEY_SITE_ID, $siteId);

        if ($scriptUrl !== null && $scriptUrl !== '') {
            $this->configRepository->setValue(self::KEY_SCRIPT_URL, $scriptUrl);
        }

        if ($accountEmail !== null) {
            $this->configRepository->setValue(self::KEY_ACCOUNT_EMAIL, $accountEmail);
        }
    }

    public function resetSettings(): void
    {
        $this->configRepository->deleteByKey(self::KEY_SITE_ID);
        $this->configRepository->deleteByKey(self::KEY_SCRIPT_URL);
        $this->configRepository->deleteByKey(self::KEY_ACCOUNT_EMAIL);
    }
}
