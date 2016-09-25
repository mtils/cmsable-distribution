function baseName(str)
{
//    var base = new String(str).substring(str.lastIndexOf('/') + 1);
   return new String(str).substring(str.lastIndexOf('/') + 1);
//     if(base.lastIndexOf(".") != -1)
//         base = base.substring(0, base.lastIndexOf("."));
//    return base;
}

function deleteResource(url, element) {

    var message = $(element).data('confirm-message');
    var forward = $(element).data('forward');

    if (!message) {
        message = 'Are you sure to delete this item?';
    }

    if (!forward) {
        forward = window.location.href;
    }

    if (!confirm(message)) {
        return;
    }

    $.ajax({
        url: url,
        type: 'DELETE',
        success: function(result) {
            window.location.href = forward;
        },
        error: function(result) {
            alert('The item could not be deleted');
        }
    });

}

function downloadSearchResult(href) {

    if (href.indexOf('?') == -1) {
        href += '?Content-Type=text/csv';
    }
    else {
        href += '&Content-Type=text/csv';
    }

    $.fileDownload(href,{
        successCallback: function (url) {
            alert('Success');
        },
        failCallback: function (responseHtml, url) {
            alert('Failure');
        }
    });
    return false; //this is critical to stop the click event which will trigger a normal file download!

}

function putRequest(url, forward) {

    if (!forward) {
        forward = window.location.href;
    }

    $.ajax({
        url: url,
        type: 'PUT',
        success: function(result) {
            window.location.href = forward;
        },
        error: function(result) {
            alert('The was an error handling the request');
        }
    });

}

function sendTestMailFromMailingPreview(url, successMessage, errorMessage) {

    $.ajax({
        url: url,
        type: 'POST',
        success: function(result) {
            alert(successMessage);
        },
        error: function(result) {
            alert(errorMessage);
        }
    });

}

function openModalIframe(url){

    $.ajax({
        method: 'GET',
        url: url,
    }).done(function(html){
        $('#modal-ajax-content').html(html);
        $('#inline-modal').modal({show:true})
    });
    
//     $('#modal-iframe-frame').attr("src", url);

//     $('#modal-iframe').on('show', function () {
//         console.log(url);
//         $('#modal-iframe-frame').attr("src", url);
//     });
    

}

assignToLastClickedImageField = function(id, url){
    if(window.lastClickedImageField){
        jQuery(window.lastClickedImageField).find('input.image-db-field').attr('value',id);
        jQuery(window.lastClickedImageField).find('img').attr('src',url);
    }
    window.lastClickedImageField = null;
}

assignToLastClickedUploadField = function(id, name){
    if(window.lastClickedUploadField){
        jQuery(window.lastClickedUploadField).find('.upload-db-field').attr('value',id);
        jQuery(window.lastClickedUploadField).find('input.filename').attr('value', name);
    }
    window.lastClickedUploadField = null;
}

function formatResult (item) {
    if (item.loading) return item.text;


    var keyCount = item._keys.length;
    var colWidth = 12/keyCount;

    if (colWidth.toString().indexOf('.') != -1) {
        colWidth = Math.floor(colWidth);
    }

    var markup = '<div class="clearfix">';

    for (var idx in item._keys) {
        var key = item._keys[idx];
        markup += '<div class="col-sm-' + colWidth + '">' + item[key] + 
'</div>';
    }

    markup += '</div>';

    return markup;
}

function formatAutocompleterSelection (item) {
    return item.text || item.short_name;
}

$(document).on('click','table.inline-edit a.row-remove', function(event){
    $(event.target).closest('tr').remove();
    return false;
});

$(document).on('click','table.inline-edit a.row-add', function(event){

    var table = $(event.target).closest('table');

    var template = table.prev('div.row-template')
    var copy = document.createElement("tr");
    copy.innerHTML = template.text();

    // Find a free number
    var highest = 0;
    var num = 0;
    var existingElementsFound = false;

    $(table).find('input,select,textarea').each(function(){
        var element = $(this);
        var name = element.attr('name');
        var number = name.replace( /[^\d.]/g, '');
        if(number){
            existingElementsFound = true;
            var num = parseInt(number);
            if(num && num > highest){
                highest = num;
            }
        }
    });

    var next = existingElementsFound ? highest + 1 : highest;

    if(copy){
        $(copy).find('input,select,textarea').each(function(){
            var element = $(this);
            var name = element.attr('name');
            var id = element.attr('id');
            element.attr('name', name.replace('[x]','['+next+']'));
            element.attr('id', id.replace('x',next));
            if(element.attr('name') == 'id'){
                element.attr('value','');
            }
        });

        table.find('tbody').append(copy);
        table.trigger('child-added', copy);
    }

    return false;
});


