jQuery(function ($) {
    $('#submitBtn').on('click', function () {
        let keyword = $('#queryString').val()
        $.ajax({
            url: '/query.php',
            data: {
                keyword
            }
        }).done(function (data) {
            $('#result').text(data.join("\r\n"))
        })
    })
})
