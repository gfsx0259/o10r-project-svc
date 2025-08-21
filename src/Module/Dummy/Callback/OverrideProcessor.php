<?php

namespace App\Module\Dummy\Callback;

use App\Module\Dummy\Collection\ArrayCollection;
use App\Module\Dummy\State;
use App\Module\Dummy\StateManager;
use HttpSoft\Message\Uri;
use Yiisoft\Router\UrlGeneratorInterface;

/**
 * Replace callback placeholders {{PLACEHOLDER_NAME}} with values from state init request
 */
final readonly class OverrideProcessor
{
    private const array SCHEMA = [
        'APS_URL' => 'APS_URL',
        'ACS_URL' => 'ACS_URL',
        'TERM_URL' => 'TERM_URL',
        'MD' => 'MD',
        'PAYMENT_ID' => 'PAYMENT_ID',
        'PROJECT_ID' => 'PROJECT_ID',
        'METHOD_CODE' => 'METHOD_CODE',
    ];

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private StateManager $stateManager,
        private string $host,
    ) {}

    public function process(ArrayCollection $callback, State $state): ArrayCollection
    {
        $source = $state->getInitialRequest();

        $source->set('PAYMENT_ID', $state->getPaymentId());
        $source->set('PROJECT_ID', $state->getInitialRequest()->get('general.project_id'));
        $source->set('METHOD_CODE', $state->getInitialRequest()->get('payment.method'));

        $source->set('APS_URL', $this->generateUrl(
            $this->host,
            'action/dummy',
            'aps',
            [
                'unique_key' => $this->stateManager->generateAccessKey($state),
                'termination_url' => $this->urlGenerator->generateAbsolute('redirectComplete', scheme: 'https', host: $_ENV['DUMMY_SELF_HOST']),
            ]
        ));

        $source->set('ACS_URL', $this->generateUrl('https://' . $_ENV['DUMMY_SELF_HOST'], 'proxy/dummy', 'acs'));
        $source->set('TERM_URL', $state->getInitialRequest()->get('return_url.default'));
        $source->set('MD', $this->stateManager->generateAccessKey($state));

        foreach (self::SCHEMA as $placeholder => $sourcePath) {
            if ($value = $source->get($sourcePath)) {
                $callback->replace('{{' . $placeholder . '}}', $value);
            }
        }

        return $callback;
    }

    private function generateUrl(string $host, string $routeName, string $page, array $queryParams = []): string
    {
        return new Uri(
            $host . $this->urlGenerator->generate($routeName, ['page' => $page], $queryParams),
        );
    }
}
