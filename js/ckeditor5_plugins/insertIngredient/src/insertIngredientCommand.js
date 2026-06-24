/**
 * @file defines InsertSimpleBoxCommand, which is executed when the simpleBox
 * toolbar button is pressed.
 */
// cSpell:ignore simpleboxediting
// eslint-disable-next-line
import { Command } from 'ckeditor5/src/core';

export default class InsertIngredientCommand extends Command {
  execute(options = {}) {

    const { dataId } = options;
    const { model } = this.editor;

    model.change((writer) => {
      // 1. Create the parent wrapper element
      const ingredientElement = writer.createElement('ingredient', { "data-id": dataId });

      // 5. Insert the complete structure into the document
      model.insertContent(ingredientElement);
    });
  }

  refresh() {
    const { model } = this.editor;
    const { selection } = model.document;

    // Determine if the cursor (selection) is in a position where adding a
    // simpleBox is permitted. This is based on the schema of the model(s)
    // currently containing the cursor.
    const allowedIn = model.schema.findAllowedParent(
      selection.getFirstPosition(),
      'ingredient',
    );

    // If the cursor is not in a location where a simpleBox can be added, return
    // null so the addition doesn't happen.
    this.isEnabled = allowedIn !== null;
  }
}

