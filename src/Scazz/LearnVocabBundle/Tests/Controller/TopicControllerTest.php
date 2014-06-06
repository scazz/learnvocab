<?php

use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Client;

use Scazz\LearnVocabBundle\Tests\Fixtures\Entity\LoadBundleData;

class TopicControllerTest extends WebTestCase {
	private $client;

	public function testJsonGetTopicAction() {
		$this->setUpTest();
		$this->fixtures();
		$topic = array_pop( LoadBundleData::$topics );
		$vocab = array_pop( LoadBundleData::$vocabs );

		$route =  $this->getUrl('api_1_get_topic', array('id'=>$topic->getId(), '_format' => 'json'));
		$this->client->request('GET', $route, array('ACCEPT' => 'application/json'));
		$response = $this->client->getResponse();
		$this->assertJsonResponse( $response );
		$content = json_decode($response->getContent());
		$this->assertObjectNotHasAttribute( 'subject', $content->topic );

		/*
		 * Ensure vocabs come as a list of IDs, not embedded into response
		 */
		$this->assertObjectHasAttribute( 'vocabs', $content->topic );
		$this->assertTrue( in_array( $vocab->getId(), $content->topic->vocabs ));
	}

	public function testGetTopicThrows404OnError() {
		$this->setUpTest();
		$this->fixtures();
		$route =  $this->getUrl('api_1_get_topic', array('id'=>-1, '_format' => 'json'));
		$this->client->request('GET', $route, array('ACCEPT' => 'application/json'));
		$response = $this->client->getResponse();
		$this->assertEquals( 404, $response->getStatusCode());
	}

	public function testJsonGetTopicsAction() {
		$this->setUpTest();
		$this->fixtures();

		$route =  $this->getUrl('api_1_get_topics', array('_format' => 'json'));
		$this->client->request('GET', $route, array('ACCEPT' => 'application/json'));
		$response = $this->client->getResponse();
		$this->assertJsonResponse( $response );

		$content = json_decode( $response->getContent() );
		$this->assertObjectHasAttribute('topics', $content);
		$this->assertArrayHasKey(0, $content->topics);
		$this->assertObjectHasAttribute( 'id', $content->topics[0] );
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