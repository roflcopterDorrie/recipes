/*! cspell:disable */
/*
 * ATTENTION: An "eval-source-map" devtool has been used.
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file with attached SourceMaps in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
(function webpackUniversalModuleDefinition(root, factory) {
	if(typeof exports === 'object' && typeof module === 'object')
		module.exports = factory();
	else if(typeof define === 'function' && define.amd)
		define([], factory);
	else if(typeof exports === 'object')
		exports["CKEditor5"] = factory();
	else
		root["CKEditor5"] = root["CKEditor5"] || {}, root["CKEditor5"]["insertIngredient"] = factory();
})(self, () => {
return /******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./js/ckeditor5_plugins/insertIngredient/src/index.js"
/*!************************************************************!*\
  !*** ./js/ckeditor5_plugins/insertIngredient/src/index.js ***!
  \************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("{__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"default\": () => (__WEBPACK_DEFAULT_EXPORT__)\n/* harmony export */ });\n/* harmony import */ var _insertIngredient__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./insertIngredient */ \"./js/ckeditor5_plugins/insertIngredient/src/insertIngredient.js\");\n/**\n * @file The build process always expects an index.js file. Anything exported\n * here will be recognized by CKEditor 5 as an available plugin. Multiple\n * plugins can be exported in this one file.\n *\n * I.e. this file's purpose is to make plugin(s) discoverable.\n */\n// cSpell:ignore \n\n\n\n/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({\n  InsertIngredient: _insertIngredient__WEBPACK_IMPORTED_MODULE_0__[\"default\"],\n});\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9qcy9ja2VkaXRvcjVfcGx1Z2lucy9pbnNlcnRJbmdyZWRpZW50L3NyYy9pbmRleC5qcyIsIm1hcHBpbmdzIjoiOzs7OztBQUFBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly9DS0VkaXRvcjUuaW5zZXJ0SW5ncmVkaWVudC8uL2pzL2NrZWRpdG9yNV9wbHVnaW5zL2luc2VydEluZ3JlZGllbnQvc3JjL2luZGV4LmpzP2EwYjkiXSwic291cmNlc0NvbnRlbnQiOlsiLyoqXG4gKiBAZmlsZSBUaGUgYnVpbGQgcHJvY2VzcyBhbHdheXMgZXhwZWN0cyBhbiBpbmRleC5qcyBmaWxlLiBBbnl0aGluZyBleHBvcnRlZFxuICogaGVyZSB3aWxsIGJlIHJlY29nbml6ZWQgYnkgQ0tFZGl0b3IgNSBhcyBhbiBhdmFpbGFibGUgcGx1Z2luLiBNdWx0aXBsZVxuICogcGx1Z2lucyBjYW4gYmUgZXhwb3J0ZWQgaW4gdGhpcyBvbmUgZmlsZS5cbiAqXG4gKiBJLmUuIHRoaXMgZmlsZSdzIHB1cnBvc2UgaXMgdG8gbWFrZSBwbHVnaW4ocykgZGlzY292ZXJhYmxlLlxuICovXG4vLyBjU3BlbGw6aWdub3JlIFxuXG5pbXBvcnQgSW5zZXJ0SW5ncmVkaWVudCBmcm9tICcuL2luc2VydEluZ3JlZGllbnQnO1xuXG5leHBvcnQgZGVmYXVsdCB7XG4gIEluc2VydEluZ3JlZGllbnQsXG59O1xuIl0sIm5hbWVzIjpbXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./js/ckeditor5_plugins/insertIngredient/src/index.js\n\n}");

/***/ },

