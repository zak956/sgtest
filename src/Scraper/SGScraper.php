<?php

declare(strict_types=1);

namespace App\Scraper;

use App\Counter\Contract\CounterInterface;
use App\Decorator\JsonArrayItemDecorator;
use App\Iterator\ExpectingIterator;
use App\Iterator\MapIterator;
use App\Model\Contract\RequestModelInterface;
use App\Model\Factory\ModelFactory;
use App\Model\PageRequestModel;
use ArrayIterator;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\SerializerInterface;
use voku\helper\HtmlDomParser;

class SGScraper
{
    const MODE_PAGES = 'pages';
    const MODE_PRODUCTS = 'products';

    private $url;
    /** @var Client */
    private $client;
    private $maxRequests;
    /** @var SerializerInterface */
    private $serializer;
    /** @var CounterInterface */
    private $counter;

    /**
     * SGScraper constructor.
     *
     * @param Client              $client
     * @param SerializerInterface $serializer
     * @param string              $url
     * @param int                 $maxRequests
     * @param CounterInterface    $counter
     */
    public function __construct(Client $client, SerializerInterface $serializer, string $url, int $maxRequests, CounterInterface $counter)
    {
        $this->client = $client;
        $this->serializer = $serializer;
        $this->url = $url;
        $this->maxRequests = $maxRequests;
        $this->counter = $counter;
    }

    /**
     * @param OutputInterface $output
     *
     * @return true
     */
    public function run(OutputInterface &$output): bool
    {
        $output->write('[');
        $httpClient = $this->client;
        $initialRequest = new PageRequestModel($this->url);
        $this->counter->addItem($initialRequest);
        $generator = new MapIterator(
            new ArrayIterator([$initialRequest]),
            function (RequestModelInterface $request, $array) use (&$httpClient, &$output) {
                return $httpClient->requestAsync('GET', $request->getUrl())
                    ->then(function (Response $response) use ($request, $array, &$output): void {
                        $body = $response->getBody();
                        $html = (string) $body;

                        $factory = new ModelFactory(new HtmlDomParser());
                        $model = $factory->factory($request, $html);
                        $decorator = new JsonArrayItemDecorator($model, $this->serializer, $this->counter);
                        $output->write($decorator->getData());

                        foreach ($model->getNewLinks() as $link) {
                            if (!$this->counter->isLinkEnough($link)) {
                                $array[] = $link;
                                $this->counter->addItem($link);
                            }
                        }
                    });
            }
        );

        $generator = new ExpectingIterator($generator);

        $promise = Promise\each_limit($generator, $this->maxRequests);

        $promise->wait();

        $output->writeln(']');

        return true;
    }
}
