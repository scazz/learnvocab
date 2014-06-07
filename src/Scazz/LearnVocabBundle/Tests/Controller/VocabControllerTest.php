<?php

namespace Scazz\LearnVocabBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Client;

use Scazz\LearnVocabBundle\Tests\Fixtures\Entity\LoadBundleData;

class VocabControllerTest extends ControllerTestHelper {

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
}