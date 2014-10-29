<?php

namespace PBlondeau\Bundle\CommonBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class BaseWebTestCase extends WebTestCase
{
    protected function createAuthorizedClient()
    {
        $client = static::createClient();
        $container = static::$kernel->getContainer();

        $userRepository = $container->get('doctrine')->getRepository('PBlondeauUserBundle:User');
        $user = $userRepository->findOneByUsername('admin');

        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $session = $container->get('session');
        $session->set('_security_main', serialize($token));
        $session->save();

        $client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));

        return $client;
    }

    /**
     * @param string         $route         The name of the route
     * @param mixed          $parameters    An array of parameters
     * @param bool|string    $referenceType The type of reference (one of the constants in UrlGeneratorInterface)
     *
     * @return string The generated URL
     */
    protected function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return self::$kernel->getContainer()->get('router')->generate($route, $parameters, $referenceType);
    }
}
