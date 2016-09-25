$(function () {
    "use strict";
    $('#tree-container').jstree({
        "core":{
            "check_callback":true
        },
        "plugins" : [ "contextmenu" , "dnd" ]
    }).bind("move_node.jstree", function(e, data){

        if(!window.confirm('Really move this item?')){
            e.stopImmediatePropagation();
            window.location.reload();
            return false;
        }

        var movedId = data.node.li_attr['data-id'];
        var parentId = $('#'+data.parent).data('id');
        var position = parseInt(data.position) + 1;
        var newUrl = $('#tree-container').data('edit-url') + '/' + movedId + '/parent'

        $.ajax({
            url: newUrl,
            type: 'POST',
            data: {
                id: parentId,
                position: position,
                _method: 'PUT'
            },
            success: function(result) {
                window.location.reload();
            },
            error: function(result) {
                alert('The item could not be moved');
            }
        });

    });
    $('#tree-container').on("changed.jstree", function (e, data) {
        var clickedId = data.node.li_attr['data-id'];
        var newUrl = $('#tree-container').data('edit-url') + '/' + clickedId + '/edit';
        window.location.href = newUrl;
    });

    $('div.new-node').click(function(){
        var newUrl = $('#tree-container').data('edit-url');
        if($('#tree-container span.active').length > 0){
            var parentId = $('#tree-container span.active').parents('li').data('id');
            newUrl += '/' + parentId + '/children/create';
        }
//         alert(newUrl); return false;
        window.location.href = newUrl;
    });

});