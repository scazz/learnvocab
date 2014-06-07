<?php

namespace Scazz\LearnVocabBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Client;

use Scazz\LearnVocabBundle\Tests\Fixtures\Entity\LoadBundleData;

class TopicControllerTest extends ControllerTestHelper {
	public function testJsonGetTopicAction() {
		$this->setUpTest();
		$this->fixtures();
		$topic = LoadBundleData::$topics[0];
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

	public function testGetTopicsWithIdQueryParameters() {
		$this->setUpTest();
		$this->fixtures();

		$topicToLookFor = array_pop( LoadBundleData::$topics );
		$topicToExclude = array_pop( LoadBundleData::$topics );

		$route =  $this->getUrl('api_1_get_topics', array('ids' => array($topicToLookFor->getId()), '_format' => 'json'));
		$this->client->request('GET', $route, array('ACCEPT' => 'application/json'));
		$response = $this->client->getResponse();
		$this->assertJsonResponse( $response );
		$content = json_decode( $response->getContent() );

		$this->assertArrayContainsAnObjectWithPropertyWithValue($content->topics, 'id', $topicToLookFor->getId());
		$this->assertArrayNotContainsAnObjectWithPropertyWithValue($content->topics, 'id', $topicToExclude->getId());
	}

	public function testPostTopicAction() {
		$this->setUpTest();
		$this->fixtures();
		$this->fixtures();

		$vocab = LoadBundleData::$vocabs[0];

		$serializedTopic = '{"topic":{"name":"test","isTemplate":false,"vocabs":["'.$vocab->getId().'"]}}';
		$route = $this->getUrl('api_1_post_topic', array('_format'=>'json'));
		$this->client->request('POST', $route, array(), array(), array('CONTENT_TYPE' => 'application/json'), $serializedTopic );

		$response = $this->client->getResponse();
		$this->assertJsonResponse($response, 201, false );
		$returnedTopic = json_decode($response->getContent());
		$originalTopic = json_decode($serializedTopic);
		$this->assertEquals($originalTopic->topic->name, $returnedTopic->topic->name);
		$this->assertEquals($vocab->getId(), $returnedTopic->topic->vocabs[0]);
	}

	public function testPostTopicActionReturns400WithInvalidParameters() {
		$this->setUpTest();

		$serializedTopic = '{"topic":{"invalid":"test","fieldinvalid":false,"vocabs":[]}}';
		$route = $this->getUrl('api_1_post_topic', array('_format'=>'json'));
		$this->client->request('POST', $route, array(), array(), array('CONTENT_TYPE' => 'application/json'), $serializedTopic );
		$response = $this->client->getResponse();
		$this->assertJsonResponse($response, 400, false );
	}

	public function testPutSubjectAction() {
		$this->setUpTest();
		$this->fixtures();
		$topic = LoadBundleData::$topics[0];
		$vocab = LoadBundleData::$vocabs[0];

		$serializedTopic = '{"topic":{"name":"newName","isTemplate":false,"vocabs":["'.$vocab->getId().'"]}}';
		$route = $this->getUrl('api_1_put_topic', array('id'=>$topic->getId(),'_format'=>'json'));
		$this->client->request('PUT', $route, array(), array(), array('CONTENT_TYPE' => 'application/json'), $serializedTopic );
		$response = $this->client->getResponse();
		$this->assertJsonResponse($response, 201, false );
		$returnedTopic = json_decode($response->getContent());

		$this->assertNotEquals($topic->getName(), $returnedTopic->topic->name );
	}

}