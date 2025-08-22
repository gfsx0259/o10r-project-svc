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
        private string $uiHost,
        private string $selfHost,
    ) {}

    public function process(ArrayCollection $callback, State $state): ArrayCollection
    {
        $source = $state->getInitialRequest();

        $source->set('PAYMENT_ID', $state->getPaymentId());
        $source->set('PROJECT_ID', $state->getInitialRequest()->get('general.project_id'));
        $source->set('METHOD_CODE', $state->getInitialRequest()->get('payment.method_code'));

        $acsTermUrl = $state->getInitialRequest()->get('return_url.default');
        $apsTermUrl = $this->generateUrl($this->uiHost, 'action/dummy', 'aps', [
            'unique_key' => $this->stateManager->generateAccessKey($state),
            'termination_url' => $this->urlGenerator->generateAbsolute('redirectComplete', host: $this->selfHost),
        ]);

        $source->set('ACS_URL', $this->generateUrl($this->selfHost, 'proxy/dummy', 'acs'));
        $source->set('TERM_URL', $acsTermUrl);
        $source->set('MD', $this->stateManager->generateAccessKey($state));

        $source->set('APS_URL', $apsTermUrl);

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
