<?php

namespace Tests\ForumBundle;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class ForumWebTestCase extends WebTestCase
{
    /**
     * @var Client
     */
    protected static $client;

    protected function setUp()
    {
        self::$client = static::createClient();
    }
}
