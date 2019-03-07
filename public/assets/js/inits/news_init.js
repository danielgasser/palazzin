/**
 * Created by pc-shooter on 17.12.14.
 */
var postLoadTimeOut = 10 * 60 * 1000,
    comment_viewed = false,
    counter = 0,
    warningTimer,
    fillPostContent =  function(d, editCheck) {
        return '<div id="post_' + d.id + '">' +
            '<div class="row">' +
            '<div class="col-sm-12 col-md-12 posts">' +
            '<h4>' + window.lang.post_from + ' <a href="' + window.urlTo + 'user/profile/' + d.user_id + '">' + d.user_login_name + '</a></h4>' +
            '<p>' + d.created_at + ' ' + editCheck + '</p>' +
            '</div>' +
            '</div>' +
            '<div class="row postrow">' +
            '<div class="col-sm-12 col-md-12 posts">' +
            d.post_text +
            '</div>' +
            '</div>' +
            '</div>';
    },
    loadPosts = function () {
        "use strict";
        $.ajax({
            type: 'GET',
            contentType: "application/x-www-form-urlencoded;charset=utf-8",
            url: 'news_reloaded',
            success: function (n) {
                window.unAuthorized(n);
                if (typeof n === 'string' && n.indexOf('<!DOCTYPE') > -1) {
                    return false;
                }
                var editCheck,
                    str = '',
                    dats = n[0],
                    auth = n.auth;
                $.each(dats, function (i, data) {
                    editCheck = (auth === data.uid) ? '<span id="editPost_' + data.id + '" class="glyphicon glyphicon-pencil edit"></span><span id="deletePost_' + data.id + '" class="glyphicon glyphicon-remove edit"></span>' : '';
                    str = fillPostContent(data, editCheck);
                    $('#newsticker').append(str);
                });
            }
        });
    };
jQuery(document).ready(function () {
    "use strict";

    jQuery(document).on('click', '#close_warning', function () {
        if ($(this).children('span').hasClass('glyphicon-chevron-up')) {
            $(this).children('span').removeClass('glyphicon-chevron-up');
            $(this).children('span').addClass('glyphicon-chevron-down');
        } else {
            $(this).children('span').removeClass('glyphicon-chevron-down');
            $(this).children('span').addClass('glyphicon-chevron-up');
        }
        $('#warnings').slideToggle();
    });
    jQuery(document).on('click', '#add_post', function (e) {
        e.preventDefault();
        $('#newstickerNewPost').slideToggle('slow');
        window.scrollIt('#newsticker', 0, 'slow');
        $('#post_text').val('');
    });

    jQuery(document).on('click', '#cancel_new_post', function (e) {
        e.preventDefault();
        $('#newstickerNewPost').slideUp('slow');
        $('[id^="closeComment_"]').trigger('click');
        $('#post_text').val('');
    });

    jQuery(document).on('click', '[id^="editPost_"]', function (e) {
        e.preventDefault();
        $.ajax({
            type: 'GET',
            url: 'news/getone',
            data: {
                id: $(this).attr('id').split('_')[1]
            },
            success: function (data) {
                window.unAuthorized(data);
                $('#newstickerNewPost').slideDown('slow').promise().always(function () {
                    window.CKEDITOR.instances.post_text.setData(data.post_text);
                });
                $('#post_text').val(data.post_text);
                $('#id').val(data.id);
            }
        });

    });
    jQuery(document).on('click', '[id^="deletePost_"]', function () {
        var id = $(this).attr('id').split('_')[1];
        $.ajax({
            type: 'POST',
            url: 'news/delete',
            data: {
                id: id
            },
            success: function (data) {
                window.unAuthorized(data);
                $('#post_' + id).remove();
            }
        });
    });
    jQuery(document).on('click', '#save_new_post', function (e) {
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
                window.unAuthorized(n);
                var editCheck,
                    str,
                    data = n[0][0],
                    auth = n.auth;
                editCheck = (auth === data.uid) ? '<span id="editPost_' + data.id + '" class="glyphicon glyphicon-pencil edit"></span><span id="deletePost_' + data.id + '" class="glyphicon glyphicon-remove edit"></span>' : '';
                str = fillPostContent(data, editCheck);
                $('#newsticker').prepend(str);
            }
        });
    });
});
