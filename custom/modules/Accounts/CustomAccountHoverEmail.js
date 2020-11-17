function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}
$(function () {
    'use strict';
    $( document ).ready(function() {
        $('body').on('click','.preview_function',function(){
            var recordId = getParameterByName('record', $(this).parent().parent().find("td:nth-child(3) a").attr('href'));
            var url = "/index.php?entryPoint=customAccountHoverEmail&record=" + recordId;
            $.fancybox({
                maxWidth	: 800,
                maxHeight	: 600,
                fitToView	: false,
                width		: '70%',
                height		: '70%',
                autoSize	: false,
                closeClick	: false,
                openEffect	: 'none',
                closeEffect	: 'none',
                href: url,
                type: 'ajax',
            })
        });
        // $('#tab-actions').after($('#tab-actions li:first').clone());
    });
});