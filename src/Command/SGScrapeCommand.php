<?php

declare(strict_types=1);

namespace App\Command;

use App\Counter\Factory\CounterFactory;
use App\Model\AbstractModel;
use App\Scraper\SGScraper;
use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SGScrapeCommand extends Command
{
    const ARG_URL = 'url';
    const ARG_NUM = 'number';
    const ARG_MAX = 'max_async_requests';
    const OPT_PRODUCTS = 'products';
    const DEFAULT_MAX = 5;
    protected static $defaultName = 'scrape';

    protected function configure(): void
    {
        $this
            ->setName('scrape')
            ->setDescription('Scrapes a product list page from SG.com site.')
            ->setHelp('This command allows you to scrape a product list page from SG.com site...')
            ->addArgument(self::ARG_URL, InputArgument::REQUIRED, 'URL of product list page.')
            ->addArgument(self::ARG_NUM, InputArgument::REQUIRED, 'Number of pages(default) or products to be processed.')
            ->addArgument(self::ARG_MAX, InputArgument::OPTIONAL, 'Max number of async requests', self::DEFAULT_MAX)
            ->addOption(self::OPT_PRODUCTS, null, InputOption::VALUE_NONE, 'Use products number instead of pages number.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $client = new Client();

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $counter = CounterFactory::getCounter($input->getOption(self::OPT_PRODUCTS) ? AbstractModel::TYPE_PRODUCT : AbstractModel::TYPE_PAGE, (int) $input->getArgument(self::ARG_NUM));

        $scraper = new SGScraper(
            $client,
            $serializer,
            $input->getArgument(self::ARG_URL),
            (int) $input->getArgument(self::ARG_MAX),
            $counter
        );

        $scraper->run($output);
    }
}
