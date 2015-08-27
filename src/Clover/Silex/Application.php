<?php
namespace Clover\Silex;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Application extends \Silex\Application
{
    use \Silex\Application\TwigTrait;
    use \Silex\Application\UrlGeneratorTrait;

    public function currentPath()
    {
        return $this['request']->getPathInfo();
    }

    public function currentUrl()
    {
        return sprintf("%s://%s%s",
            $this['request']->getScheme(),
            $this['request']->getHost(),
            $this['request']->getPathInfo()
        );
    }

    public function currentUri()
    {
        return sprintf("%s://%s%s",
            $this['request']->getScheme(),
            $this['request']->getHost(),
            $this['request']->getRequestUri()
        );
    }

    public function baseUrl()
    {
        return sprintf("%s://%s",
            $this['request']->getScheme(),
            $this['request']->getHost()
        );
    }

    public function isMobile()
    {
        $userAgent = $this->getUserAgent();
        if (!$userAgent) {
            return false;
        }
        if (strpos($userAgent, 'iPhone') !== false
            || strpos($userAgent, 'iPod') !== false
            || preg_match('/Android(.+)Mobile/', $userAgent)) {
            return true;
        }
        return false;
    }

    public function getUserAgent()
    {
        return $this['request']->server->get('HTTP_USER_AGENT');
    }

}
