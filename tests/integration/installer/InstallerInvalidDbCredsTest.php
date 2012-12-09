<?php

require_once dirname(dirname(__FILE__)) . '/goutte.phar';
use Goutte\Client;

class TestInstallerInvalidDbCreds extends PHPUnit_Framework_Testcase
{
    /**
     * @group installer
     * @group all
     */
    public function setUp()
    {
        $this->client = new Client();
    }

    public function tearDown()
    {
        unset($this->client);
    }
    /**
     * @test
     * Given a fresh Pyro install
     * When a user provides invalid db credentials
     * Then the install should error and halt
     */
    public function InstallWithInvalidDBCredentials()
    {
        $crawler = $this->client->request('GET','http://localhost');
        $link = $crawler->selectLink('Step #1')->link();
        $crawler = $this->client->click($link);
        $this->assertEquals($crawler->filter('title')->text(),'PyroCMS Installer');
        $form = $crawler->selectButton('Step #2')->form();
        $crawler = $this->client->submit($form,array('username'=>'test',
                                                     'password'=>'test',
                                                     'database'=>'pyrocms'
                                                ));
        $this->assertContains('Problem connecting to',$crawler->filter('.error')->text());
    }

    /**
     * @test
     * Given that the database field is not provided
     * When the form is submitted
     * Then an error should display on the same page
     */
     public function InstallWithMissingDB()
     {
         $crawler = $this->client->request('GET','http://localhost/installer');
         $link = $crawler->selectLink('Step #1')->link();
         $crawler = $this->client->click($link);
         $this->assertEquals($crawler->filter('title')->text(),'PyroCMS Installer');
         $form = $crawler->selectButton('Step #2')->form();
         $crawler = $this->client->submit($form,array('username'=>'pyro',
                                                      'password'=>'pyro'
                                                ));
         $this->assertContains('MySQL Database field is required',$crawler->filter('.error')->text());
     }


}
