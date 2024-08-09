<?php

return [
    'configs' => [
        [
            'name' => 'pix-webhook',
            'signing_secret' => env('WEBHOOK_SECRET'),
            'signature_header_name' => 'X-Webhook-Signature',
            'signature_validator' => \App\WebhookSignatureValidator::class,
            'webhook_profile' => \Spatie\WebhookClient\WebhookProfile\ProcessEverythingWebhookProfile::class,
            'webhook_response' => \Spatie\WebhookClient\WebhookResponse\RespondsToLaravelWebhooks::class,
            'webhook_model' => \Spatie\WebhookClient\Models\WebhookCall::class,
        ],
    ],
];
