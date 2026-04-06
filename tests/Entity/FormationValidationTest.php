<?php

namespace App\Tests\Entity;

use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FormationValidationTest extends KernelTestCase
{
    public function testDateFutureInvalid()
    {
        self::bootKernel();

        $formation = new Formation();

        $formation->setTitle("Test");
        $formation->setVideoId("123456");

        $futureDate = new \DateTime('+1 day');

        $formation->setPublishedAt($futureDate);

        $validator = self::getContainer()->get(ValidatorInterface::class);

        $errors = $validator->validate($formation);

        $this->assertGreaterThan(0, count($errors));
    }
}