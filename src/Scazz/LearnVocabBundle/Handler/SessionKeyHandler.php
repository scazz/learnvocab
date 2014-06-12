<?php

namespace Scazz\LearnVocabBundle\Handler;

use Scazz\LearnVocabBundle\Entity\SessionKey;
use Scazz\LearnVocabBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;

class SessionKeyHandler {

	private $om;

	public function __construct(ObjectManager $om) {
		$this->om = $om;
	}

	public function generateKey(User $user) {
		$key = new SessionKey($user, false);
		$this->om->persist($key);
		$this->om->flush();
		return $key;
	}

	public function deleteKey($key) {
		$repo = $this->om->getRepository("ScazzLearnVocabBundle:SessionKey");
		$sessionKey = $repo->find($key);
		if (!$sessionKey) {
			echo "fail... no key!";
		}

		$this->om->remove($sessionKey);
		$this->om->flush();
	}



}

