<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PagesTest extends WebTestCase
{
    public function testAccueilAccessible()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h3', 'Bienvenue sur le site de MediaTek86');
    }

    public function testTriFormationsAccessible()
    {
        $client = static::createClient();
        $client->request('GET', '/formations/tri/title/ASC');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('table');
    }

    public function testFiltreFormationsAccessible()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');

        $form = $crawler->filter('form')->first()->form([
            'recherche' => 'Symfony'
        ]);

        $client->submit($form);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('table');
    }

    public function testLienVersFormation()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');

        $link = $crawler->filter('a[href*="/formations/formation/"]')->first()->link();
        $client->click($link);

        $this->assertResponseIsSuccessful();
    }

    public function testTriPlaylistsAccessible()
    {
        $client = static::createClient();
        $client->request('GET', '/playlists/tri/name/ASC');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('table');
    }

    public function testFiltrePlaylistsAccessible()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists');

        $form = $crawler->filter('form')->first()->form([
            'recherche' => 'Symfony'
        ]);

        $client->submit($form);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('table');
    }

    public function testLienVersPlaylist()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists');

        $link = $crawler->filter('a[href*="/playlists/playlist/"]')->first()->link();
        $client->click($link);

        $this->assertResponseIsSuccessful();
    }
}