<?php

namespace ConferenceTools\MessagingTest\Domain\Email\Command;

use ConferenceTools\Messaging\Domain\Email\Command\SendEmailInBackground;
use Doctrine\Common\Annotations\AnnotationRegistry;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;

class SendEmailInBackgroundTest extends \Codeception\Test\Unit
{
    /** @var \UnitTester */
    protected $tester;
    private $serializer;

    protected function getSerializer(): Serializer
    {
        if ($this->serializer === null) {
            AnnotationRegistry::registerLoader('class_exists');
            $this->serializer = SerializerBuilder::create()
                ->setPropertyNamingStrategy(new IdenticalPropertyNamingStrategy())
                ->addDefaultHandlers()
                ->build();
        }

        return $this->serializer;
    }

    public function testSerialise()
    {
        $fixture = new SendEmailInBackground('test@email.com', 'emails/ticket-email', ['var1' => 'ticketid', 'var2' => 'John Smith'], ['Delegate' => 'delegateId']);
        $data = $this->getSerializer()->toArray($fixture);

        /** @var SendEmailInBackground $sut */
        $sut = $this->getSerializer()->fromArray($data, SendEmailInBackground::class);

        $this->tester->assertEquals('test@email.com', $sut->getEmailAddress());
        $this->tester->assertEquals('emails/ticket-email', $sut->getEmailHandle());
        $this->tester->assertEquals(['var1' => 'ticketid', 'var2' => 'John Smith'], $sut->getVars());
        $this->tester->assertEquals(['Delegate' => 'delegateId'], $sut->getReadModels());
    }
}
