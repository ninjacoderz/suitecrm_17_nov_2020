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

//FOR ADDRESS'S DETAILVIEW IN ACCOUNT'S DETAIL
$(document).ready(function(){
    var record = $('#formDetailView').find('input[name="record"]').val();
    var module = $('#formDetailView').find('input[name="module"]').val();
    $(document).find('#account_address_detail').closest('.detail-view-row-item').hide();
    $.ajax({
        url: "index.php?entryPoint=showDetailViewOtherModule",
        type: 'GET',
        async: false,
        data: {
            record: record,
            module: module,
        },
        success: function(data) {
            // debugger
            if (data == '' || typeof data === 'undefined') return;
            let result = JSON.parse(data);
            if (Object.keys(result.contents).length > 0) {
                window.addressContent = result.contents;
                $(document).find('#account_address_detail').closest('.detail-view-row-item').after(result.selector);
                $(document).find('#content_address_selected').append(result.contents[$('#detail_pe_address').val()]);
                // $(document).find('#account_address_detail').closest('.detail-view-row-item').hide();
            } else {
                console.log('No address!');
                return;
            }
        }
    });

    $(document).find('#detail_pe_address').on('change', function() {
        $(document).find('#content_address_selected').empty();
        $(document).find('#content_address_selected').append(window.addressContent[$('#detail_pe_address').val()]);
    });
});