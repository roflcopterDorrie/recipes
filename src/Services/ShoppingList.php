<?php

namespace Drupal\recipes\Services;

use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Messenger\Messenger;
use Drupal\recipes\Entity\ShoppingList as ShoppingListEntity;
use Drupal\recipes\Entity\ShoppingListItem;

class ShoppingList
{
  use DependencySerializationTrait;

  protected ShoppingListEntity $shopping_list;
  protected AccountInterface $user;

  public function __construct(
    protected EntityTypeManagerInterface $entity_type_manager,
    protected Messenger $messenger,
    protected Ingredient $ingredient_service
  ) {}

  /**
   * @return ShoppingListItem[]
   */
  public array $shopping_list_items {
    get {
      if (!isset($this->shopping_list_items)) {
        $this->shopping_list_items = $this->getShoppingListItems();
      }
      return $this->shopping_list_items;
    }
  }

  public function load(AccountInterface $user) : ?ShoppingList {
    $this->user = $user;
    $storage = $this->entity_type_manager->getStorage('recipes_shopping_list');
    $entities = $storage->loadByProperties([
      'uid' => $user->id(),
    ]);
    if (!empty($entities)) {
      $this->shopping_list = reset($entities);
    }

    if (!isset($this->shopping_list)) {
      // We don't have a shopping list yet for this user, create one.

      // Check permissions to create shopping lists.
      $list_access_control_handler = $this->entity_type_manager->getAccessControlHandler('recipes_shopping_list');
      if (!$list_access_control_handler->createAccess(NULL, $this->user)) {
        $this->messenger->addError('You do not have permission to create a new shopping list.');
        return NULL;
      }
      // Create new shopping list.
      $this->shopping_list = ShoppingListEntity::create([
        'name' => 'Shopping list for ' . $this->user->id(),
        'uid' => $this->user->id(),
      ]);
      $this->shopping_list->save();
    }


    return $this;
  }

  /**
   * @return ShoppingListItem[]
   */
  private function getShoppingListItems() : ?array {
    // Load up all the shopping list items.
    $storage = $this->entity_type_manager->getStorage('recipes_shopping_list_item');
    $shopping_list_items = $storage->loadByProperties([
      'recipes_shopping_list_id' => $this->shopping_list->id(),
    ]);
    return $shopping_list_items;
  }

  public function getIngredient(ShoppingListItem $item) : ?Node{
    $ingredient = $item->get('recipes_ingredient_id')->entity;
    return $ingredient ? $this->ingredient_service->populate($ingredient) : NULL;
  }

  public function delete() : bool {
    if ($this->shopping_list) {
      // Make sure the user has permission to delete this shopping list.
      if (!$this->shopping_list->access('delete', $this->user)) {
        $this->messenger->addError('You do not have permission to delete this shopping list.');
        return FALSE;
      }
      $this->shopping_list->delete();
    }
    return TRUE;
  }

  public function clear() {
    if ($this->shopping_list) {
      foreach($this->shopping_list_items as $item) {
        $ingredient = $this->getIngredient($item);
        if ($ingredient == null) { // If ingredient is missing, just delete it.
          $item->delete();
          return;
        }
        $ingredient = $this->ingredient_service->populate($ingredient);
        if ($ingredient->aisle->getName() != 'Custom' || ($ingredient->aisle->getName() == 'Custom' && $item->get('collected')->value == 1)) {
          if ($item->access('delete', $this->user)) {
            $item->delete();
          }
        }
      }
    }
  }

  public function addIngredients(array $ingredient_ids) {
    if ($this->shopping_list) {  
      // Check permissions to create ShoppingListItems.
      $list_item_access_control_handler = $this->entity_type_manager->getAccessControlHandler('recipes_shopping_list_item');
      if (!$list_item_access_control_handler->createAccess(NULL, $this->user)) {
        $this->messenger->addError('You do not have permission to create a new shopping list item.');
        return;
      }
      // Create all the items in the list.
      foreach($ingredient_ids as $ingredient_id) {
        $shopping_list_item = ShoppingListItem::create([
          'label' => 'Shopping list item',
          'recipes_shopping_list_id' => ['target_id' => $this->shopping_list->id()],
          'recipes_ingredient_id' => ['target_id' => $ingredient_id],
          'collected' => FALSE,
          'uid' => $this->user->id(),
        ]);
        $shopping_list_item->save();
      }
    }
  }
}
