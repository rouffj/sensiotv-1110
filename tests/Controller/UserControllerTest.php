<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testSignupForm(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Create your account');

        $form = $client->getCrawler()->selectButton('Create your SensioTV account')->form();

        // When the form has errors
        $client->submit($form, [
            'user[firstName]' => 'a',
        ]);
        //print_r($client->getResponse()->getContent());die;

        $this->assertCount(4, $client->getCrawler()->filter('.form-error-icon'));

        // When the form is valid
    }
}
