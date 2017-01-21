<?php

namespace prizephitah\ArkLog\Web\Security;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use prizephitah\ArkLog\Web\SlimAwareController;

/**
 * Handles the authentication routes.
 */
class LoginController extends SlimAwareController {

  /**
   * Shows the login prompt.
   * @param  ServerRequestInterface $request  The request.
   * @param  ResponseInterface      $response The response.
   * @return ResponseInterface                The response with the login view.
   */
  public function login(ServerRequestInterface $request, ResponseInterface $response) {
    return $this->view->render($response, 'login.twig.html');
  }

  /**
   * Processes any login attempt.
   * @param  ServerRequestInterface $request  The request.
   * @param  ResponseInterface      $response The response.
   * @return ResponseInterface                The response with the authentication result.
   */
  public function authenticate(ServerRequestInterface $request, ResponseInterface $response) {
    $authenticator = new SimplePasswordAuthenticationMiddleware($this->container);
    $requestParameters = $request->getParsedBody();
    if ($authenticator->validatePassword($requestParameters['password'])) {
      if (isset($requestParameters['remember_me'])) {
        $response = $authenticator->rememberMe($response);
      }
      $url = $this->container->router->pathFor('home');
      return $response->withRedirect($url, 303);
    }
    $url = $this->container->router->pathFor('login');
    return $response->withRedirect($url, 401);
  }
}
