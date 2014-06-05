<?php

namespace Scazz\LearnVocabBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Client;

use Scazz\LEarnVocabBundle\Tests\Fixtures\Entity\LoadBundleData;

class SubjectControllerTest extends WebTestCase {
    private $client;

    public function testJsonGetSubjectsAction() {
        $this->setUpTest();
        $fixtures = array( 'Scazz\LearnVocabBundle\Tests\Fixtures\Entity\LoadBundleData');
        $this->loadFixtures( $fixtures );
		$subject = array_pop( LoadBundleData::$subjects );


        $route =  $this->getUrl('api_1_get_subject', array('id'=>$subject->getId(), '_format' => 'json'));
        $this->client->request('GET', $route, array('ACCEPT' => 'application/json'));
        $response = $this->client->getResponse();
        $this->assertJsonResponse( $response );

        //return LoadBundleData::
    }


    private function setUpTest() {
        $this->client = static::createClient();
    }

    protected function assertJsonResponse(Response $response, $statusCode = 200, $checkValidJson =  true, $contentType = 'application/json')
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', $contentType),
            $response->headers
        );

        if ($checkValidJson) {
            $decode = json_decode($response->getContent());
            $this->assertTrue(($decode != null && $decode != false),
                'is response valid json: [' . $response->getContent() . ']'
            );
        }
    }
}