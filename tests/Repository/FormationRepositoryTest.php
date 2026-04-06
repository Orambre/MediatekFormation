<?php

namespace App\Tests\Repository;

use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FormationRepositoryTest extends KernelTestCase
{
    private $formationRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->formationRepository = self::getContainer()->get(FormationRepository::class);
    }

    public function testFindAll()
    {
        $formations = $this->formationRepository->findAll();

        $this->assertIsArray($formations);
        $this->assertNotEmpty($formations);
    }

    public function testFindAllLasted()
    {
        $formations = $this->formationRepository->findAllLasted(3);

        $this->assertLessThanOrEqual(3, count($formations));
    }

    public function testFindAllOrderByTitleAsc()
    {
        $formations = $this->formationRepository->findAllOrderBy('title', 'ASC');

        $this->assertIsArray($formations);
        $this->assertNotEmpty($formations);
    }

    public function testFindByContainValueTitle()
    {
        $formations = $this->formationRepository->findByContainValue('title', 'Symfony');

        $this->assertIsArray($formations);
    }

    public function testFindAllForOnePlaylist()
    {
        $formations = $this->formationRepository->findAllForOnePlaylist(1);

        $this->assertIsArray($formations);
    }
}