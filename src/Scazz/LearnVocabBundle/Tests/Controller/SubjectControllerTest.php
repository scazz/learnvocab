<?php

namespace Scazz\LearnVocabBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Client;

use Scazz\LearnVocabBundle\Tests\Fixtures\Entity\LoadBundleData;

class SubjectControllerTest extends ControllerTestHelper {
    public function testJsonGetSubjectAction() {
        $this->setUpTest();
		$this->fixtures();
		$subject = array_pop( LoadBundleData::$subjects );

        $route =  $this->getUrl('api_1_get_subject', array('id'=>$subject->getId(), '_format' => 'json'));
        $this->client->request('GET', $route, array('ACCEPT' => 'application/json'));
        $response = $this->client->getResponse();
        $this->assertJsonResponse( $response );
    }

	public function testGetSubjectThrows404OnError() {
		$this->setUpTest();
		$this->fixtures();
		$route =  $this->getUrl('api_1_get_subject', array('id'=>-1, '_format' => 'json'));
		$this->client->request('GET', $route, array('ACCEPT' => 'application/json'));
		$response = $this->client->getResponse();
		$this->assertEquals( 404, $response->getStatusCode());
	}

	public function testGetSubjectsWithIdQueryParameters() {
		$this->setUpTest();
		$this->fixtures();

		$subjectToLookFor = array_pop( LoadBundleData::$subjects );
		$subjectToExclude = array_pop( LoadBundleData::$subjects );

		$route =  $this->getUrl('api_1_get_subjects', array('ids' => array($subjectToLookFor->getId()), '_format' => 'json'));
		$this->client->request('GET', $route, array('ACCEPT' => 'application/json'));
		$response = $this->client->getResponse();
		$this->assertJsonResponse( $response );
		$content = json_decode( $response->getContent() );

	//	echo "\n\n ".$subjectToExclude->getId()."<-- \n";
	//	echo "\n\n ".$subjectToLookFor->getId()."<-- \n";

		$this->assertArrayContainsAnObjectWithPropertyWithValue($content->subjects, 'id', $subjectToLookFor->getId());
		$this->assertArrayNotContainsAnObjectWithPropertyWithValue($content->subjects, 'id', $subjectToExclude->getId());
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

	public function testJsonGetSubjectShouldIncludeTopicId() {
		$this->setUpTest();
		$this->fixtures();
		$subject =  LoadBundleData::$subjects[0];
		$topic = array_pop ( LoadBundleData::$topics );

		$route =  $this->getUrl('api_1_get_subject', array('id'=>$subject->getId(), '_format' => 'json'));
		$this->client->request('GET', $route, array('ACCEPT' => 'application/json'));
		$response = $this->client->getResponse();
		$content = json_decode( $response->getContent() );
		$this->assertTrue( in_array( $topic->getId(), $content->subject->topics));
	}
}