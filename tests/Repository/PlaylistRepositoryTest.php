<?php

namespace App\Tests\Repository;

use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PlaylistRepositoryTest extends KernelTestCase
{
    private $playlistRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->playlistRepository = self::getContainer()->get(PlaylistRepository::class);
    }

    public function testFindAllOrderByName()
    {
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');

        $this->assertIsArray($playlists);
        $this->assertNotEmpty($playlists);
    }

    public function testFindAllOrderByNbFormations()
    {
        $playlists = $this->playlistRepository->findAllOrderByNbFormations('ASC');

        $this->assertIsArray($playlists);
        $this->assertNotEmpty($playlists);
    }

    public function testFindByContainValueName()
    {
        $playlists = $this->playlistRepository->findByContainValue('name', 'Symfony');

        $this->assertIsArray($playlists);
    }

    public function testFindByContainValueEmpty()
    {
        $playlists = $this->playlistRepository->findByContainValue('name', '');

        $this->assertIsArray($playlists);
        $this->assertNotEmpty($playlists);
    }
}