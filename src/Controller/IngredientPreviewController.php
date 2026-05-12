<?php 
namespace Drupal\recipes\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

class IngredientPreviewController extends ControllerBase {

  public function preview($ingredient_id) {
    $ingredient = $this->entityTypeManager()
      ->getStorage('recipes_ingredient')
      ->load($ingredient_id);

    if (!$ingredient) {
      return new JsonResponse(['html' => '']);
    }
    
    $build = $this->entityTypeManager()
      ->getViewBuilder('node')
      ->view($ingredient, 'teaser');


    return new JsonResponse([
      'html' => $this->renderer()->renderPlain($build),
    ]);
  }
}