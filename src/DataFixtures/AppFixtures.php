<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Nelmio\Alice\Loader\SimpleFilesLoader;
use Nelmio\Alice\ObjectSet;
use Nelmio\Alice\Throwable\LoadingThrowable;

class AppFixtures extends Fixture
{
    private const FIXTURE_FILES_ARRAY = [
        __DIR__.'/Fixture/task_fixture.yml',
    ];

    public function __construct(private readonly SimpleFilesLoader $loader)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $this->fixtureDataToDatabaseFromFixtureFiles($manager);
    }

    /**
     * @param array<string> $fixtureFilesPaths
     *
     * @throws LoadingThrowable
     */
    private function getObjectsSetByFixtureFiles(array $fixtureFilesPaths): ObjectSet
    {
        return $this->loader->loadFiles($fixtureFilesPaths);
    }

    private function persistObjectSetToManager(ObjectManager $manager, ObjectSet $objectSet): void
    {
        foreach ($objectSet->getObjects() as $object) {
            $manager->persist($object);
        }
    }

    private function fixtureDataToDatabaseFromFixtureFiles(ObjectManager $manager): void
    {
        $this->persistObjectSetToManager(
            $manager,
            $this->getObjectsSetByFixtureFiles(self::FIXTURE_FILES_ARRAY)
        );

        $manager->flush();
    }
}
