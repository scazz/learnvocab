<?php

namespace Scazz\LearnVocabBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Util\SecureRandom;

/**
 * SessionKey
 *
 * @ORM\Table("session_key")
 * @ORM\Entity()
 */
class SessionKey {

	/**
	 * @var string
	 *
	 * @ORM\Column(name="session_key", type="string", length=255)
	 * @ORM\Id
	 */
	private $key;

	/**
	 * @var datetime
	 *
	 * @ORM\Column(name="expiresAt", type="datetime", nullable=true)
	 */
	private $expiresAt;

	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="keys")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 */
	private $user;

	public function __construct(User $user, $expires=true) {
		$this->setUser($user);
		$this->setKey( $this->generateKey() );
		if ($expires) {
			$expiryTime = new DateTime();
			$expiryTime->add( new \DateInterval("P3600S"));
			$this->setExpiresAt( $expiryTime );
		} else {
			$this->setExpiresAt(null);
		}
	}

	private function setExpiresAt( $time ) {
		$this->expiresAt = $time;
	}

	public function setUser(User $user) {
		$this->user = $user;
	}

	private function setKey($key) {
		$this->key = $key;
		return $this;
	}
	public function getKey() {
		return $this->key;
	}

	private function generateKey() {
		$generator = new SecureRandom();
		$x = base64_encode( $generator->nextBytes(256) );
		return sha1($this->user->getPassword() .  $x, false);
	}
}