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
                    dats = n[0];
                $.each(dats, function (i, data) {
                    editCheck = ($('#editPost_' + data.id).length > 0) ? '<span id="editPost_' + data.id + '" class="glyphicon glyphicon-pencil edit"></span>' : '';
                    str = fillPostContent(data, editCheck);
                    $('#newsticker').append(str);
                });
            }
        });
        window.setTimeout(loadPosts, postLoadTimeOut);
    },
    initTiny = function () {
        "use strict";
        window.tinymce.init({
            theme_advanced_font_sizes: "10px,12px,13px,14px,16px,18px,20px",
            fontsize_formats: "10px 11px 12px 13px 14px 16px 18px 20px",
            selector: 'textarea.editit',
            language: 'de',
            auto_focus: 'post_text',
            menu : {
                edit   : {
                    title : 'Edit',
                    items : 'undo redo | cut copy paste pastetext | selectall'
                },
                insert : {
                    title : 'Insert',
                    items : 'link media | template hr'
                },
                table  : {
                    title : 'Table',
                    items : 'inserttable tableprops deletetable | cell row column'
                },
                tools  : {
                    title : 'Tools',
                    items : 'spellchecker code'
                }
            },
            plugins: 'autoresize emoticons lists table textcolor',
            toolbar: 'insertfile undo redo | fontselect |  fontsizeselect | styleselect | forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l ink image | print preview media fullpage | emoticons',
            content_css: window.urlAssets + '/css/tinymce.css'
        });
    };
jQuery(document).ready(function () {
    "use strict";

    window.setTimeout(loadPosts(), postLoadTimeOut);
    window.setTimeout(function () {
        $('#warnings').slideUp(1000);
    }, postLoadTimeOut / 10);
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
        initTiny();
        if (window.tinyMCE.activeEditor !== null) {
            window.tinyMCE.activeEditor.setContent('');
        }
    });

    jQuery(document).on('click', '#cancel_new_post', function (e) {
        e.preventDefault();
        $('#newstickerNewPost').slideUp('slow');
        $('[id^="closeComment_"]').trigger('click');
        $('#post_text').val('');
    });

    jQuery(document).on('click', '[id^="editPost_"]', function (e) {
        e.preventDefault();
        initTiny();
        $.ajax({
            type: 'GET',
            url: 'news/getone',
            data: {
                id: $(this).attr('id').split('_')[1]
            },
            success: function (data) {
                window.unAuthorized(data);
                $('#newstickerNewPost').slideDown('slow').promise().always(function () {
                    window.tinyMCE.get('post_text').setContent(data.post_text);
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
                $('[id^="closeComment_"]').trigger('click');
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
                post_text: window.tinyMCE.activeEditor.getContent(),
                id: $('#id').val()
            },
            success: function (n) {
                window.unAuthorized(n);
                if (n[0].indexOf('error') > -1) {
                    $('#message').html(n[1]);
                    $('#comments_too_much').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    return false;
                }
                var editCheck,
                    str,
                    data = n[0][0],
                    auth = n.auth;
                editCheck = (auth === data.uid && data.editable) ? '<span id="editPost_' + data.id + '" class="glyphicon glyphicon-pencil edit"></span><span id="deleteComment_' + data.id + '" class="glyphicon glyphicon-remove edit"></span>' : '';
                str = fillPostContent(data, editCheck);
                $('#newsticker').prepend(str);
            }
        });
    });
    jQuery(document).on('click', '[id^="editComment_"]', function () {
        var id = $(this).attr('id').split('_')[1];
        $.ajax({
            type: 'POST',
            url: 'news/getcomment',
            data: {
                id: id
            },
            success: function (n) {
                window.unAuthorized(n);
                $('#addComment_' + n.post_id).trigger('click');
                $('#comment_text_' + n.post_id).val(n.comment_text);
                $('#comment-add-area_' + n.post_id).append('<input id="comment_id_' + n.post_id + '" name="comment_id" type="hidden" value="' + n.id + '">');
            }
        });
    });
});