/***/ "./js/ckeditor5_plugins/insertIngredient/src/insertIngredient.js"
/*!***********************************************************************!*\
  !*** ./js/ckeditor5_plugins/insertIngredient/src/insertIngredient.js ***!
  \***********************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("{__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"default\": () => (/* binding */ InsertIngredient)\n/* harmony export */ });\n/* harmony import */ var _insertIngredientEditing__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./insertIngredientEditing */ \"./js/ckeditor5_plugins/insertIngredient/src/insertIngredientEditing.js\");\n/* harmony import */ var _insertIngredientUi__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./insertIngredientUi */ \"./js/ckeditor5_plugins/insertIngredient/src/insertIngredientUi.js\");\n/* harmony import */ var ckeditor5_src_core__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ckeditor5/src/core */ \"ckeditor5/src/core.js\");\n/**\n * @file This is what CKEditor refers to as a master (glue) plugin. Its role is\n * just to load the “editing” and “UI” components of this Plugin. Those\n * components could be included in this file, but\n *\n * I.e, this file's purpose is to integrate all the separate parts of the plugin\n * before it's made discoverable via index.js.\n */\n// cSpell:ignore simpleboxediting simpleboxui\n\n// The contents of SimpleBoxUI and SimpleBox editing could be included in this\n// file, but it is recommended to separate these concerns in different files.\n// eslint-disable-next-line\n\n\n// eslint-disable-next-line\n\n\nclass InsertIngredient extends ckeditor5_src_core__WEBPACK_IMPORTED_MODULE_2__.Plugin {\n  static get requires() {\n    return [_insertIngredientEditing__WEBPACK_IMPORTED_MODULE_0__[\"default\"], _insertIngredientUi__WEBPACK_IMPORTED_MODULE_1__[\"default\"]];\n  }\n}\n\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9qcy9ja2VkaXRvcjVfcGx1Z2lucy9pbnNlcnRJbmdyZWRpZW50L3NyYy9pbnNlcnRJbmdyZWRpZW50LmpzIiwibWFwcGluZ3MiOiI7Ozs7Ozs7QUFBQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly9DS0VkaXRvcjUuaW5zZXJ0SW5ncmVkaWVudC8uL2pzL2NrZWRpdG9yNV9wbHVnaW5zL2luc2VydEluZ3JlZGllbnQvc3JjL2luc2VydEluZ3JlZGllbnQuanM/NWZhNSJdLCJzb3VyY2VzQ29udGVudCI6WyIvKipcbiAqIEBmaWxlIFRoaXMgaXMgd2hhdCBDS0VkaXRvciByZWZlcnMgdG8gYXMgYSBtYXN0ZXIgKGdsdWUpIHBsdWdpbi4gSXRzIHJvbGUgaXNcbiAqIGp1c3QgdG8gbG9hZCB0aGUg4oCcZWRpdGluZ+KAnSBhbmQg4oCcVUnigJ0gY29tcG9uZW50cyBvZiB0aGlzIFBsdWdpbi4gVGhvc2VcbiAqIGNvbXBvbmVudHMgY291bGQgYmUgaW5jbHVkZWQgaW4gdGhpcyBmaWxlLCBidXRcbiAqXG4gKiBJLmUsIHRoaXMgZmlsZSdzIHB1cnBvc2UgaXMgdG8gaW50ZWdyYXRlIGFsbCB0aGUgc2VwYXJhdGUgcGFydHMgb2YgdGhlIHBsdWdpblxuICogYmVmb3JlIGl0J3MgbWFkZSBkaXNjb3ZlcmFibGUgdmlhIGluZGV4LmpzLlxuICovXG4vLyBjU3BlbGw6aWdub3JlIHNpbXBsZWJveGVkaXRpbmcgc2ltcGxlYm94dWlcblxuLy8gVGhlIGNvbnRlbnRzIG9mIFNpbXBsZUJveFVJIGFuZCBTaW1wbGVCb3ggZWRpdGluZyBjb3VsZCBiZSBpbmNsdWRlZCBpbiB0aGlzXG4vLyBmaWxlLCBidXQgaXQgaXMgcmVjb21tZW5kZWQgdG8gc2VwYXJhdGUgdGhlc2UgY29uY2VybnMgaW4gZGlmZmVyZW50IGZpbGVzLlxuLy8gZXNsaW50LWRpc2FibGUtbmV4dC1saW5lXG5pbXBvcnQgSW5zZXJ0SW5ncmVkaWVudEVkaXRpbmcgZnJvbSAnLi9pbnNlcnRJbmdyZWRpZW50RWRpdGluZyc7XG5pbXBvcnQgSW5zZXJ0SW5ncmVkaWVudFVJIGZyb20gJy4vaW5zZXJ0SW5ncmVkaWVudFVpJztcbi8vIGVzbGludC1kaXNhYmxlLW5leHQtbGluZVxuaW1wb3J0IHsgUGx1Z2luIH0gZnJvbSAnY2tlZGl0b3I1L3NyYy9jb3JlJztcblxuZXhwb3J0IGRlZmF1bHQgY2xhc3MgSW5zZXJ0SW5ncmVkaWVudCBleHRlbmRzIFBsdWdpbiB7XG4gIHN0YXRpYyBnZXQgcmVxdWlyZXMoKSB7XG4gICAgcmV0dXJuIFtJbnNlcnRJbmdyZWRpZW50RWRpdGluZywgSW5zZXJ0SW5ncmVkaWVudFVJXTtcbiAgfVxufVxuXG4iXSwibmFtZXMiOltdLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///./js/ckeditor5_plugins/insertIngredient/src/insertIngredient.js\n\n}");

/***/ },

