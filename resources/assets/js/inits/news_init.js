var fillPostContent =  function(d, editCheck) {
        if ($('#edit').val() === '1') {
            return '<a id="link_post_' + d.id + '"></a>' +
                '<div class="row postrow">' +
                '<div class="col-sm-3 col-md-3 col-xs-12 posts">' +
                d.created_at + '<br><h4><a href="' + window.urlTo + 'user/profile/' + d.user_id + '">' + d.user_first_name + ' ' + d.user_name + '</a></h4>' +
                editCheck +
                '</div>' +
                '<div class="col-sm-9 col-md-9 col-xs-12 post-entry">' +
                d.post_text +
                '</div>' +
                '</div>';
        }
        return '<div id="post_' + d.id + '">' +
            '<a id="link_post_' + d.id + '"></a>' +
            '<div class="row postrow">' +
            '<div class="col-sm-3 col-md-3 col-xs-12 posts">' +
            d.created_at + '<br><h4><a href="' + window.urlTo + 'user/profile/' + d.user_id + '">' + d.user_first_name + ' ' + d.user_name + '</a></h4>' +
            editCheck +
            '</div>' +
            '<div class="col-sm-9 col-md-9 col-xs-12 post-entry">' +
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
    if (window.localStorage.hasOwnProperty('news_hash')) {
        window.location.hash = window.localStorage.getItem('news_hash');
        window.localStorage.removeItem('news_hash');
    }
    $(document).on('click', '#add_post', function (e) {
        e.preventDefault();
        $('#newstickerNewPost').slideToggle('slow');
        GlobalFunctions.scrollIt('#wrap', 'slow');
        $('#post_text').val('');
        $('#edit').val('0');
    });

    $(document).on('click', '#cancel_new_post', function (e) {
        e.preventDefault();
        $('#newstickerNewPost').slideUp('slow');
        $('[id^="closeComment_"]').trigger('click');
        $('#post_text').val('');
        $('#edit').val('0');
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
                GlobalFunctions.scrollIt('#wrap', 'slow');
                $('#newstickerNewPost').slideDown('slow').promise().always(function () {
                    window.CKEDITOR.instances.post_text.setData(data.post_text);
                });
                $('#edit').val('1');
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
        if ($('#edit').val() === '0') {
            $('#post_' + $('#id').val()).remove();
        }
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
                if ($('#edit').val() === '1') {
                    $('#post_' + data.id).html(str);
                    GlobalFunctions.scrollIt('#post_' + data.id, 'slow');
                } else {
                    $('#newsticker').prepend(str);
                }
                $('#confirm_notify_new_post').attr('data-id', data.id);
                $.ajax({
                    url: 'notify_new_post',
                    method: 'POST',
                    data: {
                        post_id: data.id
                    },
                    success: function (data) {
                        let el = $('#notify_new_post'),
                            elHeader = el.find('.modal-header'),
                            elBody = el.find('.modal-body'),
                            elFooter = el.find('.modal-footer'),
                            d = $.parseJSON(data);
                        $('#notify_new_post').modal();
                        if (d.hasOwnProperty('success')) {
                            elBody.html('<p>' + d.success + '</p>');
                            elFooter.html('<button class="btn btn-default btn-dialog-left close" data-dismiss="modal" aria-label="Close">Ok</button>');
                        } else {
                            elHeader
                                .addClass('modal-title-info')
                                .removeClass('modal-title-warning')
                                .html('<h4>Warnung</h4>');
                            elBody.html('<p>' + d.error + '</p>');
                            elFooter.html('<button class="btn btn-default btn-dialog-left close" data-dismiss="modal" aria-label="Close">Ok</button>');
                        }
                        $('#loading').hide();
                    }
                });
            }
        });
    });
    $(document).on('click', '#confirm_notify_new_post', function (e) {
        e.preventDefault();
        let id = $(this).attr('data-id'),
            el = $('#notify_new_post'),
        elHeader = el.find('.modal-header'),
        elBody = el.find('.modal-body'),
        elFooter = el.find('.modal-footer');
        $.ajax({
            url: 'notify_new_post',
            method: 'POST',
            data: {
                post_id: id
            },
            success: function (data) {
                let d = $.parseJSON(data);
                if (d.hasOwnProperty('success')) {
                    elBody.html('<p>' + d.success + '</p>');
                    elFooter.html('<button class="btn btn-default btn-dialog-left close" data-dismiss="modal" aria-label="Close">Ok</button>');
                } else {
                    elHeader
                        .addClass('modal-title-info')
                        .removeClass('modal-title-warning')
                        .html('<h4>Warnung</h4>');
                    elBody.html('<p>' + d.error + '</p>');
                    elFooter.html('<button class="btn btn-default btn-dialog-left close" data-dismiss="modal" aria-label="Close">Ok</button>');
                }
                $('#loading').hide();
            }
        });
    })
});
