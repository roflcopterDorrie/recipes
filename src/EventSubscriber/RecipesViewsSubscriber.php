<?php

namespace Drupal\recipes\EventSubscriber;

use Drupal\views\Plugin\views\query\Sql;
use Drupal\views_event_dispatcher\Event\Views\ViewsQueryAlterEvent;
use Drupal\views_event_dispatcher\ViewsHookEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class RecipesViewsSubscriber.
 *
 * @package Drupal\recipes\RecipesViewsSubscriber
 */
class RecipesViewsSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      ViewsHookEvents::VIEWS_QUERY_ALTER => 'removeIngredientsFromViews',
    ];
  }

  /**
   * Query alter event handler.
   *
   * @param \Drupal\views_event_dispatcher\Event\Views\ViewsQueryAlterEvent $event
   *   The event.
   */
  public function removeIngredientsFromViews(ViewsQueryAlterEvent $event): void {
    $view = $event->getView();  
  
    // Remove ingredients from the main admin content listing.
    if ($view->id() == 'content' && $view->current_display == 'page_1') {
      $query = $event->getQuery();
      
      if ($query instanceof Sql) {
        // Ensure the base table exists and get its alias.
        $alias = $query->ensureTable('node_field_data');

        $query->addWhere(
          0,
          "$alias.type",
          'recipes_ingredient',
          '!='
        );
      }
    }
  }


}