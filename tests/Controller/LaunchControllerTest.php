<?php

namespace Kitchen\Tests;

use Silex\WebTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LaunchControllerTest extends WebTestCase
{
    public function testGetLunchPage()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse() instanceof RedirectResponse);
        $this->assertTrue($client->getResponse()->isRedirect('/lunch'));

        $crawler = $client->followRedirect();

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
        $this->assertContains('Hotdog', $client->getResponse()->getContent());
    }

    public function createApplication()
    {
        $app = require __DIR__.'/../../../app/bootstrap.php';
        $app['session.test'] = true;

        return $this->app = $app;
    }
}
