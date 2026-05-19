/**
 * @file registers the simpleBox toolbar button and binds functionality to it.
 */
// eslint-disable-next-line
import { Plugin } from 'ckeditor5/src/core';
// eslint-disable-next-line
import { ButtonView } from 'ckeditor5/src/ui';
const { createDropdown, addListToDropdown, UIModel } = CKEditor5.ui;
const { Collection } = CKEditor5.utils;
import icon from '../../../../icons/simpleBox.svg';

export default class InsertIngredientUI extends Plugin {
  init() {
    const editor = this.editor;

    editor.ui.componentFactory.add('insertIngredient', locale => {

      const dropdownView = createDropdown(locale);

      // 1. Setup the button look
      dropdownView.buttonView.set({
        label: 'Insert Ingredient',
        withText: true,
        tooltip: true
      });

      // 2. Prepare the list items from your JS variable
      const items = new Collection();

      const availableIngredients = drupalSettings.recipes.ingredients;

      availableIngredients.forEach(ingredient => {
        console.log(ingredient);
        items.add({
          type: 'button',
          model: new UIModel({
            withText: true,
            label: ingredient.label,
            id: ingredient.id // Store our data here
          })
        });
      });

      // 3. Add the list to the dropdown
      addListToDropdown(dropdownView, items);

      // 4. Listen to the execution
      this.listenTo(dropdownView, 'execute', eventInfo => {
        const { id, label } = eventInfo.source;
        editor.execute('insertIngredient', { id, label: label });
        editor.editing.view.focus();
      });

      return dropdownView;
    });

    
  }
}
