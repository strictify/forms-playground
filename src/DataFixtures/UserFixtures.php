<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Generator;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const USER_1 = 'user_1';
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->getFixtures() as [$firstName, $lastName, $reference]) {
            $user = $this->userRepository->create($firstName, $lastName);
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
