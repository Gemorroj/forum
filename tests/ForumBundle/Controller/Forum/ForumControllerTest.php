<?php

namespace Tests\ForumBundle\Controller;

use Tests\ForumBundle\ForumWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ForumControllerTest extends ForumWebTestCase
{
    /**
     * @param array $topic
     * @dataProvider topicFixtureProvider
     */
    public function testShow($topic)
    {
        $crawler = self::$client->click(
            self::$crawler->filter('a.forum_link')->first()->link()
        );

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $search = function ($crawler, $topic) {
            $domElement = $crawler->filter('.topic_title')->getIterator();
            while ($domElement->valid()) {
                if ($topic['title'] == $domElement->current()->textContent) {
                    return true;
                }
                $domElement->next();
            }
            return false;
        };

        $isFound = false;
        while (true) {
            if (true === $isFound || false === $crawler) {
                break;
            }

            $isFound = $search($crawler, $topic);
            $crawler = $isFound ? false : self::pagination($crawler);
        }

        $this->assertTrue($isFound);
    }
}
