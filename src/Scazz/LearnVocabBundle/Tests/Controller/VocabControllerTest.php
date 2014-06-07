<?php
use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Client;

use Scazz\LearnVocabBundle\Tests\Fixtures\Entity\LoadBundleData;

class VocabControllerTest extends WebTestCase {
	private $client;

	public function testJsonGetVocabAction() {
		$this->setUpTest();
		$this->fixtures();
		//$topic = array_pop( LoadBundleData::$topics );
		$vocab = array_pop( LoadBundleData::$vocabs );

		$route =  $this->getUrl('api_1_get_vocab', array('id'=>$vocab->getId(), '_format' => 'json'));
		$this->client->request('GET', $route, array('ACCEPT' => 'application/json'));
		$response = $this->client->getResponse();
		$this->assertJsonResponse( $response );
		$content = json_decode($response->getContent());

		$this->assertObjectNotHasAttribute( 'topic', $content->vocab );
	}

	public function testGetVocabThrows404OnError() {
		$this->setUpTest();
		$route =  $this->getUrl('api_1_get_vocab', array('id'=>-1, '_format' => 'json'));
		$this->client->request('GET', $route, array('ACCEPT' => 'application/json'));
		$response = $this->client->getResponse();
		$this->assertEquals(404, $response->getStatusCode());
	}

	public function testGetVocabsAction() {
		$this->setUpTest();
		$this->fixtures();
		$vocab = array_pop( LoadBundleData::$vocabs );

		$route =  $this->getUrl('api_1_get_vocabs', array('_format' => 'json'));
		$this->client->request('GET', $route, array('ACCEPT' => 'application/json'));
		$response = $this->client->getResponse();
		$this->assertJsonResponse( $response );
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