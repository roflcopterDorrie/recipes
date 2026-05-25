<?php 
namespace Drupal\recipes\Controller;

use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\recipes\Entity\RecipeList;

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

  public function AddToList(int $recipe_id) {
    // Get the users default list.
    $storage = $this->entityTypeManager()->getStorage('recipes_recipe_list');
    $query = $storage->getQuery()
      ->condition('uid', $this->current_user->id())
      ->accessCheck(true);

    $ids = $query->execute();

    $recipe_list_node_id = reset($ids);

    $recipe_list = null;
    
    if (empty($ids)) { // Create a list since one doesn't exist.
      $recipe_list = RecipeList::create([
        'label' => 'Recipe list for user ' . $this->current_user->id(),
        'uid' => $this->current_user->id(),
      ]);
      if ($recipe_list->save()) {
        $recipe_list_node_id = $recipe_list->id();
      }
    } else { // Load the current list.
      $recipe_list = $storage->load($recipe_list_node_id);
    }
    
    if (isset($recipe_list)) {
    
      // Add recipe to the list.
      $recipe_list->get('recipes')->appendItem([
        'target_id' => $recipe_id,
      ]);
      
      /* Old way of doing it.
      $recipes = $recipe_list->get('field_recipes_recipe')->getValue();
      $recipes[] = ['target_id' => $recipe_id];
      $recipe_list->set('field_recipes_recipe', $recipes);*/

      $recipe_list->save();

      return ['#markup' => 'Recipe added to list.'];
    }

    return ['#markup' => 'Could not add recipe to list.'];
  }

  public function RemoveFromList($recipe_id) {
    // Check that list exists first.

    // Remove from list.
  }
}