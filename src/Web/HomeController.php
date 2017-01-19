<?php

namespace prizephitah\ArkLog\Web;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use prizephitah\ArkLog\Persistence\ArkPlayerController;
use prizephitah\ArkLog\Persistence\ArkSessionController;

class HomeController extends SlimAwareController {

  public function home(ServerRequestInterface $request, ResponseInterface $response) {
    $playerController = new ArkPlayerController($this->config);
    $sessionController = new ArkSessionController($this->config);
    $start = new \DateTime('-24 hours');
    $recentPlayers = $playerController->getRecentlyUpdatedPlayers($start);
    $recentSessions = $sessionController->getRecentSessions($start);
    return $this->view->render($response, 'index.twig.html', [
      'players' => json_encode($recentPlayers),
      'sessions' => json_encode($recentSessions),
      'start' => $start->format(\DateTime::ISO8601),
      'end' => (new \DateTime)->format(\DateTime::ISO8601)
    ]);
  }
}