/***/ "./js/ckeditor5_plugins/insertIngredient/src/insertIngredientCommand.js"
/*!******************************************************************************!*\
  !*** ./js/ckeditor5_plugins/insertIngredient/src/insertIngredientCommand.js ***!
  \******************************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("{__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"default\": () => (/* binding */ InsertIngredientCommand)\n/* harmony export */ });\n/* harmony import */ var ckeditor5_src_core__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ckeditor5/src/core */ \"ckeditor5/src/core.js\");\n/**\n * @file defines InsertSimpleBoxCommand, which is executed when the simpleBox\n * toolbar button is pressed.\n */\n// cSpell:ignore simpleboxediting\n// eslint-disable-next-line\n\n\nclass InsertIngredientCommand extends ckeditor5_src_core__WEBPACK_IMPORTED_MODULE_0__.Command {\n  execute(options = {}) {\n\n    const { dataId } = options;\n    const { model } = this.editor;\n\n    model.change((writer) => {\n      // 1. Create the parent wrapper element\n      const ingredientElement = writer.createElement('ingredient', { \"data-id\": dataId });\n\n      // 5. Insert the complete structure into the document\n      model.insertContent(ingredientElement);\n    });\n  }\n\n  refresh() {\n    const { model } = this.editor;\n    const { selection } = model.document;\n\n    // Determine if the cursor (selection) is in a position where adding a\n    // simpleBox is permitted. This is based on the schema of the model(s)\n    // currently containing the cursor.\n    const allowedIn = model.schema.findAllowedParent(\n      selection.getFirstPosition(),\n      'ingredient',\n    );\n\n    // If the cursor is not in a location where a simpleBox can be added, return\n    // null so the addition doesn't happen.\n    this.isEnabled = allowedIn !== null;\n  }\n}\n\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9qcy9ja2VkaXRvcjVfcGx1Z2lucy9pbnNlcnRJbmdyZWRpZW50L3NyYy9pbnNlcnRJbmdyZWRpZW50Q29tbWFuZC5qcyIsIm1hcHBpbmdzIjoiOzs7OztBQUFBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly9DS0VkaXRvcjUuaW5zZXJ0SW5ncmVkaWVudC8uL2pzL2NrZWRpdG9yNV9wbHVnaW5zL2luc2VydEluZ3JlZGllbnQvc3JjL2luc2VydEluZ3JlZGllbnRDb21tYW5kLmpzPzRjYjEiXSwic291cmNlc0NvbnRlbnQiOlsiLyoqXG4gKiBAZmlsZSBkZWZpbmVzIEluc2VydFNpbXBsZUJveENvbW1hbmQsIHdoaWNoIGlzIGV4ZWN1dGVkIHdoZW4gdGhlIHNpbXBsZUJveFxuICogdG9vbGJhciBidXR0b24gaXMgcHJlc3NlZC5cbiAqL1xuLy8gY1NwZWxsOmlnbm9yZSBzaW1wbGVib3hlZGl0aW5nXG4vLyBlc2xpbnQtZGlzYWJsZS1uZXh0LWxpbmVcbmltcG9ydCB7IENvbW1hbmQgfSBmcm9tICdja2VkaXRvcjUvc3JjL2NvcmUnO1xuXG5leHBvcnQgZGVmYXVsdCBjbGFzcyBJbnNlcnRJbmdyZWRpZW50Q29tbWFuZCBleHRlbmRzIENvbW1hbmQge1xuICBleGVjdXRlKG9wdGlvbnMgPSB7fSkge1xuXG4gICAgY29uc3QgeyBkYXRhSWQgfSA9IG9wdGlvbnM7XG4gICAgY29uc3QgeyBtb2RlbCB9ID0gdGhpcy5lZGl0b3I7XG5cbiAgICBtb2RlbC5jaGFuZ2UoKHdyaXRlcikgPT4ge1xuICAgICAgLy8gMS4gQ3JlYXRlIHRoZSBwYXJlbnQgd3JhcHBlciBlbGVtZW50XG4gICAgICBjb25zdCBpbmdyZWRpZW50RWxlbWVudCA9IHdyaXRlci5jcmVhdGVFbGVtZW50KCdpbmdyZWRpZW50JywgeyBcImRhdGEtaWRcIjogZGF0YUlkIH0pO1xuXG4gICAgICAvLyA1LiBJbnNlcnQgdGhlIGNvbXBsZXRlIHN0cnVjdHVyZSBpbnRvIHRoZSBkb2N1bWVudFxuICAgICAgbW9kZWwuaW5zZXJ0Q29udGVudChpbmdyZWRpZW50RWxlbWVudCk7XG4gICAgfSk7XG4gIH1cblxuICByZWZyZXNoKCkge1xuICAgIGNvbnN0IHsgbW9kZWwgfSA9IHRoaXMuZWRpdG9yO1xuICAgIGNvbnN0IHsgc2VsZWN0aW9uIH0gPSBtb2RlbC5kb2N1bWVudDtcblxuICAgIC8vIERldGVybWluZSBpZiB0aGUgY3Vyc29yIChzZWxlY3Rpb24pIGlzIGluIGEgcG9zaXRpb24gd2hlcmUgYWRkaW5nIGFcbiAgICAvLyBzaW1wbGVCb3ggaXMgcGVybWl0dGVkLiBUaGlzIGlzIGJhc2VkIG9uIHRoZSBzY2hlbWEgb2YgdGhlIG1vZGVsKHMpXG4gICAgLy8gY3VycmVudGx5IGNvbnRhaW5pbmcgdGhlIGN1cnNvci5cbiAgICBjb25zdCBhbGxvd2VkSW4gPSBtb2RlbC5zY2hlbWEuZmluZEFsbG93ZWRQYXJlbnQoXG4gICAgICBzZWxlY3Rpb24uZ2V0Rmlyc3RQb3NpdGlvbigpLFxuICAgICAgJ2luZ3JlZGllbnQnLFxuICAgICk7XG5cbiAgICAvLyBJZiB0aGUgY3Vyc29yIGlzIG5vdCBpbiBhIGxvY2F0aW9uIHdoZXJlIGEgc2ltcGxlQm94IGNhbiBiZSBhZGRlZCwgcmV0dXJuXG4gICAgLy8gbnVsbCBzbyB0aGUgYWRkaXRpb24gZG9lc24ndCBoYXBwZW4uXG4gICAgdGhpcy5pc0VuYWJsZWQgPSBhbGxvd2VkSW4gIT09IG51bGw7XG4gIH1cbn1cblxuIl0sIm5hbWVzIjpbXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./js/ckeditor5_plugins/insertIngredient/src/insertIngredientCommand.js\n\n}");

/***/ },

