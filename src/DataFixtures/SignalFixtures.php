<?php

namespace App\DataFixtures;

use App\Entity\Signal;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class SignalFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $types = ['BUY', 'SELL'];
        $results = ['PROFIT', 'LOSS', 'PENDING'];

        for ($i = 0; $i < 50; $i++) {

            $signal = new Signal();

            // Type random
            $type = $types[array_rand($types)];

            // Entry random (ex: prix crypto / forex large range)
            $entry = random_int(20000, 40000);

            // Calcul SL / TP cohérent avec type
            if ($type === 'BUY') {
                $stopLoss = $entry - random_int(50, 300);
                $takeProfit = $entry + random_int(100, 600);
            } else { // SELL
                $stopLoss = $entry + random_int(50, 300);
                $takeProfit = $entry - random_int(100, 600);
            }

            // Date random dans les 30 derniers jours
            $timestamp = time() - random_int(0, 60 * 60 * 24 * 30);
            $date = (new \DateTimeImmutable())->setTimestamp($timestamp);

            // Name basé sur datetime
            $name = $date->format('YmdHi') . $i;

            $signal->setName($name);
            $signal->setType($type);
            $signal->setEntry($entry);
            $signal->setStopLoss($stopLoss);
            $signal->setTakeProfit($takeProfit);
            $signal->setResult($results[array_rand($results)]);
            $signal->setPublishedAt($date);

            $manager->persist($signal);
        }

        $manager->flush();
    }
}
