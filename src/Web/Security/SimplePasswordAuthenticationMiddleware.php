<?php

namespace prizephitah\ArkLog\Web\Security;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\RouterInterface;
use SlimSession\Helper as Session;

/**
 * Provides simple password protection.
 *
 * Password is defined in config and validates against both session and cookie.
 */
class SimplePasswordAuthenticationMiddleware {

  /** @var string */
  private $passwordHash;

  /** @var Session */
  protected $session;

  /** @var RouterInterface */
  protected $router;

  /**
   * Sets up the middleware.
   * @param ContainerInterface $container The DI container.
   */
  public function __construct(ContainerInterface $container) {
    $this->passwordHash = empty($container->config->get('password'))
      ? null : sha1($container->config->get('password'));
    $this->session = $container->session;
    $this->router = $container->router;
  }

  /**
   * The middleware entrypoint.
   * @param  ServerRequestInterface $request  The request.
   * @param  ResponseInterface      $response The response.
   * @param  callable               $next     The next method in the chain.
   * @return ResponseInterface                The resulting response.
   */
  public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next) {
    if ($this->passwordHash) {
      if (!($this->session->get('authorized') || $this->isRemembered($request))) {
        $url = $this->router->pathFor('login');
        return $response->withRedirect($url, 401);
      }
    }
    $response = $next($request, $response);
    return $response;
  }

  /**
   * Validates the value in a remember me cookie.
   * @param  ServerRequestInterface $request The request.
   * @return boolean                         Whether the cookie is valid.
   */
  protected function isRemembered(ServerRequestInterface $request) {
    $cookies = $request->getCookieParams();
    return sha1($this->passwordHash) == $cookies['remember_me'];
  }

  /**
   * Validates the supplied password against the one set in the config.
   *
   * Also sets a session flag indicating that the user is authenticated,
   * preventing future login prompts for the session.
   * @param  string $password The password to validate.
   * @return boolean          Whether the password is valid or not.
   */
  public function validatePassword($password) {
    if ($this->passwordHash == sha1($password)) {
      $this->session->set('authorized', true);
      return true;
    }
    return false;
  }

  /**
   * Sets a remember me cookie preventing the user from seeing login prompts
   * for the forseable future.
   * @param  ResponseInterface $response The response.
   * @return ResponseInterface           The response with the cookie set.
   */
  public function rememberMe(ResponseInterface $response) {
    $cookie = urlencode('remember_me').'='.urlencode(sha1($this->passwordHash)).'; '
      .'expires='.(new \DateTime('2038-01-01'))->format(\DateTime::COOKIE).'; '
      .'path=/; HttpOnly';
      return $response->withAddedHeader('Set-Cookie', $cookie);
  }
}