/***/ "./js/ckeditor5_plugins/insertIngredient/src/insertIngredientEditing.js"
/*!******************************************************************************!*\
  !*** ./js/ckeditor5_plugins/insertIngredient/src/insertIngredientEditing.js ***!
  \******************************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("{__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"default\": () => (/* binding */ InsertIngredientEditing)\n/* harmony export */ });\n/* harmony import */ var ckeditor5_src_core__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ckeditor5/src/core */ \"ckeditor5/src/core.js\");\n/* harmony import */ var ckeditor5_src_widget__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ckeditor5/src/widget */ \"ckeditor5/src/widget.js\");\n/* harmony import */ var _insertIngredientCommand__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./insertIngredientCommand */ \"./js/ckeditor5_plugins/insertIngredient/src/insertIngredientCommand.js\");\n// eslint-disable-next-line\n\n// eslint-disable-next-line\n\n// eslint-disable-next-line\n\n\n\n\n/**\n * CKEditor 5 plugins do not work directly with the DOM. They are defined as\n * plugin-specific data models that are then converted to markup that\n * is inserted in the DOM.\n */\nclass InsertIngredientEditing extends ckeditor5_src_core__WEBPACK_IMPORTED_MODULE_0__.Plugin {\n  static get requires() {\n    return [ckeditor5_src_widget__WEBPACK_IMPORTED_MODULE_1__.Widget];\n  }\n\n  init() {\n    this._defineSchema();\n    this._defineConverters();\n    this.editor.commands.add(\n      'insertIngredient',\n      new _insertIngredientCommand__WEBPACK_IMPORTED_MODULE_2__[\"default\"](this.editor),\n    );\n  }\n\n  _defineSchema() {\n    const schema = this.editor.model.schema;\n\n    schema.register('ingredient', {\n      allowWhere: '$text',\n      isInline: true, \n      isObject: true,\n      allowAttributes: ['data-id'],\n    });\n  }\n\n  /**\n   * Converters determine how CKEditor 5 models are converted into markup and\n   * vice versa.\n   */\n  _defineConverters() {\n    const conversion = this.editor.conversion;\n\n    // Upcast \n    conversion.for('upcast').elementToElement({\n      view: {\n        name: 'ingredient',\n        attributes: {\n          'data-id': true\n        }\n      },\n      model: (viewElement, { writer }) => {\n        return writer.createElement('ingredient', {\n          'data-id': viewElement.getAttribute('data-id')\n        });\n      }\n    });\n\n    // Output for saving in the database.\n    conversion.for('dataDowncast').elementToElement({\n      model: 'ingredient',\n      view: (modelElement, { writer }) => {\n        return writer.createContainerElement('ingredient', {\n          'data-id': modelElement.getAttribute('data-id')\n        });\n      }\n    });\n\n    // Editor UI View.\n    conversion.for('editingDowncast').elementToElement({\n      model: 'ingredient',\n      view: (modelElement, { writer }) => {\n        const ingredientId = modelElement.getAttribute('data-id');\n        const ingredientData = this.getIngredientById(ingredientId);\n\n        // Outer wrapper.\n        const widgetWrapper = writer.createContainerElement('span', {\n            class: 'ingredient',\n            'data-id': modelElement.getAttribute('data-id')\n        });\n\n        // Inner label.\n        const innerLabel = writer.createUIElement('span', null, function(domDocument) {\n            const domElement = this.toDomElement(domDocument);\n            domElement.innerText = ingredientData.name;\n            domElement.setAttribute('contenteditable', 'false');\n            return domElement;\n        });\n        writer.insert(writer.createPositionAt(widgetWrapper, 0), innerLabel);\n\n        // Return the widget.\n        return (0,ckeditor5_src_widget__WEBPACK_IMPORTED_MODULE_1__.toWidget)(widgetWrapper, writer, { label: 'ingredient widget' });\n      }\n    });\n\n  }\n\n  getIngredientById(id) {\n    const availableIngredients = drupalSettings.recipes.ingredients;\n    const found = availableIngredients.find(ingredient => Number(id) === Number(ingredient.id));\n    return found || null;\n  }\n\n\n\n}//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9qcy9ja2VkaXRvcjVfcGx1Z2lucy9pbnNlcnRJbmdyZWRpZW50L3NyYy9pbnNlcnRJbmdyZWRpZW50RWRpdGluZy5qcyIsIm1hcHBpbmdzIjoiOzs7Ozs7O0FBQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly9DS0VkaXRvcjUuaW5zZXJ0SW5ncmVkaWVudC8uL2pzL2NrZWRpdG9yNV9wbHVnaW5zL2luc2VydEluZ3JlZGllbnQvc3JjL2luc2VydEluZ3JlZGllbnRFZGl0aW5nLmpzPzgyYzUiXSwic291cmNlc0NvbnRlbnQiOlsiLy8gZXNsaW50LWRpc2FibGUtbmV4dC1saW5lXG5pbXBvcnQgeyBQbHVnaW4gfSBmcm9tICdja2VkaXRvcjUvc3JjL2NvcmUnO1xuLy8gZXNsaW50LWRpc2FibGUtbmV4dC1saW5lXG5pbXBvcnQgeyB0b1dpZGdldCwgdG9XaWRnZXRFZGl0YWJsZSB9IGZyb20gJ2NrZWRpdG9yNS9zcmMvd2lkZ2V0Jztcbi8vIGVzbGludC1kaXNhYmxlLW5leHQtbGluZVxuaW1wb3J0IHsgV2lkZ2V0IH0gZnJvbSAnY2tlZGl0b3I1L3NyYy93aWRnZXQnO1xuaW1wb3J0IEluc2VydEluZ3JlZGllbnRDb21tYW5kIGZyb20gJy4vaW5zZXJ0SW5ncmVkaWVudENvbW1hbmQnO1xuXG5cbi8qKlxuICogQ0tFZGl0b3IgNSBwbHVnaW5zIGRvIG5vdCB3b3JrIGRpcmVjdGx5IHdpdGggdGhlIERPTS4gVGhleSBhcmUgZGVmaW5lZCBhc1xuICogcGx1Z2luLXNwZWNpZmljIGRhdGEgbW9kZWxzIHRoYXQgYXJlIHRoZW4gY29udmVydGVkIHRvIG1hcmt1cCB0aGF0XG4gKiBpcyBpbnNlcnRlZCBpbiB0aGUgRE9NLlxuICovXG5leHBvcnQgZGVmYXVsdCBjbGFzcyBJbnNlcnRJbmdyZWRpZW50RWRpdGluZyBleHRlbmRzIFBsdWdpbiB7XG4gIHN0YXRpYyBnZXQgcmVxdWlyZXMoKSB7XG4gICAgcmV0dXJuIFtXaWRnZXRdO1xuICB9XG5cbiAgaW5pdCgpIHtcbiAgICB0aGlzLl9kZWZpbmVTY2hlbWEoKTtcbiAgICB0aGlzLl9kZWZpbmVDb252ZXJ0ZXJzKCk7XG4gICAgdGhpcy5lZGl0b3IuY29tbWFuZHMuYWRkKFxuICAgICAgJ2luc2VydEluZ3JlZGllbnQnLFxuICAgICAgbmV3IEluc2VydEluZ3JlZGllbnRDb21tYW5kKHRoaXMuZWRpdG9yKSxcbiAgICApO1xuICB9XG5cbiAgX2RlZmluZVNjaGVtYSgpIHtcbiAgICBjb25zdCBzY2hlbWEgPSB0aGlzLmVkaXRvci5tb2RlbC5zY2hlbWE7XG5cbiAgICBzY2hlbWEucmVnaXN0ZXIoJ2luZ3JlZGllbnQnLCB7XG4gICAgICBhbGxvd1doZXJlOiAnJHRleHQnLFxuICAgICAgaXNJbmxpbmU6IHRydWUsIFxuICAgICAgaXNPYmplY3Q6IHRydWUsXG4gICAgICBhbGxvd0F0dHJpYnV0ZXM6IFsnZGF0YS1pZCddLFxuICAgIH0pO1xuICB9XG5cbiAgLyoqXG4gICAqIENvbnZlcnRlcnMgZGV0ZXJtaW5lIGhvdyBDS0VkaXRvciA1IG1vZGVscyBhcmUgY29udmVydGVkIGludG8gbWFya3VwIGFuZFxuICAgKiB2aWNlIHZlcnNhLlxuICAgKi9cbiAgX2RlZmluZUNvbnZlcnRlcnMoKSB7XG4gICAgY29uc3QgY29udmVyc2lvbiA9IHRoaXMuZWRpdG9yLmNvbnZlcnNpb247XG5cbiAgICAvLyBVcGNhc3QgXG4gICAgY29udmVyc2lvbi5mb3IoJ3VwY2FzdCcpLmVsZW1lbnRUb0VsZW1lbnQoe1xuICAgICAgdmlldzoge1xuICAgICAgICBuYW1lOiAnaW5ncmVkaWVudCcsXG4gICAgICAgIGF0dHJpYnV0ZXM6IHtcbiAgICAgICAgICAnZGF0YS1pZCc6IHRydWVcbiAgICAgICAgfVxuICAgICAgfSxcbiAgICAgIG1vZGVsOiAodmlld0VsZW1lbnQsIHsgd3JpdGVyIH0pID0+IHtcbiAgICAgICAgcmV0dXJuIHdyaXRlci5jcmVhdGVFbGVtZW50KCdpbmdyZWRpZW50Jywge1xuICAgICAgICAgICdkYXRhLWlkJzogdmlld0VsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLWlkJylcbiAgICAgICAgfSk7XG4gICAgICB9XG4gICAgfSk7XG5cbiAgICAvLyBPdXRwdXQgZm9yIHNhdmluZyBpbiB0aGUgZGF0YWJhc2UuXG4gICAgY29udmVyc2lvbi5mb3IoJ2RhdGFEb3duY2FzdCcpLmVsZW1lbnRUb0VsZW1lbnQoe1xuICAgICAgbW9kZWw6ICdpbmdyZWRpZW50JyxcbiAgICAgIHZpZXc6IChtb2RlbEVsZW1lbnQsIHsgd3JpdGVyIH0pID0+IHtcbiAgICAgICAgcmV0dXJuIHdyaXRlci5jcmVhdGVDb250YWluZXJFbGVtZW50KCdpbmdyZWRpZW50Jywge1xuICAgICAgICAgICdkYXRhLWlkJzogbW9kZWxFbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS1pZCcpXG4gICAgICAgIH0pO1xuICAgICAgfVxuICAgIH0pO1xuXG4gICAgLy8gRWRpdG9yIFVJIFZpZXcuXG4gICAgY29udmVyc2lvbi5mb3IoJ2VkaXRpbmdEb3duY2FzdCcpLmVsZW1lbnRUb0VsZW1lbnQoe1xuICAgICAgbW9kZWw6ICdpbmdyZWRpZW50JyxcbiAgICAgIHZpZXc6IChtb2RlbEVsZW1lbnQsIHsgd3JpdGVyIH0pID0+IHtcbiAgICAgICAgY29uc3QgaW5ncmVkaWVudElkID0gbW9kZWxFbGVtZW50LmdldEF0dHJpYnV0ZSgnZGF0YS1pZCcpO1xuICAgICAgICBjb25zdCBpbmdyZWRpZW50RGF0YSA9IHRoaXMuZ2V0SW5ncmVkaWVudEJ5SWQoaW5ncmVkaWVudElkKTtcblxuICAgICAgICAvLyBPdXRlciB3cmFwcGVyLlxuICAgICAgICBjb25zdCB3aWRnZXRXcmFwcGVyID0gd3JpdGVyLmNyZWF0ZUNvbnRhaW5lckVsZW1lbnQoJ3NwYW4nLCB7XG4gICAgICAgICAgICBjbGFzczogJ2luZ3JlZGllbnQnLFxuICAgICAgICAgICAgJ2RhdGEtaWQnOiBtb2RlbEVsZW1lbnQuZ2V0QXR0cmlidXRlKCdkYXRhLWlkJylcbiAgICAgICAgfSk7XG5cbiAgICAgICAgLy8gSW5uZXIgbGFiZWwuXG4gICAgICAgIGNvbnN0IGlubmVyTGFiZWwgPSB3cml0ZXIuY3JlYXRlVUlFbGVtZW50KCdzcGFuJywgbnVsbCwgZnVuY3Rpb24oZG9tRG9jdW1lbnQpIHtcbiAgICAgICAgICAgIGNvbnN0IGRvbUVsZW1lbnQgPSB0aGlzLnRvRG9tRWxlbWVudChkb21Eb2N1bWVudCk7XG4gICAgICAgICAgICBkb21FbGVtZW50LmlubmVyVGV4dCA9IGluZ3JlZGllbnREYXRhLm5hbWU7XG4gICAgICAgICAgICBkb21FbGVtZW50LnNldEF0dHJpYnV0ZSgnY29udGVudGVkaXRhYmxlJywgJ2ZhbHNlJyk7XG4gICAgICAgICAgICByZXR1cm4gZG9tRWxlbWVudDtcbiAgICAgICAgfSk7XG4gICAgICAgIHdyaXRlci5pbnNlcnQod3JpdGVyLmNyZWF0ZVBvc2l0aW9uQXQod2lkZ2V0V3JhcHBlciwgMCksIGlubmVyTGFiZWwpO1xuXG4gICAgICAgIC8vIFJldHVybiB0aGUgd2lkZ2V0LlxuICAgICAgICByZXR1cm4gdG9XaWRnZXQod2lkZ2V0V3JhcHBlciwgd3JpdGVyLCB7IGxhYmVsOiAnaW5ncmVkaWVudCB3aWRnZXQnIH0pO1xuICAgICAgfVxuICAgIH0pO1xuXG4gIH1cblxuICBnZXRJbmdyZWRpZW50QnlJZChpZCkge1xuICAgIGNvbnN0IGF2YWlsYWJsZUluZ3JlZGllbnRzID0gZHJ1cGFsU2V0dGluZ3MucmVjaXBlcy5pbmdyZWRpZW50cztcbiAgICBjb25zdCBmb3VuZCA9IGF2YWlsYWJsZUluZ3JlZGllbnRzLmZpbmQoaW5ncmVkaWVudCA9PiBOdW1iZXIoaWQpID09PSBOdW1iZXIoaW5ncmVkaWVudC5pZCkpO1xuICAgIHJldHVybiBmb3VuZCB8fCBudWxsO1xuICB9XG5cblxuXG59Il0sIm5hbWVzIjpbXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./js/ckeditor5_plugins/insertIngredient/src/insertIngredientEditing.js\n\n}");

