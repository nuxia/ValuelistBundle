<?php

namespace Nuxia\ValuelistBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nuxia\ValuelistBundle\Entity\Valuelist;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Parser;

class LoadValuelistData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        if (count(glob($this->getValuelistPatternPath())) > 0) {
            $parser = new Parser();
            $finder = Finder::create()->in($this->getValuelistPatternPath())->name('*.yml')->sortByName();
            foreach ($finder->files() as $file) {
                foreach ($parser->parse(file_get_contents($file->getRealpath())) as $code => $data) {
                    $values = array_intersect_key(
                        $data,
                        array_fill_keys(array('code', 'label', 'language', 'category', 'value'), 'buffer')
                    );
                    if (!isset($data['code'])) {
                        $values['code'] = $code;
                    }
                    if (!isset($data['category'])) {
                        $values['category'] = $this->convertFilenameToCategory($file->getRelativePathname());
                    }
                    if (isset($data['labels'])) {
                        foreach ($data['labels'] as $language => $label) {
                            $values = array_merge($values, array('label' => $label, 'language' => $language));
                            $this->saveValuelist($manager, $values, $data);
                        }
                    } else {
                        $this->saveValuelist($manager, $values, $data);
                    }
                }
            }
            $manager->flush();
        }
    }

    /**
     * @param $name
     * @return string
     */
    private function convertFilenameToCategory($name)
    {
        return substr($name, strpos($name, '_') + 1, -4);
    }

    /**
     * @param $manager
     * @param array $values
     * @param array $ymlData
     */
    private function saveValuelist($manager, array $values, array $ymlData)
    {
        $valuelist = $this->container->get('nuxia_valuelist.manager')->create($values);
        if (isset($ymlData['parent'])) {
            $valuelist->setParent($this->getReference('valuelist-' . $ymlData['parent']));
        }
        $this->addValuelistReference($valuelist, $ymlData);
        $manager->persist($valuelist);
    }

    /**
     * @return string
     */
    private function getValuelistPatternPath()
    {
        return $this->container->getParameter('kernel.root_dir') . '/../src/*/*/DataFixtures/Valuelist';
    }

    /**
     * @param Valuelist $valuelist
     * @param array     $ymlData
     */
    private function addValuelistReference(Valuelist $valuelist, array $ymlData)
    {
        $reference = array_key_exists('_reference', $ymlData) ? $ymlData['_reference'] : 'auto';
        if ($reference !== null) {
            if ($reference === 'auto') {
                $prefix = 'valuelist-' . $valuelist->getLanguage() . '-' . $valuelist->getCategory() . '-';
                if ($valuelist->getParent() !== null) {
                    $prefix .= $valuelist->getParent()->getCode() . '-';
                }
                $reference = $prefix . $valuelist->getCode();
            }
            $this->addReference($reference, $valuelist);
        }
    }

    /**
     * {@inheritdoc }
     */
    public function getOrder()
    {
        return 0;
    }
}
