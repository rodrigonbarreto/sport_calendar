<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/en/');
        $client->getResponse()->getStatusCode();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('List of exercises', $crawler->filter('.container h1')->text());
    }
}