/***/ },

/***/ "./js/ckeditor5_plugins/insertIngredient/src/insertIngredientUi.js"
/*!*************************************************************************!*\
  !*** ./js/ckeditor5_plugins/insertIngredient/src/insertIngredientUi.js ***!
  \*************************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("{__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"default\": () => (/* binding */ InsertIngredientUI)\n/* harmony export */ });\n/* harmony import */ var ckeditor5_src_core__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ckeditor5/src/core */ \"ckeditor5/src/core.js\");\n/* harmony import */ var ckeditor5_src_ui__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ckeditor5/src/ui */ \"ckeditor5/src/ui.js\");\n/* harmony import */ var _icons_simpleBox_svg__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../icons/simpleBox.svg */ \"./icons/simpleBox.svg\");\n/**\n * @file registers the simpleBox toolbar button and binds functionality to it.\n */\n// eslint-disable-next-line\n\n// eslint-disable-next-line\n\nconst { createDropdown, addListToDropdown, UIModel } = CKEditor5.ui;\nconst { Collection } = CKEditor5.utils;\n\n\nclass InsertIngredientUI extends ckeditor5_src_core__WEBPACK_IMPORTED_MODULE_0__.Plugin {\n  init() {\n    const editor = this.editor;\n\n    editor.ui.componentFactory.add('insertIngredient', locale => {\n\n      const dropdownView = createDropdown(locale);\n\n      // 1. Setup the button look\n      dropdownView.buttonView.set({\n        label: 'Ingredient',\n        withText: true,\n        tooltip: true\n      });\n\n      // 2. Prepare the list items from your JS variable\n      const items = new Collection();\n\n      const availableIngredients = drupalSettings.recipes.ingredients;\n\n      availableIngredients.forEach(ingredient => {\n        items.add({\n          type: 'button',\n          model: new UIModel({\n            withText: true,\n            label: ingredient.name,\n            dataId: ingredient.id,\n          })\n        });\n      });\n\n      // 3. Add the list to the dropdown\n      addListToDropdown(dropdownView, items);\n\n      // 4. Listen to the execution\n      this.listenTo(dropdownView, 'execute', eventInfo => {\n        const { dataId } = eventInfo.source;\n        editor.execute('insertIngredient', { dataId });\n        editor.editing.view.focus();\n      });\n\n      return dropdownView;\n    });\n\n    \n  }\n}\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9qcy9ja2VkaXRvcjVfcGx1Z2lucy9pbnNlcnRJbmdyZWRpZW50L3NyYy9pbnNlcnRJbmdyZWRpZW50VWkuanMiLCJtYXBwaW5ncyI6Ijs7Ozs7OztBQUFBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vQ0tFZGl0b3I1Lmluc2VydEluZ3JlZGllbnQvLi9qcy9ja2VkaXRvcjVfcGx1Z2lucy9pbnNlcnRJbmdyZWRpZW50L3NyYy9pbnNlcnRJbmdyZWRpZW50VWkuanM/Mjk1OCJdLCJzb3VyY2VzQ29udGVudCI6WyIvKipcbiAqIEBmaWxlIHJlZ2lzdGVycyB0aGUgc2ltcGxlQm94IHRvb2xiYXIgYnV0dG9uIGFuZCBiaW5kcyBmdW5jdGlvbmFsaXR5IHRvIGl0LlxuICovXG4vLyBlc2xpbnQtZGlzYWJsZS1uZXh0LWxpbmVcbmltcG9ydCB7IFBsdWdpbiB9IGZyb20gJ2NrZWRpdG9yNS9zcmMvY29yZSc7XG4vLyBlc2xpbnQtZGlzYWJsZS1uZXh0LWxpbmVcbmltcG9ydCB7IEJ1dHRvblZpZXcgfSBmcm9tICdja2VkaXRvcjUvc3JjL3VpJztcbmNvbnN0IHsgY3JlYXRlRHJvcGRvd24sIGFkZExpc3RUb0Ryb3Bkb3duLCBVSU1vZGVsIH0gPSBDS0VkaXRvcjUudWk7XG5jb25zdCB7IENvbGxlY3Rpb24gfSA9IENLRWRpdG9yNS51dGlscztcbmltcG9ydCBpY29uIGZyb20gJy4uLy4uLy4uLy4uL2ljb25zL3NpbXBsZUJveC5zdmcnO1xuXG5leHBvcnQgZGVmYXVsdCBjbGFzcyBJbnNlcnRJbmdyZWRpZW50VUkgZXh0ZW5kcyBQbHVnaW4ge1xuICBpbml0KCkge1xuICAgIGNvbnN0IGVkaXRvciA9IHRoaXMuZWRpdG9yO1xuXG4gICAgZWRpdG9yLnVpLmNvbXBvbmVudEZhY3RvcnkuYWRkKCdpbnNlcnRJbmdyZWRpZW50JywgbG9jYWxlID0+IHtcblxuICAgICAgY29uc3QgZHJvcGRvd25WaWV3ID0gY3JlYXRlRHJvcGRvd24obG9jYWxlKTtcblxuICAgICAgLy8gMS4gU2V0dXAgdGhlIGJ1dHRvbiBsb29rXG4gICAgICBkcm9wZG93blZpZXcuYnV0dG9uVmlldy5zZXQoe1xuICAgICAgICBsYWJlbDogJ0luZ3JlZGllbnQnLFxuICAgICAgICB3aXRoVGV4dDogdHJ1ZSxcbiAgICAgICAgdG9vbHRpcDogdHJ1ZVxuICAgICAgfSk7XG5cbiAgICAgIC8vIDIuIFByZXBhcmUgdGhlIGxpc3QgaXRlbXMgZnJvbSB5b3VyIEpTIHZhcmlhYmxlXG4gICAgICBjb25zdCBpdGVtcyA9IG5ldyBDb2xsZWN0aW9uKCk7XG5cbiAgICAgIGNvbnN0IGF2YWlsYWJsZUluZ3JlZGllbnRzID0gZHJ1cGFsU2V0dGluZ3MucmVjaXBlcy5pbmdyZWRpZW50cztcblxuICAgICAgYXZhaWxhYmxlSW5ncmVkaWVudHMuZm9yRWFjaChpbmdyZWRpZW50ID0+IHtcbiAgICAgICAgaXRlbXMuYWRkKHtcbiAgICAgICAgICB0eXBlOiAnYnV0dG9uJyxcbiAgICAgICAgICBtb2RlbDogbmV3IFVJTW9kZWwoe1xuICAgICAgICAgICAgd2l0aFRleHQ6IHRydWUsXG4gICAgICAgICAgICBsYWJlbDogaW5ncmVkaWVudC5uYW1lLFxuICAgICAgICAgICAgZGF0YUlkOiBpbmdyZWRpZW50LmlkLFxuICAgICAgICAgIH0pXG4gICAgICAgIH0pO1xuICAgICAgfSk7XG5cbiAgICAgIC8vIDMuIEFkZCB0aGUgbGlzdCB0byB0aGUgZHJvcGRvd25cbiAgICAgIGFkZExpc3RUb0Ryb3Bkb3duKGRyb3Bkb3duVmlldywgaXRlbXMpO1xuXG4gICAgICAvLyA0LiBMaXN0ZW4gdG8gdGhlIGV4ZWN1dGlvblxuICAgICAgdGhpcy5saXN0ZW5Ubyhkcm9wZG93blZpZXcsICdleGVjdXRlJywgZXZlbnRJbmZvID0+IHtcbiAgICAgICAgY29uc3QgeyBkYXRhSWQgfSA9IGV2ZW50SW5mby5zb3VyY2U7XG4gICAgICAgIGVkaXRvci5leGVjdXRlKCdpbnNlcnRJbmdyZWRpZW50JywgeyBkYXRhSWQgfSk7XG4gICAgICAgIGVkaXRvci5lZGl0aW5nLnZpZXcuZm9jdXMoKTtcbiAgICAgIH0pO1xuXG4gICAgICByZXR1cm4gZHJvcGRvd25WaWV3O1xuICAgIH0pO1xuXG4gICAgXG4gIH1cbn1cbiJdLCJuYW1lcyI6W10sInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///./js/ckeditor5_plugins/insertIngredient/src/insertIngredientUi.js\n\n}");

