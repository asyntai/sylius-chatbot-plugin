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

namespace Asyntai\SyliusChatbotPlugin\Controller\Admin;

use Asyntai\SyliusChatbotPlugin\Service\ConfigService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/asyntai')]
class SettingsController extends AbstractController
{
    public function __construct(
        private readonly ConfigService $configService
    ) {
    }

    #[Route('/settings', name: 'asyntai_admin_settings', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('@AsyntaiSyliusChatbotPlugin/Admin/settings.html.twig', [
            'siteId' => $this->configService->getSiteId(),
            'accountEmail' => $this->configService->getAccountEmail(),
            'scriptUrl' => $this->configService->getScriptUrl(),
            'isConnected' => $this->configService->isConnected(),
        ]);
    }

    #[Route('/api/save', name: 'asyntai_admin_api_save', methods: ['POST'])]
    public function save(Request $request): JsonResponse
    {
        try {
            $content = $request->getContent();
            $data = json_decode($content, true);

            if (!is_array($data)) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Invalid JSON data',
                ], Response::HTTP_BAD_REQUEST);
            }

            $siteId = $data['site_id'] ?? null;

            if (empty($siteId)) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'site_id is required',
                ], Response::HTTP_BAD_REQUEST);
            }

            $scriptUrl = $data['script_url'] ?? null;
            $accountEmail = $data['account_email'] ?? null;

            $this->configService->saveSettings($siteId, $scriptUrl, $accountEmail);

            return new JsonResponse([
                'success' => true,
                'site_id' => $siteId,
                'script_url' => $this->configService->getScriptUrl(),
                'account_email' => $accountEmail,
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/reset', name: 'asyntai_admin_api_reset', methods: ['POST'])]
    public function reset(): JsonResponse
    {
        try {
            $this->configService->resetSettings();

            return new JsonResponse([
                'success' => true,
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
