<?php

namespace ConferenceTools\Messaging\Domain\Email\Command;

use JMS\Serializer\Annotation as Jms;

class SendEmailInBackground
{
    /** @Jms\Type("string") */
    private $emailAddress;
    /** @Jms\Type("string") */
    private $emailHandle;
    /** @Jms\Type("array<string, string>") */
    private $vars;
    /** @Jms\Type("array<string, string>") */
    private $readModels;

    public function __construct(string $emailAddress, string $emailHandle, array $vars, array $readModels)
    {
        $this->emailAddress = $emailAddress;
        $this->emailHandle = $emailHandle;
        $this->vars = $vars;
        $this->readModels = $readModels;
    }

    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    public function getEmailHandle(): string
    {
        return $this->emailHandle;
    }

    public function getVars(): array
    {
        return $this->vars;
    }

    public function getReadModels(): array
    {
        return $this->readModels;
    }
}
