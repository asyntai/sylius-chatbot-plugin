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

namespace Asyntai\SyliusChatbotPlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $menu
            ->addChild('asyntai', [
                'route' => 'asyntai_admin_settings',
                'extras' => ['routes' => [['route' => 'asyntai_admin_settings']]],
            ])
            ->setLabel('Asyntai Chatbot')
            ->setLabelAttribute('icon', 'tabler:message-chatbot');

        // Reorder to place after 'configuration'
        $this->reorderMenuItems($menu);
    }

    private function reorderMenuItems(\Knp\Menu\ItemInterface $menu): void
    {
        $children = $menu->getChildren();
        if (!isset($children['asyntai']) || !isset($children['configuration'])) {
            return;
        }

        $newOrder = [];
        foreach ($children as $key => $child) {
            if ($key === 'asyntai') {
                continue; // Skip, we'll add it after configuration
            }
            $newOrder[$key] = $child;
            if ($key === 'configuration') {
                $newOrder['asyntai'] = $children['asyntai'];
            }
        }

        $menu->setChildren($newOrder);
    }
}
