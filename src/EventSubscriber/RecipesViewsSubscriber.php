<?php

namespace Drupal\recipes\EventSubscriber;

use Drupal\views\ViewEvents;
use Drupal\views\Event\ViewsQueryAlterEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class EntityTypeSubscriber.
 *
 * @package Drupal\recipes\RecipesViewsSubscriber
 */
class RecipesViewsSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   *
   * @return array
   *   The event names to listen for, and the methods that should be executed.
   */
  public static function getSubscribedEvents() : array {
    return [
      ViewEvents::QUERY_ALTER => 'removeIngredientsFromViews',
    ];
  }

  /**
   * Remove all ingredients from admin content listings.
   *
   * @param \Drupal\views\Event\ViewsQueryAlterEvent $event
   *   Entity type event.
   */
  public function removeIngredientsFromViews(ViewsQueryAlterEvent $event) {
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