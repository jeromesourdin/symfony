<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProduitControllerTest extends WebTestCase
{
    public function testListe()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/produits/liste');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Liste des Produits', $crawler->filter('div h1 span')->text());
    }
}
