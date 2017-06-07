$(window).ready(function (){

    $(".addChild").click(function() {
        // Child row container
        childRowContainer = $(this).parent().prev(".childRowContainer");

        if(childRowContainer.children(".childRow:last").hasClass("hidden")) { // no row exist
            // remove hidden row signs
            childRowContainer.children(".childRow:last, .labelLeft, .labelRight").removeClass("hidden");
            childRowContainer.children(".childRow:last").children("input[type=text], select").prop("disabled", false);
        } else {
            // cloning child record row
            rowClone = childRowContainer.children(".childRow:last").clone(true, true);

            // remove cloned inserted elements values
            hookClickEvent($(rowClone).find('.removeChild'));

            $(rowClone).children("input[type=text]").val("").prop('disabled', false);
            $(rowClone).children("select").find('option').removeAttr("selected");

            // remove disabled elements' attributes
            $(rowClone).children("input[type=text], select").removeClass('disabledInput');
            $(rowClone).children(".removeChild").removeClass('hidden');
            $(rowClone).children("input.isDisabledForEditing").val(0);

            // cloned row inserted into main child container
            rowClone.appendTo(childRowContainer);

            // create additional <div class="float-clear"></div> element
            clearDiv = $('<div />', {"class": 'float-clear'});
            clearDiv.appendTo(childRowContainer);
        }

        return false;
    })

    function hookClickEvent(exactTarget) {
        exactTarget.click(function() {
            // main child records container
            childRowContainer = $(this).parent().parent(".childRowContainer");

            if(childRowContainer.children('.childRow').size() > 1) {
                $(this).parent().next(".float-clear").remove();
                $(this).parent().remove();
            } else { // do not remove last row, just hide
                childRowContainer.children('.childRow, .labelLeft, .labelRight').addClass("hidden");
                childRowContainer.children(".childRow").children("input[type=text], select").prop("disabled", true);
            }

            return false;
        });
    }

    // Time and date plugins settings
    $.datetimepicker.setLocale('lt');
    $('.datetime').datetimepicker({
        format:'Y-m-d H:i',
        dayOfWeekStart : 1,
        startDate: '2016-01-01',
        defaultDate: '2016-01-01'
    });

    $('.date').datetimepicker({
        yearOffset:222,
        timepicker:false,
        format:'Y-m-d',
        formatDate:'Y-m-d',
        defaultDate: '2016-01-01'
    });

});

function showConfirmDialog(module, removeId) {
    var r = confirm("Ar tikrai norite pašalinti!");
    if (r === true) {
        window.location.replace("index.php?module=" + module + "&remove=" + removeId);
    }
}
