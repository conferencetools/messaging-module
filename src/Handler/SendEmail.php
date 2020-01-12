<?php

namespace ConferenceTools\Messaging\Handler;

use ConferenceTools\Messaging\Domain\Email\Command\SendEmail as SendEmailCommand;
use Phactor\Message\DomainMessage;
use Phactor\Message\Handler;
use Psr\Container\ContainerInterface;
use Zend\Http\Response;
use Zend\Mail\Message;
use Zend\Mail\Transport\TransportInterface;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\View\Model\ViewModel;
use Zend\View\View;

class SendEmail implements Handler
{
    private $repositoryContainer;
    private $view;
    private $mail;
    private $config;

    public function __construct(
        ContainerInterface $repositoryContainer,
        View $view,
        TransportInterface $mail,
        array $config = []
    ) {
        $this->repositoryContainer = $repositoryContainer;
        $this->view = $view;
        $this->mail = $mail;
        $this->config = $config;
    }

    public function handle(DomainMessage $message)
    {
        $innerMessage = $message->getMessage();
        if (!($innerMessage instanceof SendEmailCommand)) {
            return;
        }

        list($template, $subject, $from, $viewVarsFromConfig) = $this->prepareConfig($innerMessage);
        $viewModel = $this->prepareViewModel($innerMessage, $template, $viewVarsFromConfig);

        $response = new Response();
        $this->view->setResponse($response);
        $this->view->render($viewModel);
        $html = $response->getContent();

        $email = $this->buildMessage($html, $subject, $innerMessage->getEmailAddress(), $from);
        $this->mail->send($email);
    }

    private function prepareViewModel(SendEmailCommand $command, string $template, array $vars)
    {
        $readModels = [];

        foreach ($command->getReadModels() as $readModel => $id) {
            $readModels[$readModel] = $this->repositoryContainer->get($readModel)->get($id);
        }

        if (!empty($readModels)) {
            $vars['readModels'] = $readModels;
        }

        $viewModel = new ViewModel($vars);
        $viewModel->setTemplate($template);
        return $viewModel;
    }

    private function prepareConfig(SendEmailCommand $command): array
    {
        $config = array_merge($this->config['_defaults'], array_filter($this->config[$command->getEmailHandle()]));
        $template = $config['template'];
        $subject = $config['subject'];
        $from = $config['from'];

        unset($config['subject'], $config['from'], $config['template']);

        return [$template, $subject, $from, $config];
    }

    private function buildMessage(string $htmlMarkup, string $subject, string $to, string $from): Message
    {
        $html = new MimePart($htmlMarkup);
        $html->setCharset('UTF-8');
        $html->type = "text/html";

        $body = new MimeMessage();
        $body->setParts(array($html));

        $message = new Message();
        $message->setBody($body);
        $message->setSubject($subject);
        $message->setTo($to);
        if (!empty($from)) {
            $message->setFrom($from);
        }
        $message->setEncoding('UTF-8');

        return $message;
    }
}
