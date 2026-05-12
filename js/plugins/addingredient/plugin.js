import Plugin from '@ckeditor/ckeditor5-core/src/plugin';
import Widget from '@ckeditor/ckeditor5-widget/src/widget';
import { toWidget } from '@ckeditor/ckeditor5-widget/src/utils';
import { createDropdown, addListToDropdown } from '@ckeditor/ckeditor5-ui/src/dropdown/utils';
import Collection from '@ckeditor/ckeditor5-utils/src/collection';
import Model from '@ckeditor/ckeditor5-ui/src/model';


export default class AddIngredient extends Plugin {
  static get requires() {
    return [Widget];
  }

  init() {
    const editor = this.editor;

    // 1. Schema
    editor.model.schema.register('ingredient', {
      isObject: true,
      allowWhere: '$block',
      allowAttributes: ['data-id'],
    });


    // 2. Downcast: model → editor view
    editor.conversion.for('editingDowncast').elementToElement({
      model: 'ingredient',
      view: (modelEl, { writer }) => {
        const id = modelEl.getAttribute('data-id');

        const container = writer.createContainerElement('ingredient', {
          class: 'my-widget',
          'data-id': id
        });

        // Temporary loading placeholder
        const loading = writer.createRawElement(
          'div',
          { class: 'ingredient-loading' },
          domElement => {
            domElement.innerHTML = 'Loading ingredient…';
          }
        );

        writer.insert(writer.createPositionAt(container, 0), loading);

        return toWidget(container, writer, {
          label: 'Ingredient'
        });
      }
    });

    // 3. Downcast: model → HTML
    editor.conversion.for('dataDowncast').elementToElement({
      model: 'ingredient',
      view: (modelEl, { writer }) => {
        return writer.createEmptyElement('ingredient', {
          class: 'recipes-ingredient',
          'data-id': modelEl.getAttribute('data-id')
        });
      }
    });


    // 4. Upcast: HTML → model
    editor.conversion.for('upcast').elementToElement({
      view: {
        name: 'ingredient',
        classes: 'my-widget',
      },
      model: (viewEl, { writer }) => {
        const id = viewEl.getAttribute('data-id');

        const modelEl = writer.createElement('ingredient', {
          'data-id': id,
        });

        const textNode = viewEl.getChild(0);
        if (textNode) {
          writer.insertText(textNode.data, modelEl);
        }

        return modelEl;
      }
    });

    // 5. Toolbar button
    editor.ui.componentFactory.add('ingredient', locale => {
      const dropdown = createDropdown(locale);
      dropdown.buttonView.set({
        label: 'Ingredient',
        withText: true
      });

      const items = new Collection();
      const ingredients = window.drupalSettings?.recipes?.ingredients || [];

      ingredients.forEach(ingredient => {
        items.add({
          type: 'button',
          model: new Model({
            label: ingredient.label,
            withText: true,
            ingredientId: ingredient.id
          })
        });
      });

      addListToDropdown(dropdown, items);

      dropdown.on('execute', evt => {
        const { ingredientId, label } = evt.source;

        editor.model.change(writer => {
          const element = writer.createElement('ingredient', {
            'data-id': ingredientId,
          });

          writer.insertText(label, element);
          editor.model.insertContent(element);
        });
      });

      return dropdown;
    });

    editor.editing.view.document.on('render', () => {
      const domRoot = editor.editing.view.domRoots.get('main');

      domRoot.querySelectorAll('ingredient').forEach(el => {
        if (el.dataset.loaded) return;

        el.dataset.loaded = true;

        fetch(`/recipes/ingredient-preview/${el.dataset.id}`)
          .then(r => r.json())
          .then(data => {
            el.innerHTML = data.html;
          });
      });
    });

  }
}