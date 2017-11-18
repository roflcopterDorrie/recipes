jQuery(document).ready(function () {

    var ingredientCount = 0;

    $('#add-ingredient').on('click', function (e) {
        e.preventDefault();
        var row = $('#ingredients tr:nth-child(2)').clone();
        var ingredient = row.find('td:nth-child(1) input');
        ingredient.attr('name', 'RecipeIngredient[new][' + ingredientCount + '][ingredient]');
        ingredient.val('');
        var section = row.find('td:nth-child(2) select');
        section.attr('name', 'RecipeIngredient[new][' + ingredientCount + '][ingredient_store_section_id]');
        section.val('');
        $('#ingredients tbody').append(row);
        ingredientCount++;
    });

    var stepCount = 0;

    $('#add-step').on('click', function (e) {
        e.preventDefault();
        var row = $('#steps tr:nth-child(2)').clone();
        var step = row.find('td:nth-child(1) textarea');
        step.attr('name', 'RecipeStep[new][' + stepCount + '][step]');
        step.html('');
        $('#steps tbody').append(row);
        stepCount++;
    });


    var textArea;
    $('#steps textarea').on('blur', function () {
        textArea = $(this).attr('id');
    });

    $('.insert-ingredient').on('click', function (e) {
        insertAtCaret(textArea, '[ingredient:' + $(this).attr('data-ingredient') + ']');
    });

    function insertAtCaret(areaId, text) {
        var txtarea = document.getElementById(areaId);
        var scrollPos = txtarea.scrollTop;
        var strPos = 0;
        var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ?
                "ff" : (document.selection ? "ie" : false));
        if (br == "ie") {
            txtarea.focus();
            var range = document.selection.createRange();
            range.moveStart('character', -txtarea.value.length);
            strPos = range.text.length;
        }
        else if (br == "ff")
            strPos = txtarea.selectionStart;

        var front = (txtarea.value).substring(0, strPos);
        var back = (txtarea.value).substring(strPos, txtarea.value.length);
        txtarea.value = front + text + back;
        strPos = strPos + text.length;
        if (br == "ie") {
            txtarea.focus();
            var range = document.selection.createRange();
            range.moveStart('character', -txtarea.value.length);
            range.moveStart('character', strPos);
            range.moveEnd('character', 0);
            range.select();
        }
        else if (br == "ff") {
            txtarea.selectionStart = strPos;
            txtarea.selectionEnd = strPos;
            txtarea.focus();
        }
        txtarea.scrollTop = scrollPos;
    }
});