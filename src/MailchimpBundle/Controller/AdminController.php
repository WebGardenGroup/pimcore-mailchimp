<?php

namespace Wgg\MailchimpBundle\Controller;

use function array_filter;
use Exception;
use function is_array;
use function json_decode;
use Pimcore\Bundle\AdminBundle\Controller\AdminController as BaseAdminController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wgg\MailchimpBundle\ApiClient;
use Wgg\MailchimpBundle\MailchimpConfiguration;

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

        $apiKey = $request->request->get('api_key');
        $serverPrefix = $request->request->get('server_prefix');
        $listIdsRaw = $request->request->get('list_id');
        $listIds = array_filter(is_array($listIdsRaw) ? $listIdsRaw : [$listIdsRaw]);

        $this->cookieConfiguration->writeConfig($apiKey, $serverPrefix, $listIds);

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
