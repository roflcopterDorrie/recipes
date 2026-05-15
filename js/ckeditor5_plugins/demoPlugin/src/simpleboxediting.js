// eslint-disable-next-line
import { Plugin } from 'ckeditor5/src/core';
// eslint-disable-next-line
import { toWidget, toWidgetEditable } from 'ckeditor5/src/widget';
// eslint-disable-next-line
import { Widget } from 'ckeditor5/src/widget';
import InsertSimpleBoxCommand from './insertsimpleboxcommand';

// cSpell:ignore simplebox insertsimpleboxcommand

/**
 * CKEditor 5 plugins do not work directly with the DOM. They are defined as
 * plugin-specific data models that are then converted to markup that
 * is inserted in the DOM.
 *
 * CKEditor 5 internally interacts with simpleBox as this model:
 * <simpleBox>
 *    <simpleBoxTitle></simpleBoxTitle>
 *    <simpleBoxDescription></simpleBoxDescription>
 * </simpleBox>
 *
 * Which is converted for the browser/user as this markup
 * <section class="simple-box">
 *   <h2 class="simple-box-title"></h2>
 *   <div class="simple-box-description"></div>
 * </section>
 *
 * This file has the logic for defining the simpleBox model and for how it is
 * converted to standard DOM markup.
 */
export default class SimpleBoxEditing extends Plugin {
  static get requires() {
    return [Widget];
  }

  init() {
    this._defineSchema();
    this._defineConverters();
    this.editor.commands.add(
      'insertSimpleBox',
      new InsertSimpleBoxCommand(this.editor),
    );
  }

  /*
   * This registers the structure that will be seen by CKEditor 5 as
   * <simpleBox>
   *    <simpleBoxTitle></simpleBoxTitle>
   *    <simpleBoxDescription></simpleBoxDescription>
   * </simpleBox>
   *
   * The logic in _defineConverters() will determine how this is converted to
   * markup.
   */
  _defineSchema() {
    // Schemas are registered via the central `editor` object.
    const schema = this.editor.model.schema;

    schema.register('ingredient', {
      // Allows the ingredient to be inserted into paragraphs, headings, etc.
      allowWhere: '$text',
      allowIn: '$block',
      // This makes the entire span behave as a single uneditable unit
      isObject: true,
      allowAttributes: ['id', 'label'],
    });

  }

  /**
   * Converters determine how CKEditor 5 models are converted into markup and
   * vice versa.
   */
  _defineConverters() {
    // Converters are registered via the central editor object.
    const { conversion } = this.editor;

    // Upcast Converters: determine how existing HTML is interpreted by the
    // editor. These trigger when an editor instance loads.
    //
    // If <section class="simple-box"> is present in the existing markup
    // processed by CKEditor, then CKEditor recognizes and loads it as a
    // <simpleBox> model.
    conversion.for('upcast').elementToElement({
      view: {
        name: 'span',
        classes: 'ingredient',
        attributes: {
          'data-ingredient-id': true
        }
      },
      model: (viewElement, { writer }) => {
        const id = viewElement.getAttribute('data-ingredient-id');
        // We grab the inner text "Potatoes" to use as our label
        const label = viewElement.getChild(0).data;

        return writer.createElement('ingredient', { id, label });
      },
      // Use high priority to ensure this runs before any generic span converters
      converterPriority: 'high'
    });

    conversion.for('editingDowncast').elementToElement({
      model: 'ingredient',
      view: (modelElement, { writer }) => {
        const id = modelElement.getAttribute('id');
        const label = modelElement.getAttribute('label');

        const span = writer.createContainerElement('span', {
          'class': 'ingredient',
          'data-ingredient-id': id
        });

        // Insert the text "Potatoes" inside the span for the UI
        writer.insert(writer.createPositionAt(span, 0), writer.createText(label));

        // toWidget makes it a single selectable unit (the "badge" behavior)
        return toWidget(span, writer, { label: 'ingredient widget' });
      }
    });

    conversion.for('downcast').elementToElement({
      model: 'ingredient',
      view: (modelElement, { writer }) => {
        const id = modelElement.getAttribute('id');
        const label = modelElement.getAttribute('label');

        const span = writer.createContainerElement('span', {
          'class': 'ingredient',
          'data-ingredient-id': id
        });

        // Insert the text "Potatoes" inside the span for the UI
        writer.insert(writer.createPositionAt(span, 0), writer.createText(label));

        // toWidget makes it a single selectable unit (the "badge" behavior)
        return toWidget(span, writer, { label: 'ingredient widget' });
      }
    });
  }

}
