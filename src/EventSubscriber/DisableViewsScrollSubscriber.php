<?php

namespace Drupal\recipes\EventSubscriber;

use Drupal\views\Ajax\ViewAjaxResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class DisableViewsScrollSubscriber implements EventSubscriberInterface {

  public function onResponse(ResponseEvent $event) {
    $response = $event->getResponse();

    // Check if this response is coming from a Views AJAX request
    if ($response instanceof ViewAjaxResponse) {
      $view = $response->getView();
      
      // Target your specific view ID here
      if ($view->id() === 'recipes') {
        $commands = &$response->getCommands();
        foreach ($commands as $key => $command) {
          // Remove the core scrollTop command
          if ($command['command'] === 'scrollTop' || $command['command'] === 'viewsScrollTop') {
            unset($commands[$key]);
          }
        }
        // Re-index array
        $commands = array_values($commands);
      }
    }
  }

  public static function getSubscribedEvents() {
    return [
      KernelEvents::RESPONSE => ['onResponse', 0],
    ];
  }
}