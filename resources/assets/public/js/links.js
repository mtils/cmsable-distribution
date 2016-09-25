$(function () {
    "use strict";
    $("select[name='type']").change(switchHref);
    switchHref();
});

function switchHref() {
	var value = $("select[name='type']").val();
	switch(value) {
		case 'extern':
			$("input[name='href_extern']").parent().show();
			$("select[name='href_page']").parent().hide();
			$("select[name='href_file']").parent().hide();
			break;
		case 'page':
			$("input[name='href_extern']").parent().hide();
			$("select[name='href_page']").parent().show();
			$("select[name='href_file']").parent().hide();
			break;
		case 'file':
			$("input[name='href_extern']").parent().hide();
			$("select[name='href_page']").parent().hide();
			$("select[name='href_file']").parent().show();
			break;
	}
}