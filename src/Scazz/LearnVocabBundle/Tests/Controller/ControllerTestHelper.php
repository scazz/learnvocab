<?php

namespace Scazz\LearnVocabBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ControllerTestHelper extends WebTestCase {

	protected $client;

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

	protected function assertArrayContainsAnObjectWithPropertyWithValue($array, $property, $value) {
		$found = $this->arrayContainsAnObjectWithPropertyWithValue($array, $property, $value);
		$this->assertTrue($found);
	}

	protected  function assertArrayNotContainsAnObjectWithPropertyWithValue($array, $property, $value) {
		$found = $this->arrayContainsAnObjectWithPropertyWithValue($array, $property, $value);
		$this->assertFalse($found);
	}

	private function arrayContainsAnObjectWithPropertyWithValue( $array, $property, $value ) {
		foreach ( $array as $testObject ) {
			if ( property_exists( $testObject, $property)) {
				if ($testObject->$property == $value) {
					return true;
				}
			}
		}
		return false;
	}

	protected  function setUpTest() {
		$this->client = static::createClient();
	}

	protected function fixtures() {
		$fixtures = array( 'Scazz\LearnVocabBundle\Tests\Fixtures\Entity\LoadBundleData');
		$this->loadFixtures( $fixtures );
	}

}