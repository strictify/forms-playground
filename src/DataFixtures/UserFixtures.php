<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Generator;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const USER_1 = 'user_1';
    
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getFixtures() as [$firstName, $lastName, $reference]) {
            $user = new User($firstName, $lastName);
            $manager->persist($user);
            $this->addReference($reference, $user);
        }
        $manager->flush();
    }

    /**
     * @return Generator<array{0: string, 1: string, 2: string}>
     */
    private function getFixtures(): Generator
    {
        yield ['John', 'Smith', self::USER_1];
    }
}
