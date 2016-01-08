<?php

namespace Tests\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertContains('PHP', $crawler->filter('li > a')->eq(0)->text());
        $this->assertContains('MySQL', $crawler->filter('li > a')->eq(1)->text());
    }
}
