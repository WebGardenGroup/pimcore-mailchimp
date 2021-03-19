<?php

namespace Wg\MailchimpBundle\Controller;

use Pimcore\Bundle\AdminBundle\Controller\AdminController as BaseAdminController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Wg\MailchimpBundle\MailchimpConfiguration;

use function array_filter;
use function is_array;
use function json_decode;


class AdminController extends BaseAdminController
{
    private MailchimpConfiguration $cookieConfiguration;

    public function __construct(MailchimpConfiguration $cookieConfiguration)
    {
        $this->cookieConfiguration = $cookieConfiguration;
    }

    /**
     * @Route("/get-settings", options={ "expose": true })
     */
    public function getSettingsAction(): JsonResponse
    {
        $this->checkPermission('mailchimp.permission');

        return $this->adminJson($this->cookieConfiguration->readConfig());
    }

    /**
     * @Route("/settings/save", options={ "expose": true })
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
            $version++;
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
}
