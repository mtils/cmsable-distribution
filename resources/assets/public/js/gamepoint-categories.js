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
    $('#tree-container').jstree({
        "core":{
            "check_callback":true
        },
        "plugins" : [ "contextmenu" ]
    });
    $('#tree-container').on("changed.jstree", function (e, data) {
//         if($('#'+data.selected[0]).hasClass('root-node')){
//             return;
//         }
        window.location.href = $('#tree-container').data('edit-url') + '/edit/' + data.selected[0].replace('node-','')
    });
    $('#page-form__content').ckeditor({
        customConfig: '/themes/admin/js/ckconfig.js',
        toolbar: 'Full'
    });

    $('#page-form__menu_title').keyup(function(e){
        var val = $('#page-form__menu_title').val().toLowerCase();
        var search =  ['ä' ,'ö' ,'ü' ,'ß' ,' ','_','&'];
        var replace = ['ae','oe','ue','ss','-','-','and'];
        var url_segment = val.replaceArray(search, replace)
        $('#page-form__url_segment').val(url_segment);
    });

    $('div.new-page').click(function(){
        var newUrl = $('#sitetree-container').data('edit-url') + '/new';
        if($('#sitetree-container li.active').length > 0){
            var parentId = $('#sitetree-container li.active').attr('id').replace('sitetree-','');
            newUrl += '?parent_id=' + parentId;
        }
        window.location.href = newUrl;
    });
    $('#page-form .action_delete').click(function(e){
        if(!window.confirm('Wollen Sie die Seite wirklich löschen')){
            e.preventDefault();
        }
    });
});