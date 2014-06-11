<?php

namespace Scazz\LearnVocabBundle\Security\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Scazz\LearnVocabBundle\Security\Authentication\Token\AuthToken;
use Symfony\Component\Security\Core\Util\StringUtils;

class AuthProvider implements AuthenticationProviderInterface
{
	private $userProvider;

	public function __construct(UserProviderInterface $userProvider)
	{
		$this->userProvider = $userProvider;
	}

	public function authenticate(TokenInterface $token)
	{
		$user = $this->userProvider->loadUserByUsername($token->getUsername());

		if ($user && $this->validateDigest($token->digest, $user->getKeys())) {
			$authenticatedToken = new AuthToken($user->getRoles());
			$authenticatedToken->setUser($user);

			return $authenticatedToken;
		}

		throw new AuthenticationException('The authentication failed.');
	}

	protected function validateDigest($digest, $keys)
	{
		foreach( $keys as $key ) {
			if (StringUtils::equals($key->getKey(), $digest))
				return true;
		}
		return false;
	}

	public function supports(TokenInterface $token)
	{
		return $token instanceof AuthToken;
	}
}