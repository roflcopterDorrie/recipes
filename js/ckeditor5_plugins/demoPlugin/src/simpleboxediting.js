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
      // Behaves like a self-contained object (e.g., an image).
      isObject: true,
      // Allow in places where other blocks are allowed (e.g., directly in the root).
      allowWhere: '$block',
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
      model: 'ingredient',
      view: {
        name: 'span',
        classes: 'ingredient' 
      },
    });


    // Data Downcast Converters: converts stored model data into HTML.
    // These trigger when content is saved.
    //
    // Instances of <simpleBox> are saved as
    // <section class="simple-box">{{inner content}}</section>.
    conversion.for('dataDowncast').elementToElement({
      model: 'ingredient',
      view: {
        name: 'span',
        classes: 'ingredient',
      },
    });


    // Editing Downcast Converters. These render the content to the user for
    // editing, i.e., this determines what gets seen in the editor. These trigger
    // after the Data Upcast Converters and are re-triggered any time there
    // are changes to any of the models' properties.
    //
    // Convert the <simpleBox> model into a container widget in the editor UI.
    conversion.for('editingDowncast').elementToElement({
      model: 'ingredient',
      view: (modelElement, { writer: viewWriter }) => {
        const section = viewWriter.createContainerElement('span', {
          class: 'ingredient',
        });

        return toWidget(section, viewWriter, { label: 'simple box widget' });
      },
    });

  }
}
