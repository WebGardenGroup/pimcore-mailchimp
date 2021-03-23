<?php

namespace Wg\MailchimpBundle\Controller;

use Exception;
use Pimcore\Bundle\AdminBundle\Controller\AdminController as BaseAdminController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wg\MailchimpBundle\ApiClient;
use Wg\MailchimpBundle\MailchimpConfiguration;

use function array_filter;
use function is_array;
use function json_decode;

class AdminController extends BaseAdminController
{
    private MailchimpConfiguration $cookieConfiguration;
    private ApiClient $apiClient;

    public function __construct(MailchimpConfiguration $cookieConfiguration, ApiClient $apiClient)
    {
        $this->cookieConfiguration = $cookieConfiguration;
        $this->apiClient = $apiClient;
    }

    /**
     * @Route("/get-settings", options={ "expose": true }, methods={"GET"})
     */
    public function getSettingsAction(): JsonResponse
    {
        $this->checkPermission('mailchimp.permission');

        return $this->adminJson($this->cookieConfiguration->readConfig());
    }

    /**
     * @Route("/settings/save", options={ "expose": true }, methods={"PUT"})
     */
    public function saveSettingsAction(Request $request): JsonResponse
    {
        $this->checkPermission('mailchimp.permission');
        $values = json_decode($request->get('data'), true);

        $config = $this->cookieConfiguration->readConfig();
        $version = $config['version'];
        if (!$version) {
            $version = 1;
        } else {
            ++$version;
        }

        // Google Analytics
        $settings['config']['api_key'] = $values['config.api_key'];
        $settings['config']['server_prefix'] = $values['config.server_prefix'];
        $settings['config']['list_id'] = is_array($values['config.list_id']) ? array_filter($values['config.list_id']) : [$values['config.list_id']];

        $settings['version'] = $version;

        $this->cookieConfiguration->writeConfig($settings);

        $output = [
            'success' => true,
        ];

        return $this->adminJson($output);
    }

    /**
     * @Route("/settings/validate", options={ "expose": true }, methods={"POST"})
     */
    public function validateSettingsAction(Request $request): JsonResponse
    {
        $this->checkPermission('mailchimp.permission');
        $values = json_decode($request->get('data'), true);

        $this->apiClient->setConfig([
            'apiKey' => $values['config.api_key'],
            'server' => $values['config.server_prefix'],
        ]);

        try {
            $this->apiClient->ping->get();
            $listIds = is_array($values['config.list_id']) ? array_filter($values['config.list_id']) : [$values['config.list_id']];
            foreach ($listIds as $listId) {
                $this->apiClient->lists->getList($listId, 'id');
            }
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(null, JsonResponse::HTTP_OK);
    }
}
