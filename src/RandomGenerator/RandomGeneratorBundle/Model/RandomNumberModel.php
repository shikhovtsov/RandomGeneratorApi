<?php

namespace RandomGenerator\RandomGeneratorBundle\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use RandomGenerator\RandomGeneratorBundle\Entity\RandomNumber;

class RandomNumberModel
{
    const DEFAULT_MIN_VALUE = 0;
    const DEFAULT_MAX_VALUE = 100000;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * RandomNumberModel constructor.
     * @param $em
     */
    public function __construct($em)
    {
        $this->em = $em;
    }

    /**
     * Generate random number in [$min, $max] range,
     * save it to DB and return record ID
     *
     * @param integer $min
     * @param integer $max
     * @return int
     */
    public function generateRandomNumber($min, $max)
    {
        if ($min > $max) {
            return false;
        }

        $randomNumber = new RandomNumber();

        $randomNumber->setValue(rand($min, $max));

        $this->em->persist($randomNumber);
        $this->em->flush($randomNumber);

        return $randomNumber->getId();
    }

    /**
     * Get random number by $id
     *
     * @param integer $id
     * @return null|RandomNumber
     */
    public function getRandomNumber($id)
    {
        /** @var EntityRepository $repo */
        $repo = $this->em->getRepository('RandomGeneratorBundle:RandomNumber');

        $randomNumber = $repo->find($id);

        return $randomNumber;
    }

}