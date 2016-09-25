
function ckeditor_cmsvariables_convertMenu(variables)
{
    var items = {};
    for (var key in variables) {

        items[variables[key]['name']] = {name: variables[key]['title']};

        if (typeof variables[key]['children'] === 'undefined') {
            continue;
        }

        items[variables[key]['name']]['items'] = ckeditor_cmsvariables_convertMenu(variables[key]['children']);

    }

    return items;

}

function addContextMenuIfNotAdded(editor, button, variables) {

    if (typeof button['ctxMenu'] !== 'undefined') {
        return;
    }

    button['ctxMenu'] = true;

    var items = ckeditor_cmsvariables_convertMenu(variables);

    $.contextMenu({
        selector: '#' + $(button).attr('id'),
        trigger: 'none',
        callback: function(key, options) {
            editor.insertText(key);
        },
        items: items
    });

}


CKEDITOR.plugins.add( 'cmsablevariables',
{
    init: function( editor )
    {
        editor.addCommand( 'showVariables', {

            exec : function( editor ) {
                var textArea = editor.element.$;

                var buttons = $(editor.container.$).find('.cke_button__variables');

                if (!buttons.length) {
                    console.error('Button element not found in editor DOM');
                    return;
                }

                var button = buttons[0];

                addContextMenuIfNotAdded(editor, button, $(textArea).data('variables'));

                $(button).contextMenu();

                return;

            }
        });

        editor.ui.addButton( 'Variables', {
            label: 'Insert Variable',
            command: 'showVariables',
            icon: this.path + 'images/variables.png'
        });

    }
} );
