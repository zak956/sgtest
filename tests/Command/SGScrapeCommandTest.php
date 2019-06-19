<?php

declare(strict_types=1);

namespace App\Tests\Command;

use App\Command\SGScrapeCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

// @coversNothing
//class SGScrapeCommandTest extends TestCase
//{
//    public function testExecute(): void
//    {
//        $application = new Application();
//        $application->add(new SGScrapeCommand());
//
//        $command = $application->find('scrape');
//
//        $commandTester = new CommandTester($command);
////        $this->assertTrue(true);
////        $commandTester->execute([
////            'command' => $command->getName(),
////            'url' => 'https://www.stadiumgoods.com/adidas',
////            'number' => 1
////
////            // pass arguments to the helper
//////            'username' => 'Wouter',
////
////            // prefix the key with two dashes when passing options,
////            // e.g: '--some-option' => 'option_value',
////        ]);
////
////        // the output of the command in the console
////        $output = $commandTester->getOutput();
////        $this->assertContains('Username: Wouter', $output);
//
//        // ...
//    }
//}