/***/ },

/***/ "./icons/simpleBox.svg"
/*!*****************************!*\
  !*** ./icons/simpleBox.svg ***!
  \*****************************/
(module) {

"use strict";
module.exports = "<svg width=\"20\" height=\"20\" viewBox=\"0 0 20 20\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"><path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M1.95154 2.84131C1.95154 2.28902 2.39925 1.84131 2.95154 1.84131H17.0484C17.6007 1.84131 18.0484 2.28902 18.0484 2.84131V17.1588C18.0484 17.7111 17.6007 18.1588 17.0484 18.1588H2.95154C2.39925 18.1588 1.95154 17.7111 1.95154 17.1588V2.84131ZM3.5116 8.10129H16.4926V15.3194C16.4926 15.8717 16.0449 16.3194 15.4926 16.3194H4.5116C3.95931 16.3194 3.5116 15.8717 3.5116 15.3194V8.10129ZM4.44415 3.81676C3.89187 3.81676 3.44415 4.26447 3.44415 4.81676V6.35087H16.4316V4.81676C16.4316 4.26447 15.9838 3.81676 15.4316 3.81676H4.44415Z\" fill=\"black\"/></svg>\n";

/***/ },

/***/ "ckeditor5/src/core.js"
/*!************************************************************!*\
  !*** delegated ./core.js from dll-reference CKEditor5.dll ***!
  \************************************************************/
