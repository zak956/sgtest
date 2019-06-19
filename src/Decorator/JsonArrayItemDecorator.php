<?php

declare(strict_types=1);

namespace App\Decorator;

use App\Counter\Contract\CounterInterface;
use App\Decorator\Contract\OutputInterface;
use Symfony\Component\Serializer\SerializerInterface;

class JsonArrayItemDecorator implements OutputInterface
{
    private $object;
    private $serializer;
    private $counter;

    public function __construct(OutputInterface $object, SerializerInterface $serializer, CounterInterface &$counter)
    {
        $this->object = $object;
        $this->serializer = $serializer;
        $this->counter = $counter;
    }

    public function getData()
    {
        if (null === $this->object->getData()) {
            return '';
        }

        $result = $this->counter->isFirstItem() ? '' : ",\n";
        $this->counter->setNotFirst();
        $result .= $this->serializer->serialize($this->object->getData(), 'json', ['json_encode_options' => JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES]);

        return $result;
    }
}
