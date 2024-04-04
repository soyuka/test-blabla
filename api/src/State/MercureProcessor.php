<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Message;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

/**
 * @implements ProcessorInterface<Message, Message>
 */
final class MercureProcessor implements ProcessorInterface
{
    /**
     * @param ProcessorInterface<Message, Message> $persistProcessor
     */
    public function __construct(
        private readonly HubInterface $hub,
        #[Autowire('@api_platform.doctrine.orm.state.persist_processor')] private readonly ProcessorInterface $persistProcessor
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Message
    {
        $a = 'https://localhost/messages/' . random_int(1, 10);
        $this->hub->publish(new Update($a, json_encode(['a' => 'b', '@id' => $a])));
        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