(module, __unused_webpack_exports, __webpack_require__) {

module.exports = (__webpack_require__(/*! dll-reference CKEditor5.dll */ "dll-reference CKEditor5.dll"))("./src/core.js");

/***/ },

/***/ "ckeditor5/src/ui.js"
/*!**********************************************************!*\
  !*** delegated ./ui.js from dll-reference CKEditor5.dll ***!
  \**********************************************************/
(module, __unused_webpack_exports, __webpack_require__) {

module.exports = (__webpack_require__(/*! dll-reference CKEditor5.dll */ "dll-reference CKEditor5.dll"))("./src/ui.js");

/***/ },

/***/ "ckeditor5/src/widget.js"
/*!**************************************************************!*\
  !*** delegated ./widget.js from dll-reference CKEditor5.dll ***!
  \**************************************************************/
(module, __unused_webpack_exports, __webpack_require__) {

module.exports = (__webpack_require__(/*! dll-reference CKEditor5.dll */ "dll-reference CKEditor5.dll"))("./src/widget.js");

/***/ },

/***/ "dll-reference CKEditor5.dll"
/*!********************************!*\
  !*** external "CKEditor5.dll" ***!
  \********************************/
(module) {

"use strict";
module.exports = CKEditor5.dll;

/***/ }

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		if (!(moduleId in __webpack_modules__)) {
/******/ 			delete __webpack_module_cache__[moduleId];
/******/ 			var e = new Error("Cannot find module '" + moduleId + "'");
/******/ 			e.code = 'MODULE_NOT_FOUND';
/******/ 			throw e;
/******/ 		}
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval-source-map devtool is used.
/******/ 	var __webpack_exports__ = __webpack_require__("./js/ckeditor5_plugins/insertIngredient/src/index.js");
/******/ 	__webpack_exports__ = __webpack_exports__["default"];
/******/ 	
/******/ 	return __webpack_exports__;
/******/ })()
;
});