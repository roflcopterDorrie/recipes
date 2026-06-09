/**
 * @file This is what CKEditor refers to as a master (glue) plugin. Its role is
 * just to load the “editing” and “UI” components of this Plugin. Those
 * components could be included in this file, but
 *
 * I.e, this file's purpose is to integrate all the separate parts of the plugin
 * before it's made discoverable via index.js.
 */
// cSpell:ignore simpleboxediting simpleboxui

// The contents of SimpleBoxUI and SimpleBox editing could be included in this
// file, but it is recommended to separate these concerns in different files.
// eslint-disable-next-line
import InsertIngredientEditing from './insertIngredientEditing';
import InsertIngredientUI from './insertIngredientUi';
// eslint-disable-next-line
import { Plugin } from 'ckeditor5/src/core';

export default class InsertIngredient extends Plugin {
  static get requires() {
    return [InsertIngredientEditing, InsertIngredientUI];
  }
}

