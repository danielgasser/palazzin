/**
 * Created by pc-shooter on 17.12.14.
 */
var postLoadTimeOut = 10 * 60 * 1000,
    comment_viewed = false,
    counter = 0,
    warningTimer,
    loadPosts = function () {
        "use strict";
        $.ajax({
            type: 'GET',
            contentType: "application/x-www-form-urlencoded;charset=utf-8",
            url: 'news_reloaded',
            success: function (n) {
                if (typeof n === 'string' && n.indexOf('<!DOCTYPE') > -1) {
                    return false;
                }
                var editCheck,
                    str = '',
                    dats = n[0],
                    auth = n.auth;
                $.each(dats, function (i, data) {
                    editCheck = ($('#editPost_' + data.id).length > 0) ? '<span id="editPost_' + data.id + '" class="glyphicon glyphicon-pencil edit"></span><span id="deleteComment_' + data.id + '" class="glyphicon glyphicon-remove edit"></span>' : '';
                    str += '<div class="row">' +
                        '<div class="col-sm-12 col-md-12 posts">' +
                        '<h4>' + window.lang.post_from + ' <a href="' + window.urlTo + 'user/profile/' + data.user_id + '">' + data.user_login_name + '</a></h4>' +
                        '<p>' + data.created_at + ' ' + editCheck + '</p>' +
                        '</div>' +
                        '</div>' +
                        '<div class="row postrow">' +
                        '<div class="col-sm-12 col-md-12 posts">' +
                        data.post_text +
                        '</div>' +
                        '<div class="col-sm-12 col-md-12 comments">';
                    if (data.comments.length > 0) {
                        str +=  '<h4 class="comments-titles">' + window.lang.comments_title + '</h4>';
                    }

                    str +=  '<ul id="comments_' + data.id + '">';
                    $.each(data.comments, function (j, comm) {
                        str += '<li id="commentId_' + comm.id + '">' + window.lang.comment_from + ' <a href="' + window.urlTo + 'user/profile/' + comm.user_id + '">' + comm.user_login_name + '</a> ' + window.lang.comment_at + ' ' + comm.created_at;
                        if (auth === comm.user_id && comm.editable === '1') {
                            str += '<span id="editComment_' + comm.id + '" class="glyphicon glyphicon-pencil edit"></span><span id="deleteComment_' + comm.id + '" class="glyphicon glyphicon-remove edit"></span>';
                        }
                        str += '<br>"' + comm.comment_text + '"</li>';
                    });
                    str += '</ul>' +
                        '</div>' +
                        '<div class="col-sm-12 col-md-12 comments">' +
                        '<a href="#" id="addComment_' + data.id + '">' + window.lang.add_comment + '</a>' +
                        '</div>' +
                        '<div class="col-sm-12 col-md-4 comments" id="comment-add-area_' + data.id + '">' +
                        '</div>' +
                        '<div class="col-sm-12 col-md-2 comments" id="comment-add_' + data.id + '">' +
                        '</div>' +
                        '<div class="col-sm-12 col-md-6 comments">' +
                        '</div>' +
                        '</div>';
                });
                $('#newsticker').html(str);
            }
        });
        window.setTimeout(loadPosts, postLoadTimeOut);
    },
    appendComment = function (data, postId, el) {
        "use strict";
        if (data.comments.length > 1) {
            $(el + postId).html('');
        }
        $.each(data.comments, function (i, n) {
            var editCheck = (data.auth === n.user_id && n.editable) ? '<span id="editComment_' + n.id + '" class="glyphicon glyphicon-pencil edit"></span><span id="deleteComment_' + n.id + '" class="glyphicon glyphicon-remove edit"></span>' : '';
            if ($('#commentId_' + n.id).length > 0) {
                $('#commentId_' + n.id).remove();
            }
            $('#comments_' + postId).append('<li id="commentId_' + n.id + '">' + editCheck + window.lang.comment_from + ' <a href="' + window.urlTo + '/user/profile/' + n.user_id + '">' +
                n.user_login_name + '</a> ' + window.lang.comment_at + ' ' + n.created_at +
                '<br>"' + n.comment_text + '"</li>');
        });
        $('[id^="closeComment_"]').trigger('click');
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
    },
    toggleWarning = function () {
        "use strict";
        var input = $('#counter'),
            cc = window.parseInt(input.val(), 10);
        $('.ach').toggle();
        cc += 1;
        input.val(cc);
        if (cc > 6) {
            window.clearTimeout(warningTimer);
            $('.ach').show();
            return;
        }
        warningTimer = window.setTimeout(toggleWarning, 1000);
    };
