$(function () {
    "use strict";
    $('#sitetree-container').jstree({
        "core" : {
            "check_callback" : true
        },
        "plugins" : [ "dnd" ]
    }).bind("move_node.jstree", function(e, data){
        var movedId = data.node.id.replace('sitetree-','');
        var parentId = data.parent.replace('sitetree-','');
        var position = parseInt(data.position) + 1;
        var newUrl = $('#sitetree-container').data('edit-url') + '/move/' + movedId + '?into=' + parentId + '&position=' + position
        window.location.href = newUrl;
    });
    $('div.new-page').click(function(){
        var newUrl = $('#sitetree-container').data('edit-url') + '/new';
        if($('#sitetree-container li.active').length > 0){
            var parentId = $('#sitetree-container li.active').attr('id').replace('sitetree-','');
            newUrl += '?parent_id=' + parentId;
        }
        window.location.href = newUrl;
    });
});