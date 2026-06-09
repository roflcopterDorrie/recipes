<?php

namespace Drupal\recipes\Validator;

use Drupal\Core\Extension\ModuleUninstallValidatorInterface;

class RecipesUninstallValidator implements ModuleUninstallValidatorInterface {

  public function validate($module) {
    if ($module !== 'recipes') {
      return [];
    }

    $count = \Drupal::entityTypeManager()
      ->getStorage('node')
      ->getQuery()
      ->condition('type', 'recipes_recipe')
      ->accessCheck(FALSE)
      ->count()
      ->execute();

    if ($count > 0) {
      return [
        "Cannot uninstall: there are $count Recipe nodes remaining. Delete them first.",
      ];
    }

    return [];
  }

}