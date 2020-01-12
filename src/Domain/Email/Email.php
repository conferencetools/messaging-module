<?php

namespace ConferenceTools\Messaging\Domain\Email;

use Phactor\Actor\AbstractActor;

class Email extends AbstractActor
{
    public function handleSendEmailInBackground(Command\SendEmailInBackground $command)
    {
        $this->schedule(
            new Command\SendEmail($command->getEmailAddress(), $command->getEmailHandle(), $command->getVars(), $command->getReadModels()),
            (new \DateTime())->add(new \DateInterval('PT1M'))
        );
    }
}
