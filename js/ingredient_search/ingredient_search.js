import Fuse from 'https://cdn.jsdelivr.net/npm/fuse.js@7.4.1/dist/fuse.basic.min.mjs';

(function (Drupal, once) {

  let ingredientCache = null;

  async function getIngredients() {
    if (ingredientCache !== null) {
      return ingredientCache;
    }

    const res = await fetch('/recipes/api/ingredients?_format=json');
    ingredientCache = await res.json();

    return ingredientCache;
  }

  Drupal.behaviors.recipesIngredientSearch = {
    attach: function (context) {

      once('recipesIngredientSearch', "#recipes-ingredient-search-wrapper", context).forEach(async function () {

        const ingredients = await getIngredients();

        const fuse = new Fuse(ingredients, {
          keys: ['title'],
          includeScore: true,
          threshold: 0.3,
          ignoreLocation: true,
        });

        renderResults([]);

        const ingredientInput = document.querySelector('#recipes-ingredient-search');

        ingredientInput.addEventListener('input', debounce(function (event) {
          const term = event.target.value;

          if (term.length == 0) {
            refreshViewWithIds([]);
            renderResults([]);
            return;
          }

          const results = fuse.search(term);
          renderResults(results);

          let ingredientIds = [];
          results.forEach(({ item }) => {
            ingredientIds.push(item.tid);
          });
          if (ingredientIds.length > 0) {
            refreshViewWithIds(ingredientIds);
          } else {
            clearResults();
          }
        }, 200)
        );

        function renderResults(results) {
          // Clear the results.
          const ingredientResults = document.querySelector('#recipes-ingredient-search--found-ingredients');
          ingredientResults.innerHTML = '';

          // Add the label.
          const labelTemplate = document.querySelector('#recipes-ingredient-label');
          ingredientResults.appendChild(labelTemplate.content.cloneNode(true));

          // Add found ingredients.
          const ingredientTemplate = document.querySelector('#recipes-ingredient-template');
          results.forEach(({ item }) => {
            const clone = ingredientTemplate.content.cloneNode(true);
            clone.querySelector('.recipes-search-result__ingredient').textContent = item.title;
            ingredientResults.appendChild(clone);
          });

          // If no results, put a message.
          if (results.length == 0) {
            const noResultsTemplate = document.querySelector('#recipes-ingredient-no-results');
            ingredientResults.appendChild(noResultsTemplate.content.cloneNode(true));
          }
        }

        function refreshViewWithIds(ids) {
          const form = document.querySelector('.views-exposed-form');

          if (!form) return;

          const inputs = form.querySelectorAll('input[data-drupal-selector^="edit-tid-"]');

          // Loop through each option and check if its value is in your array
          inputs.forEach(input => {
            let id = input.getAttribute('data-drupal-selector');
            id = id.replaceAll('edit-tid-', '');
            input.checked = ids.includes(id);
          });

          form.querySelector('[type="submit"]').click();
        }

        function debounce(fn, delay) {
          let timeout;
          return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => fn.apply(this, args), delay);
          };
        }

        function clearResults() {
          let results = document.querySelector(".recipes-card-list");
          results.innerHTML = "";
        }

      });
    }
  };

})(Drupal, once);
