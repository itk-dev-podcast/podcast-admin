<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Tests\Fixtures\ContainerAwareFixture;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

abstract class LoadData extends ContainerAwareFixture implements OrderedFixtureInterface
{
    /** @var ObjectManager */
    protected $manager;

    /** @var \Symfony\Component\PropertyAccess\PropertyAccessorInterface */
    protected $accessor;

    public function load(ObjectManager $manager)
    {
        $this->accessor = $this->container->get('property_accessor');
        $this->manager = $manager;

        $finder = (new Finder())->files()->name('*.yml')->in(__DIR__.'/../Data/'.$this->getName());
        foreach ($finder as $file) {
            echo $file->getFilename(), PHP_EOL;

            $yaml = file_get_contents($file->getRealPath());
            $data = Yaml::parse($yaml);
            $items = $this->isAssoc($data) ? [$data] : $data;
            foreach ($items as $item) {
                $this->loadItem($item);
            }
        }
        $manager->flush();
    }

    protected function setValues($object, $values)
    {
        if (isset($values['id'])) {
            $id = $values['id'];
            unset($values['id']);

            try {
                $property = new \ReflectionProperty(get_class($object), 'id');
                $property->setAccessible(true);
                $property->setValue($object, $id);
            } catch (\ReflectionException $exception) {
            }
        }

        foreach ($values as $key => $value) {
            $this->setValue($object, $key, $value);
        }

        return $object;
    }

    protected function setValue($object, $path, $value)
    {
        $this->accessor->setValue($object, $path, $value);

        return $object;
    }

    protected function persist($entity)
    {
        $metadata = null;
        $idGenerator = null;
        $idGeneratorType = null;
        if ($entity->getId() !== null) {
            // Remove id generator and set id manually.
            $metadata = $this->manager->getClassMetadata(get_class($entity));
            $idGenerator = $metadata->idGenerator;
            $idGeneratorType = $metadata->generatorType;
            $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
        }

        $this->manager->persist($entity);

        // Restore id generator.
        if ($metadata !== null) {
            $metadata->setIdGenerator($idGenerator);
            $metadata->setIdGeneratorType($idGeneratorType);
        }
    }

    protected function getName()
    {
        if (preg_match('@\\\\Load(?<name>[A-Z][a-z0-9]*)$@', get_class($this), $matches)) {
            return $matches['name'];
        }

        throw new \RuntimeException('Cannot get name');
    }

    abstract protected function loadItem($data);

    // @see https://stackoverflow.com/a/173479
    private function isAssoc(array $arr)
    {
        if ([] === $arr) {
            return false;
        }

        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
