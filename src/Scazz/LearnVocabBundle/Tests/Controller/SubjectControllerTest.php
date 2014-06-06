<?php

namespace Scazz\LearnVocabBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Client;

use Scazz\LEarnVocabBundle\Tests\Fixtures\Entity\LoadBundleData;

class SubjectControllerTest extends WebTestCase {
    private $client;

    public function testJsonGetSubjectAction() {
        $this->setUpTest();
		$this->fixtures();
		$subject = array_pop( LoadBundleData::$subjects );

        $route =  $this->getUrl('api_1_get_subject', array('id'=>$subject->getId(), '_format' => 'json'));
        $this->client->request('GET', $route, array('ACCEPT' => 'application/json'));
        $response = $this->client->getResponse();
        $this->assertJsonResponse( $response );
    }

	public function testGetSubjectsThrows404OnError() {
		$this->setUpTest();
		$this->fixtures();
		$route =  $this->getUrl('api_1_get_subject', array('id'=>-1, '_format' => 'json'));
		$this->client->request('GET', $route, array('ACCEPT' => 'application/json'));
		$response = $this->client->getResponse();
		$this->assertEquals( 404, $response->getStatusCode());
	}

	public function testJsonGetSubjectsAction() {
		$this->setUpTest();
		$this->fixtures();
		$subject = array_pop( LoadBundleData::$subjects );

		$route =  $this->getUrl('api_1_get_subjects', array('_format' => 'json'));
		$this->client->request('GET', $route, array('ACCEPT' => 'application/json'));
		$response = $this->client->getResponse();
		$this->assertJsonResponse( $response );

		$content = json_decode( $response->getContent() );
		$this->assertObjectHasAttribute('subjects', $content);
		$this->assertArrayHasKey(0, $content->subjects);
		$this->assertObjectHasAttribute( 'id', $content->subjects[0] );
	}

    private function setUpTest() {
        $this->client = static::createClient();
    }

	private function fixtures() {
		$fixtures = array( 'Scazz\LearnVocabBundle\Tests\Fixtures\Entity\LoadBundleData');
		$this->loadFixtures( $fixtures );
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