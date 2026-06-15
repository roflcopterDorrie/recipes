<?php 
namespace Drupal\recipes\Controller;

use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\recipes\Entity\RecipeList;
use Symfony\Component\HttpFoundation\Request;

class RecipeListController extends ControllerBase {

  protected AccountProxyInterface $current_user;

  public function __construct(AccountProxyInterface $current_user) {
    $this->current_user = $current_user;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user')
    );
  }

  public function AddToList(Request $request, int $recipe_id) {
    // Get the users default list.
    $storage = $this->entityTypeManager()->getStorage('recipes_recipe_list');
    $entities = $storage->loadByProperties([
      'uid' => $this->current_user->id(),
    ]);
    $recipe_list = reset($entities) ?: NULL;
    
    if (!$recipe_list) { // Create a list since one doesn't exist.

      // Check permissions to create shopping lists.
      $list_access_control_handler = $this->entityTypeManager()->getAccessControlHandler('recipes_recipe_list');
      if (!$list_access_control_handler->createAccess(NULL, $this->current_user)) {
        $this->messenger()->addError('You do not have permission to create a new list.');
          return;
      }

      $recipe_list = RecipeList::create([
        'label' => 'Recipe list for user ' . $this->current_user->id(),
        'uid' => $this->current_user->id(),
      ]);
      $recipe_list->save();
    }
    
    if ($recipe_list) {
    
      // Add recipe to the list.
      $recipe_list->get('recipes')->appendItem([
        'target_id' => $recipe_id,
      ]);

      $recipe_list->save();

      $this->messenger()->addMessage('Recipe added.');
    }

    $referer = $request->headers->get('referer');

    if ($referer) {
      return new \Drupal\Core\Routing\TrustedRedirectResponse($referer);
    }
  }

  public function RemoveFromList(Request $request, int $recipe_id) {
    // Get the users default list.
    $storage = $this->entityTypeManager()->getStorage('recipes_recipe_list');
    $entities = $storage->loadByProperties([
      'uid' => $this->current_user->id(),
    ]);
    $recipe_list = reset($entities) ?: NULL;
    
    if ($recipe_list) { 
      if (!$recipe_list->access('update', $this->current_user)) {
        $this->messenger()->addError('You do not have permission to update this list.');
      } else {
        foreach($recipe_list->get('recipes')->referencedEntities() as $delta => $recipe) {
          if ($recipe->id() == $recipe_id) {
            $recipe_list->get('recipes')->removeItem($delta);
            $recipe_list->save();
            break;
          }
        }
        $this->messenger()->addMessage('Recipe removed.', $this->messenger()::TYPE_STATUS);
      }
    }

    $referer = $request->headers->get('referer');

    if ($referer) {
      return new \Drupal\Core\Routing\TrustedRedirectResponse($referer);
    }
  }
}