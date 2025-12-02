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

namespace Asyntai\SyliusChatbotPlugin\Twig;

use Asyntai\SyliusChatbotPlugin\Service\ConfigService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class AsyntaiExtension extends AbstractExtension
{
    public function __construct(
        private readonly ConfigService $configService
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('asyntai_chatbot_config', [$this, 'getChatbotConfig']),
        ];
    }

    /**
     * @return array{is_connected: bool, site_id: ?string, script_url: string}
     */
    public function getChatbotConfig(): array
    {
        return [
            'is_connected' => $this->configService->isConnected(),
            'site_id' => $this->configService->getSiteId(),
            'script_url' => $this->configService->getScriptUrl(),
        ];
    }
}
