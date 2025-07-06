import './bootstrap';

"use strict";
window.copyToClipboard = function (button) {
    var tempInput = button.parentElement.querySelector('input');
    tempInput.select();
    document.execCommand("copy");

    var copyIcon = button.querySelector('.copy-icon');
    var rightIcon = button.querySelector('.right-icon');

    copyIcon.classList.add('hidden');
    rightIcon.classList.remove('hidden');

    setTimeout(function () {
        copyIcon.classList.remove('hidden');
        rightIcon.classList.add('hidden');
    }, 2000);
}

$(function () {
    $('#languageButton').on('click', function () {
        $('#languageModal').toggleClass('hidden');
    });

    $(document).on('click', function (event) {
        var $target = $(event.target);
        if (!$target.closest('#languageModal').length && !$target.closest('#languageButton').length) {
            $('#languageModal').addClass('hidden');
        }
    });

    $('#languageModal button').on('click', function () {
        var selectedLang = $(this).data('lang');
        changeLanguage(selectedLang);
    });

    function changeLanguage(language) {
        $.ajax({
            url: '/change-language/' + language,
            success: function () {
                location.reload();
            },
            error: function (data) {
                console.log(data);
            }
        });
    }
});
