<?php

namespace App\Tests\Entity;

use App\Entity\Formation;
use PHPUnit\Framework\TestCase;

class FormationTest extends TestCase
{
    public function testGetPublishedAtString()
    {
        $formation = new Formation();

        $date = new \DateTime('2024-01-01');

        $formation->setPublishedAt($date);

        $this->assertEquals('01/01/2024', $formation->getPublishedAtString());
    }

    public function testGetPublishedAtStringNull()
    {
        $formation = new Formation();

        $this->assertEquals('', $formation->getPublishedAtString());
    }
}