$(function () {
    'use strict';

    $(document).on('DOMNodeInserted', function (e) {
        var $target = $(event.target);
        if ($target.attr('id') == "aor_conditions_value[0]") {
            loadPartNumber();
            autocomplete();
        }
    });
    loadPartNumber();
    autocomplete();
});

function loadPartNumber(partNumber = "", type = "") {
    $.ajax({
        url: "?entryPoint=loadPartNumber&partNumber=" + partNumber + "&type=" + type,
        success: function (data) {
            if (typeof data != 'undefined' || data != '') {
                var json = $.parseJSON(data);
                console.log(json.partNumber)
                document.querySelector("input[name='aor_conditions_value[0]']").value = json.partNumber;
            }

        }
    })
}

function autocomplete() {
    var timeout = null
    $(document).find("input[name='aor_conditions_value[0]']").autocomplete({
        source: function (request, response) {
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                if (request["term"].length > 3) {
                    $.ajax({
                        url: "?entryPoint=loadPartNumber&partNumber=" + $("input[name='aor_conditions_value[0]']").val() + "&type=list",
                        type: 'GET',
                        success: function (data) {
                            if (data == '' && typeof data == undefined) return;
                            var suggest = [];
                            var jsonObject = $.parseJSON(data);
                            for (i = 0; i < jsonObject.length; i++) {
                                suggest.push(jsonObject[i]);
                            }
                            console.log(jsonObject);
                            response(suggest);
                        },
                        error: function (response) { console.log("Fail"); },
                    });
                }
            }, 300);
        },
        select: function (event, ui) {
            console.log(ui.item.value);
            $("input[name='aor_conditions_value[0]']").val(ui.item.value);
            loadPartNumber($("input[name='aor_conditions_value[0]']").val(), "save");
            $("#updateParametersButton").trigger('click');

            return false;
        }
    });
}