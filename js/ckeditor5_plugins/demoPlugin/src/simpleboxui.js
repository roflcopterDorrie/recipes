/**
 * @file registers the simpleBox toolbar button and binds functionality to it.
 */
// eslint-disable-next-line
import { Plugin } from 'ckeditor5/src/core';
// eslint-disable-next-line
import { ButtonView } from 'ckeditor5/src/ui';
const { createDropdown, addListToDropdown, Model } = CKEditor5.ui;
const { Collection } = CKEditor5.utils;
import icon from '../../../../icons/simpleBox.svg';

export default class SimpleBoxUI extends Plugin {
  init() {
    const editor = this.editor;

    editor.ui.componentFactory.add('simpleBox', locale => {
      const command = editor.commands.get('insertSimpleBox');

    const dropdownView = createDropdown( locale );

    // 1. Setup the button look
    dropdownView.buttonView.set( {
        label: 'Insert Ingredient',
        withText: true,
        tooltip: true
    } );

    // 2. Prepare the list items from your JS variable
    const items = new Collection();
    
    availableIngredients.forEach( ingredient => {
        items.add( {
            type: 'button',
            model: new Model( {
                withText: true,
                label: ingredient.name,
                id: ingredient.id // Store our data here
            } )
        } );
    } );

    // 3. Add the list to the dropdown
    addListToDropdown( dropdownView, items );

    // 4. Listen to the execution
    this.listenTo( dropdownView, 'execute', eventInfo => {
        const { id, label } = eventInfo.source;
        editor.execute( 'insertIngredient', { id, label: label } );
        editor.editing.view.focus();
    } );

    return dropdownView;
  });

    // This will register the simpleBox toolbar button.
    /*editor.ui.componentFactory.add('simpleBox', (locale) => {
      const command = editor.commands.get('insertSimpleBox');
      const buttonView = new ButtonView(locale);

      // Create the toolbar button.
      buttonView.set({
        label: editor.t('Simple Box'),
        icon,
        tooltip: true,
      });

      // Bind the state of the button to the command.
      buttonView.bind('isOn', 'isEnabled').to(command, 'value', 'isEnabled');

      // Execute the command when the button is clicked (executed).
      this.listenTo(buttonView, 'execute', () =>
        editor.execute('insertSimpleBox'),
      );

      return buttonView;
    });*/
  }
}