$(function () {

    "use strict";

    if(jQuery('select.selectpicker').length){
        jQuery('select.selectpicker').selectpicker();
    }

    if(jQuery('input.details__expires_at').length){
        jQuery('input.details__expires_at').datepicker({
            'format':'dd.mm.yyyy',
            'weekStart' : 1,
            'language' : 'de'
        });
    }

    if(jQuery('input.date-field').length){
        jQuery('input.date-field').datepicker({
            'format':'dd.mm.yyyy',
            'weekStart' : 1,
            'language' : 'de'
        });
    }

    if(jQuery('input.schedule__start').length){
        jQuery('input.schedule__start').datepicker({
            'format':'dd.mm.yyyy',
            'weekStart' : 1,
            'language' : 'de'
        });
    }

    if(jQuery('input.schedule__end').length){
        jQuery('input.schedule__end').datepicker({
            'format':'dd.mm.yyyy',
            'weekStart' : 1,
            'language' : 'de'
        });
    }


    if(jQuery('textarea.html').length){
        jQuery('textarea.html').each(function(){
            $(this).ckeditor({
                customConfig: '/cmsable/js/ckconfig.js',
                toolbar: 'Full',
                contentsCss: window.cmsEditorCss,
                extraPlugins: $(this).data('variables') ? 'stylesheetparser,cmsablevariables' : 'stylesheetparser'
            });
        });
    }

    if(jQuery('div.image-field-placeholder').length){
        jQuery('div.image-field-placeholder').click(function(e){
            window.lastClickedImageField = jQuery(e.target).closest('div.image-field-placeholder');
            window.open(window.fileroute + '?type=image&context=image-field','filemanager','height=500,width=768,scrollbars=yes,toolbar=no');
        });
    }

    if(jQuery('div.upload-field .choose').length){
        jQuery('div.upload-field .choose').click(function(e){
            window.lastClickedUploadField = jQuery(e.target).closest('div.upload-field');
            window.open(window.fileroute + '?context=upload-field','filemanager','height=500,width=768,scrollbars=yes,toolbar=no');
        });
    }

    if(jQuery('a.inline-filemanager').length){
        jQuery('a.inline-filemanager').click(function(e){
            e.preventDefault();
            var href = jQuery(e.target).attr('href');
            window.open(href, 'imgViewer','width=600,height=400');
        });
    }

    if(jQuery('.filemanager a.normal').length){
        jQuery('.filemanager a.normal').click(function(e){
            e.preventDefault();
            var href = jQuery(e.target).attr('href');
            window.opener.CKEDITOR.tools.callFunction(1, href);
            window.close();
        });
    }

    if(jQuery('.filemanager a.image-field').length){
        jQuery('.filemanager a.image-field').click(function(e){
            e.preventDefault();
            var href = jQuery(e.target).attr('href');
            var id = jQuery(e.target).data('id');
            window.opener.assignToLastClickedImageField(id, href);
            window.close();
        });
    }

    if(jQuery('.filemanager a.upload-field').length){
        jQuery('.filemanager a.upload-field').click(function(e){
            e.preventDefault();
            var href = jQuery(e.target).attr('href');
            var id = jQuery(e.target).data('id');
            window.opener.assignToLastClickedImageField(id, href);
            window.close();
        });
    }

    $('select[multiple]').each(function(e){

        var url = $(this).data('query-url');
        var paramName = $(this).data('query-param');

        if (url && paramName) {

            $(this).select2({
                ajax: {
                    url: url,
                    dataType: 'json',
                    delay: 250,
                    data: function(params){
                        var parsed={};
                        parsed[paramName] = params.term;
                        return parsed;
                    },
                    processResults: function (data, page) {

                        // Append the keys to ensure key ordering. JSON has
                        // unordered "associative" arrays
                        var withKeys = [];

                        for (var idx in data.data) {
                            data.data[idx]['_keys'] = data.keys;
                            withKeys.push(data.data[idx]);
                        }

                        return {
                            results: withKeys
                        };

                    },
                    cache: true
                },
                escapeMarkup: function (markup) { return markup; },
                minimumInputLength: 3,
                templateResult: formatResult,
                templateSelection: formatAutocompleterSelection
            });

        }
        else{
            $(this).select2();
        }

    });

});
