<?php

namespace ConferenceTools\MessagingTest\Domain\Email;

use ConferenceTools\Messaging\Domain\Email\Command\SendEmail;
use ConferenceTools\Messaging\Domain\Email\Command\SendEmailInBackground;
use ConferenceTools\Messaging\Domain\Email\Email;
use ConferenceTools\Messaging\Domain\Email\MessageSubscriptions;
use Phactor\Test\ActorTester;
use Phactor\Test\TesterFactory;

/**
 * @covers \ConferenceTools\Messaging\Domain\Email\Email
 */
class EmailTest extends \Codeception\Test\Unit
{
    /** @var ActorTester */
    private $helper;
    private $actorId;

    public function _before()
    {
        $this->helper = (new TesterFactory())->actor(Email::class, new MessageSubscriptions());
        $this->actorId = $this->helper->getActorIdentity()->getId();
    }

    public function testCreateList()
    {
        $this->helper->when(new SendEmailInBackground('test@test.com', 'email', [], ['delegateId' => 'qwerty']));
        $this->helper->expect(new SendEmail('test@test.com', 'email', [], ['delegateId' => 'qwerty']));
    }
}