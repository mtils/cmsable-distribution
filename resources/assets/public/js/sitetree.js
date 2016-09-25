String.prototype.replaceArray = function(find, replace) {
  var replaceString = this;
  var regex;
  for (var i = 0; i < find.length; i++) {
    regex = new RegExp(find[i],"g");
    replaceString = replaceString.replace(regex, replace[i]);
  }
  return replaceString;
};

$(function () {
    "use strict";
    $('#sitetree-container').jstree({
        "core":{
            "check_callback":true
        },
        "plugins" : [ "contextmenu" , "dnd" ]
    }).bind("move_node.jstree", function(e, data){
        if(!window.confirm(window.cmsMessages['page-move-confirm'])){
            e.stopImmediatePropagation();
            window.location.href = $('#sitetree-container').data('edit-url') + '/' + data.node.id.replace('node-','') + '/edit';
            return false;
        }
        var movedId = data.node.id.replace('node-','');
        var parentId = data.parent.replace('node-','');
        var position = parseInt(data.position) + 1;
        var newUrl = $('#sitetree-container').data('edit-url') + '/move/' + movedId + '?into=' + parentId + '&position=' + position
        window.location.href = newUrl;

    });

    $('#sitetree-container').on("changed.jstree", function (e, data) {

        // Handle only left mouse button clicks
//         if(e.which != 1) {
//             return;
//         }

        if($('#'+data.selected[0]).hasClass('root-node')){
            return;
        }
        window.location.href = $('#sitetree-container').data('edit-url') + '/' + data.selected[0].replace('node-','') + '/edit';
    });

    $('#page-form__content').ckeditor({
        customConfig: '/cmsable/js/ckconfig.js',
        toolbar: 'Full',
        contentsCss: window.cmsEditorCss,
        extraPlugins: 'stylesheetparser'
    });

    $('.rich-text').ckeditor({
        customConfig: '/cmsable/js/ckconfig.js',
        toolbar: 'Full',
        contentsCss: window.cmsEditorCss,
        extraPlugins: 'stylesheetparser'
    });

    $('#page-form__menu_title').keyup(function(e){
        var val = $('#page-form__menu_title').val().toLowerCase();
        var search =  ['ä' ,'ö' ,'ü' ,'ß' ,' ','_','&'];
        var replace = ['ae','oe','ue','ss','-','-','and'];
        var url_segment = val.replaceArray(search, replace)
        $('#page-form__url_segment').val(url_segment);
    });

    $('div.new-page').click(function(){
        var newUrl = $('#sitetree-container').data('edit-url') + '/create';
        if($('#sitetree-container span.active').length > 0){
            var parentId = $('#sitetree-container span.active').parents('li').attr('id').replace('node-','');
            newUrl += '?parent_id=' + parentId;
        }
        window.location.href = newUrl;
    });
    $('#page-form .action_delete').click(function(e){
        if(!window.confirm(window.cmsMessages['page-delete-confirm'])){
            e.preventDefault();
            return;
        }
    });
    $(document).on('click','table.inline-edit a.row-remove', function(event){
        $(event.target).closest('tr').remove();
        return false;
    });

    $(document).on('click','table.inline-edit a.row-add', function(event){

        var table = $(event.target).closest('table');
        var copy = table.find('tbody tr:first-child').clone();

        // Find a free number
        var highest = 0;
        var num = 0;

        $(table).find('input,select,textarea').each(function(){
                var element = $(this);
                var name = element.attr('name');
                var number = name.replace( /[^\d.]/g, '');
                if(number){
                    var num = parseInt(number);
                    if(num && num > highest){
                        highest = num;
                    }
                }
        });

        if(copy){
            $(copy).find('input,select,textarea').each(function(){
                var element = $(this);
                var name = element.attr('name');
                var id = element.attr('id');
                var number = name.replace( /[^\d.]/g, '');
                var next = highest + 1;
                element.attr('name',name.replace('['+number+']','['+next+']'));
                element.attr('id',id.replace(number,next));
                if(element.attr('name') == 'id'){
                    element.attr('value','');
                }
            });
            copy.appendTo(table.find('tbody'));
        }

        return false;
    });
});