jQuery(document).ready(function () {
    "use strict";
   // toggleWarning();

    window.setTimeout(loadPosts(), postLoadTimeOut);
    window.setTimeout(function () {
        $('#warnings').slideUp(1000);
    }, postLoadTimeOut / 10);
    if (!window.localStorage.hasOwnProperty('comment_viewed')) {
        if (window.new_comment !== '' && window.new_comment_user_id !== window.autid) {
            $('#new_comment_available').modal({
                backdrop: 'static',
                keyboard: false
            });
            $('#commentId_' + window.new_comment).addClass('new_comment_available');
        }
    }
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
    jQuery(document).on('click', '#cancel_new_comment', function () {
        window.localStorage.setItem('comment_viewed', 'true');
        window.scrollIt('body, html, #newsticker', ($('#commentId_' + window.new_comment).offset().top + $('#commentId_' + window.new_comment).height()), 'slow');
        window.setTimeout(function () {
            $('#commentId_' + window.new_comment).removeClass('new_comment_available');
        }, 1500);
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
                $('#newstickerNewPost').slideDown('slow').promise().always(function () {
                    window.tinyMCE.get('post_text').setContent(data.post_text);
                });
                $('#post_text').val(data.post_text);
                $('#id').val(data.id);
            }
        });

    });
    jQuery(document).on('click', '[id^="moreComments_"]', function () {
        var postId = $(this).attr('id').split('_')[1],
            data_no = $(this).attr('data_no');
        if ($(this).attr('data_less') === 'true') {
            $.each($('[id^="comments_"]>li'), function (i, n) {
                if (i > 2) {
                    $(n).remove();
                }
            });
            $(this)
                .attr('data_less', false)
                .text(window.lang.show_ + ' ' + (data_no - 3) + ' ' + window.lang.show_more_comments);
            return false;
        }
        $.ajax({
            type: 'GET',
            url: 'news/getcomments',
            data: {
                id: postId
            },
            success: function (data) {
                appendComment(data, postId, '#comments_');
            }
        });
        $(this)
            .attr('data_less', true)
            .text(window.lang.show_less_comments);
    });
    jQuery(document).on('click', '[id^="deletePost_"]', function () {
        var id = $(this).attr('id').split('_')[1];
        $.ajax({
            type: 'POST',
            url: 'news/delete',
            data: {
                id: id
            },
            success: function () {
                $('#post_' + id).remove();
                $('[id^="closeComment_"]').trigger('click');
            }
        });
    });

    jQuery(document).on('click', '[id^="deleteComment_"]', function () {
        var id = $(this).attr('id').split('_')[1];
        $.ajax({
            type: 'GET',
            url: 'news/deletecomment',
            data: {
                id: id
            },
            success: function () {
                $('#commentId_' + id).remove();
                $('[id^="closeComment_"]').trigger('click');
            }
        });
    });

    jQuery(document).on('click', '[id^="closeComment_"]', function () {
        var id = $(this).attr('id').split('_')[1];
        $('#comment_text_' + id + ', #comment_id_' + id + ', #saveComment_' + id + ', #closeComment_' + id).remove();
    });

    jQuery(document).on('click', '[id^="addComment_"]', function () {
        var id = $(this).attr('id').split('_')[1];
        $('[id^="comment_text_"], [id^="comment_id_"], [id^="saveComment_"], [id^="closeComment_"]').remove();
        $('#comment-add-area_' + id)
            .html('<textarea class="form-control" id="comment_text_' + id + '" name="comment_text" /></textarea>');
        $('#comment-add_' + id)
            .html('<span id="saveComment_' + id + '" class="glyphicon glyphicon-ok edit"></span><br><span id="closeComment_' + id + '" class="glyphicon glyphicon-remove edit"></span>');
        $('#comment_text_' + id).focus();
        window.scrollIt('body, html', ($('#addComment_' + id).offset().top + $('#addComment_' + id).height()), 'slow');
    });
    jQuery(document).on('click', '[id^="saveComment_"]', function () {
        var id = $(this).attr('id').split('_')[1];
        $.ajax({
            type: 'POST',
            url: 'news/addcomment',
            data: {
                post_id: id,
                comment_text: $('#comment_text_' + id).val(),
                comment_id: $('#comment_id_' + id).val()
            },
            success: function (n) {
                if (n.hasOwnProperty('error')) {
                    $('#message').html(n.error);
                    $('#comments_too_much').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $('[id^="closeComment_"]').trigger('click');
                    return false;
                }
                appendComment(n, id, '#comments_');
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
                if (n[0].indexOf('error') > -1) {
                    $('#message').html(n[1]);
                    $('#comments_too_much').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    return false;
                }
                var editCheck,
                    data = n[0][0],
                    auth = n.auth;
                editCheck = (auth === data.uid && data.editable) ? '<span id="editPost_' + data.id + '" class="glyphicon glyphicon-pencil edit"></span><span id="deleteComment_' + data.id + '" class="glyphicon glyphicon-remove edit"></span>' : '';
                $('#newsticker').prepend('<div id="post_' + data.id + '">' +
                    '<div class="row">' +
                        '<div class="col-sm-12 col-md-12 posts">' +
                            '<h4>' + window.lang.post_from + ' <a href="' + window.urlTo + 'user/profile/' + data.user_id + '">' + data.user_login_name + '</a></h4>' +
                            '<p>' + data.created_at + ' ' + editCheck + '</p>' +
                        '</div>' +
                    '</div>' +
                    '<div class="row postrow">' +
                        '<div class="col-sm-12 col-md-12 posts">' +
                                data.post_text +
                        '</div>' +
                        '<div class="col-sm-12 col-md-12 comments">' +
                            '<ul id="comments_' + data.id + '">' +
                            '</ul>' +
                        '</div>' +
                        '<div class="col-sm-12 col-md-12 comments">' +
                            '<a href="#" id="addComment_' + data.id + '">' + window.lang.add_comment + '</a>' +
                        '</div>' +
                        '<div class="col-sm-12 col-md-4 comments" id="comment-add-area_' + data.id + '">' +
                        '</div>' +
                        '<div class="col-sm-12 col-md-2 comments" id="comment-add_' + data.id + '">' +
                        '</div>' +
                        '<div class="col-sm-12 col-md-6 comments">' +
                        '</div>' +
                    '</div>');
            }
        });
    });
    jQuery(document).on('click', '[id^="editComment_"]', function () {
        var id = $(this).attr('id').split('_')[1];
        $.ajax({
            type: 'GET',
            url: 'news/getcomment',
            data: {
                id: id
            },
            success: function (n) {
                $('#addComment_' + n.post_id).trigger('click');
                $('#comment_text_' + n.post_id).val(n.comment_text);
                $('#comment-add-area_' + n.post_id).append('<input id="comment_id_' + n.post_id + '" name="comment_id" type="hidden" value="' + n.id + '">');
            }
        });
    });
});
