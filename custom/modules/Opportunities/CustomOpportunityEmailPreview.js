function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

$( document ).ready(function() {
    $("#subpanel_history tr").each(function() {
        var recordId = getParameterByName('record', $(this).find("td:nth-child(3) a").attr('href'));
        var url = "/index.php?entryPoint=customOppEmailPreview&record=" + recordId;
        $(this).find("td:nth-child(4)").append('<a class="various fancybox.ajax" data-fancybox-type="ajax" href="' + url + '"> Preview</a>');
    });

    $(".various").fancybox({
		maxWidth	: 800,
		maxHeight	: 600,
		fitToView	: false,
		width		: '70%',
		height		: '70%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none'
	});
});