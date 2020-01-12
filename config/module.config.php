<?php

return [
    'message_handlers' => [
        'factories' => [
            \ConferenceTools\Messaging\Handler\SendEmail::class => \ConferenceTools\Messaging\Handler\SendEmailFactory::class,
        ]
    ],
    'message_subscriptions' => [
        \ConferenceTools\Messaging\Domain\Email\Command\SendEmail::class => [
            \ConferenceTools\Messaging\Handler\SendEmail::class,
        ]
    ],
    'message_subscription_providers' => [
        \ConferenceTools\Messaging\Domain\Email\MessageSubscriptions::class,
    ],
];