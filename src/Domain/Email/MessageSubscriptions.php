<?php

namespace ConferenceTools\Messaging\Domain\Email;

use Phactor\Message\MessageSubscriptionProvider;

/** @codeCoverageIgnore  */
class MessageSubscriptions implements MessageSubscriptionProvider
{
    public function getSubscriptions(): array
    {
        return [
            Command\SendEmailInBackground::class => [
                Email::class,
            ],
        ];
    }
}
