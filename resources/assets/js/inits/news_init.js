var counter = 0,
    fillPostContent =  function(d, editCheck) {
        return '<div id="post_' + d.id + '">' +
            '<div class="row postrow">' +
                '<div class="col-sm-3 col-md-3 col-xs-2 posts">' +
                    '<h4>' + d.created_at + '<br><a href="' + window.urlTo + 'user/profile/' + d.user_id + '">' + d.user_first_name + ' ' + d.user_name + '</a></h4>' +
                    editCheck +
                '</div>' +
                '<div class="col-sm-9 col-md-9 col-xs-10 post-entry">' +
                    d.post_text +
                '</div>' +
            '</div>' +
        '</div>';
    };
$(document).ready(function () {
    "use strict";
    CKEDITOR.replace( 'post_text', {
        config: {
            extraPlugins: 'uploadimage',
        }
    } );

    $(document).on('click', '#close_warning', function () {
        if ($(this).children('span').hasClass('glyphicon-chevron-up')) {
            $(this).children('span').removeClass('glyphicon-chevron-up');
            $(this).children('span').addClass('glyphicon-chevron-down');
        } else {
            $(this).children('span').removeClass('glyphicon-chevron-down');
            $(this).children('span').addClass('glyphicon-chevron-up');
        }
        $('#warnings').slideToggle();
    });
    $(document).on('click', '#add_post', function (e) {
        e.preventDefault();
        $('#newstickerNewPost').slideToggle('slow');
        GlobalFunctions.scrollIt('#newsticker', 0, 'slow');
        $('#post_text').val('');
    });

    $(document).on('click', '#cancel_new_post', function (e) {
        e.preventDefault();
        $('#newstickerNewPost').slideUp('slow');
        $('[id^="closeComment_"]').trigger('click');
        $('#post_text').val('');
    });

    $(document).on('click', '[id^="editPost_"]', function (e) {
        e.preventDefault();
        $.ajax({
            type: 'GET',
            url: 'news/getone',
            data: {
                id: $(this).attr('id').split('_')[1]
            },
            success: function (data) {
                GlobalFunctions.unAuthorized(data);
                $('#newstickerNewPost').slideDown('slow').promise().always(function () {
                    window.CKEDITOR.instances.post_text.setData(data.post_text);
                });
                $('#post_text').val(data.post_text);
                $('#id').val(data.id);
            }
        });

    });
    $(document).on('click', '[id^="deletePost_"]', function () {
        var id = $(this).attr('id').split('_')[1];
        $.ajax({
            type: 'POST',
            url: 'news/delete',
            data: {
                id: id
            },
            success: function (data) {
                GlobalFunctions.unAuthorized(data);
                $('#post_' + id).remove();
            }
        });
    });
    $(document).on('click', '#save_new_post', function (e) {
        e.preventDefault();
        $('#newstickerNewPost').slideUp('slow');
        $('#post_' + $('#id').val()).remove();
        $.ajax({
            type: 'POST',
            url: 'news',
            data: {
                post_text: window.CKEDITOR.instances.post_text.getData(),
                id: $('#id').val()
            },
            success: function (n) {
                GlobalFunctions.unAuthorized(n);
                var editCheck = '',
                    str,
                    data = n[0][0],
                    auth = n.auth;
                if (auth === data.uid) {
                    editCheck = '<div class="tools">' +
                        '<span id="editPost_' + data.id + '" class="glyphicon glyphicon-pencil edit"></span>' +
                        '<span id="deletePost_' + data.id + '" class="glyphicon glyphicon-remove edit"></span>' +
                        '</div>';
                }
                str = fillPostContent(data, editCheck);
                $('#newsticker').prepend(str);
            }
        });
    });
});
