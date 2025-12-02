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

namespace Asyntai\SyliusChatbotPlugin\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class AsyntaiSyliusChatbotExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );

        $loader->load('services.yaml');
    }

    public function prepend(ContainerBuilder $container): void
    {
        // Configure Doctrine to map our entity using attributes
        $container->prependExtensionConfig('doctrine', [
            'orm' => [
                'mappings' => [
                    'AsyntaiSyliusChatbotPlugin' => [
                        'type' => 'attribute',
                        'dir' => dirname(__DIR__) . '/Entity',
                        'prefix' => 'Asyntai\SyliusChatbotPlugin\Entity',
                        'is_bundle' => false,
                    ],
                ],
            ],
        ]);

        // For Sylius 2.x - use Twig Hooks to inject widget in shop
        if ($container->hasExtension('sylius_twig_hooks')) {
            $container->prependExtensionConfig('sylius_twig_hooks', [
                'hooks' => [
                    // Inject chatbot widget in shop frontend
                    'sylius_shop.base#javascripts' => [
                        'asyntai_chatbot_widget' => [
                            'template' => '@AsyntaiSyliusChatbotPlugin/Shop/widget_ui.html.twig',
                            'priority' => -100,
                        ],
                    ],
                ],
            ]);
        }
    }
}
