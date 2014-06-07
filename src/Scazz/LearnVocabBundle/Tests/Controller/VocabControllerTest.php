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

	public function testGetVocabsWithIdQueryParameters() {
		$this->setUpTest();
		$this->fixtures();

		$vocabToLookFor = array_pop( LoadBundleData::$vocabs );
		$vocabToExclude = array_pop( LoadBundleData::$vocabs );

		$route =  $this->getUrl('api_1_get_vocabs', array('ids' => array($vocabToLookFor->getId()), '_format' => 'json'));
		$this->client->request('GET', $route, array('ACCEPT' => 'application/json'));
		$response = $this->client->getResponse();
		$this->assertJsonResponse( $response );
		$content = json_decode( $response->getContent() );

		$this->assertArrayContainsAnObjectWithPropertyWithValue($content->vocabs, 'id', $vocabToLookFor->getId());
		$this->assertArrayNotContainsAnObjectWithPropertyWithValue($content->vocabs, 'id', $vocabToExclude->getId());
	}

	public function testPostVocabAction() {
		$this->setUpTest();

		$serializedVocab = '{"vocab":{"native":"test","translated":"translatedTest","isLearnt":false,"timesCorrectlyAnswered":2}}';
		$route = $this->getUrl('api_1_post_vocab', array('_format'=>'json'));
		$this->client->request('POST', $route, array(), array(), array('CONTENT_TYPE' => 'application/json'), $serializedVocab );
		$response = $this->client->getResponse();
		$this->assertJsonResponse($response, 201, false );
		$returnedVocab = json_decode($response->getContent());
		$originalVocab = json_decode($serializedVocab);
		$this->assertEquals($originalVocab->vocab->native, $returnedVocab->vocab->native);
		$this->assertEquals($originalVocab->vocab->timesCorrectlyAnswered,$returnedVocab->vocab->timesCorrectlyAnswered);
	}

	public function testPutVocabAction() {
		$this->setUpTest();
		$this->fixtures();
		$vocab = LoadBundleData::$vocabs[0];

		$serializedVocab = '{"vocab":{"native":"newNative","translated":"newtranslatedTest","isLearnt":true}}';
		$route = $this->getUrl('api_1_put_vocab', array('id'=>$vocab->getId(),'_format'=>'json'));
		$this->client->request('PUT', $route, array(), array(), array('CONTENT_TYPE' => 'application/json'), $serializedVocab );
		$response = $this->client->getResponse();
		$this->assertJsonResponse($response, 201, false );
		$returnedVocab = json_decode($response->getContent());

		$this->assertNotEquals($vocab->getNative(), $returnedVocab->vocab->native );
	}

	public function testDeleteVocabAction() {
		$this->setUpTest();
		$this->fixtures();
		$vocab = LoadBundleData::$vocabs[0];
		$route = $this->getUrl('api_1_delete_vocab', array('id'=>$vocab->getId(),'_format'=>'json'));
		$this->client->request('DELETE', $route, array());
		$response = $this->client->getResponse();
		$this->assertJsonResponse($response, 204, false );

		$route =  $this->getUrl('api_1_get_vocab', array('id'=>$vocab->getId(), '_format' => 'json'));
		$this->client->request('GET', $route, array('ACCEPT' => 'application/json'));
		$response = $this->client->getResponse();
		$this->assertEquals(404, $response->getStatusCode());

	}
}