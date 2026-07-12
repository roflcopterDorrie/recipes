<?php

namespace Drupal\recipes\Services;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Url;
use Drupal\Core\Security\TrustedCallbackInterface;

class MyList implements TrustedCallbackInterface {

  public function __construct(
    protected AccountProxyInterface $currentUser,
    protected EntityTypeManagerInterface $entityTypeManager,
  ) {}

  public static function trustedCallbacks() {
    return ['buildButton'];
  }

  public function buildButton(int $recipe_id): array {

    $uid = $this->currentUser->id();

    $storage = $this->entityTypeManager
      ->getStorage('recipes_recipe_list');

    $entities = $storage->loadByProperties([
      'uid' => $uid,
    ]);

    $recipe_list = reset($entities) ?: NULL;

    $in_list = FALSE;

    if ($recipe_list) {
      foreach ($recipe_list->get('recipes') as $item) {
        if ((int) $item->target_id === (int) $recipe_id) {
          $in_list = TRUE;
          break;
        }
      }
    }

    return [
      '#theme' => 'my_list_button',
      '#weight' => 100,
      '#label' => $in_list ? 'Remove from list' : 'Add to list',
      '#id' => $recipe_id,
      '#route' => $in_list
        ? 'recipes.remove_from_list'
        :'recipes.add_to_list',
      '#cache' => [
        'contexts' => [
          'user',
        ],
        'tags' => [
          'recipes_recipe_list:' . $uid,
        ],
      ],
    ];
  }

}
