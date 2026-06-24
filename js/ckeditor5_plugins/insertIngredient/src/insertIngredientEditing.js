// eslint-disable-next-line
import { Plugin } from 'ckeditor5/src/core';
// eslint-disable-next-line
import { toWidget, toWidgetEditable } from 'ckeditor5/src/widget';
// eslint-disable-next-line
import { Widget } from 'ckeditor5/src/widget';
import InsertIngredientCommand from './insertIngredientCommand';


/**
 * CKEditor 5 plugins do not work directly with the DOM. They are defined as
 * plugin-specific data models that are then converted to markup that
 * is inserted in the DOM.
 */
export default class InsertIngredientEditing extends Plugin {
  static get requires() {
    return [Widget];
  }

  init() {
    this._defineSchema();
    this._defineConverters();
    this.editor.commands.add(
      'insertIngredient',
      new InsertIngredientCommand(this.editor),
    );
  }

  _defineSchema() {
    const schema = this.editor.model.schema;

    schema.register('ingredient', {
      allowWhere: '$text',
      isInline: true, 
      isObject: true,
      allowAttributes: ['data-id'],
    });
  }

  /**
   * Converters determine how CKEditor 5 models are converted into markup and
   * vice versa.
   */
  _defineConverters() {
    const conversion = this.editor.conversion;

    // Upcast 
    conversion.for('upcast').elementToElement({
      view: {
        name: 'ingredient',
        attributes: {
          'data-id': true
        }
      },
      model: (viewElement, { writer }) => {
        return writer.createElement('ingredient', {
          'data-id': viewElement.getAttribute('data-id')
        });
      }
    });

    // Output for saving in the database.
    conversion.for('dataDowncast').elementToElement({
      model: 'ingredient',
      view: (modelElement, { writer }) => {
        return writer.createContainerElement('ingredient', {
          'data-id': modelElement.getAttribute('data-id')
        });
      }
    });

    // Editor UI View.
    conversion.for('editingDowncast').elementToElement({
      model: 'ingredient',
      view: (modelElement, { writer }) => {
        const ingredientId = modelElement.getAttribute('data-id');
        const ingredientData = this.getIngredientById(ingredientId);

        // Outer wrapper.
        const widgetWrapper = writer.createContainerElement('span', {
            class: 'ingredient',
            'data-id': modelElement.getAttribute('data-id')
        });

        // Inner label.
        const innerLabel = writer.createUIElement('span', null, function(domDocument) {
            const domElement = this.toDomElement(domDocument);
            domElement.innerText = ingredientData.name;
            domElement.setAttribute('contenteditable', 'false');
            return domElement;
        });
        writer.insert(writer.createPositionAt(widgetWrapper, 0), innerLabel);

        // Return the widget.
        return toWidget(widgetWrapper, writer, { label: 'ingredient widget' });
      }
    });

  }

  getIngredientById(id) {
    const availableIngredients = drupalSettings.recipes.ingredients;
    const found = availableIngredients.find(ingredient => Number(id) === Number(ingredient.id));
    return found || null;
  }



}