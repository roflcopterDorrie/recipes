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
      allowIn: '$block',
      isObject: true,
      allowAttributes: ['id'],
    });

    // Inner amount span - Un-commented and changed content rule to match inline elements
    schema.register('ingredientAmount', {
      allowIn: 'ingredient',
      allowContentOf: '$block'
    });

    // Inner name span - Un-commented and changed content rule to match inline elements
    schema.register('ingredientName', {
      allowIn: 'ingredient',
      allowContentOf: '$block'
    });

    // Inner extra details span - Un-commented and changed content rule to match inline elements
    schema.register('ingredientExtra', {
      allowIn: 'ingredient',
      allowContentOf: '$block'
    });
  }

  /**
   * Converters determine how CKEditor 5 models are converted into markup and
   * vice versa.
   */
  _defineConverters() {
    const conversion = this.editor.conversion;

    // --- 1. Main Ingredient Wrapper ---
    conversion.for('upcast').elementToElement({
      view: { name: 'span', classes: 'ingredient' },
      model: (viewElement, { writer }) => {
        return writer.createElement('ingredient', {
          id: viewElement.getAttribute('data-ingredient-id')
        });
      }
    });

    // DB Output: Clean HTML
    conversion.for('dataDowncast').elementToElement({
      model: 'ingredient',
      view: (modelElement, { writer }) => {
        return writer.createContainerElement('span', {
          class: 'ingredient',
          'data-ingredient-id': modelElement.getAttribute('id')
        });
      }
    });

    // Editor UI View: FIXED to use toWidget instead of toWidgetEditable
    conversion.for('editingDowncast').elementToElement({
      model: 'ingredient',
      view: (modelElement, { writer }) => {
        const span = writer.createContainerElement('span', {
          class: 'ingredient',
          'data-ingredient-id': modelElement.getAttribute('id')
        });
        return toWidget(span, writer, { label: 'ingredient widget' });
      }
    });

    // --- 2. Children (Updated for Editing UI mapping) ---
    this._registerChildConverter('ingredientAmount', 'ingredient__amount');
    this._registerChildConverter('ingredientName', 'ingredient__name');
    this._registerChildConverter('ingredientExtra', 'ingredient__extra');
  }

  _registerChildConverter(modelName, className) {
    const conversion = this.editor.conversion;

    conversion.for('upcast').elementToElement({
      view: { name: 'span', classes: className },
      model: (viewElement, { writer }) => {
        return writer.createElement(modelName);
      }
    });

    // 2. Downcast: Dynamically swap element types based on text presence
    conversion.for('downcast').elementToElement({
      model: modelName,
      view: (modelElement, { writer }) => {
        // If the model node has no text inside it, render an EmptyElement
        if (modelElement.childCount === 0) {
          return writer.createEmptyElement('span', {
            class: `${className} is-empty`
          });
        }

        // If it has text, render a standard container element
        return writer.createContainerElement('span', { class: className });
      }
    });
  }



}