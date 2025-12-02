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

namespace Asyntai\SyliusChatbotPlugin\EventListener;

use Asyntai\SyliusChatbotPlugin\Service\ConfigService;
use Sylius\Bundle\UiBundle\Block\BlockEventListener;
use Sonata\BlockBundle\Event\BlockEvent;
use Sonata\BlockBundle\Model\Block;

final class ChatWidgetListener
{
    public function __construct(
        private readonly ConfigService $configService
    ) {
    }

    public function onBlockEvent(BlockEvent $event): void
    {
        if (!$this->configService->isConnected()) {
            return;
        }

        $block = new Block();
        $block->setId(uniqid('asyntai_', true));
        $block->setSettings([
            'template' => '@AsyntaiSyliusChatbotPlugin/Shop/widget.html.twig',
            'site_id' => $this->configService->getSiteId(),
            'script_url' => $this->configService->getScriptUrl(),
        ]);
        $block->setType('sonata.block.service.template');

        $event->addBlock($block);
    }
}
