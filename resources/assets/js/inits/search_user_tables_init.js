let urlTop = (window.route.indexOf('admin') > -1) ? '/admin/users/search' : '/userlist_search',
    dataTableSettings = {
        dataSrc: '',
        responsive: true,
        autoWidth: false,
        fixedHeader: {
            header: true,
            footer: true
        },
        order: [
            1,
            'asc'
        ],
        columnDefs: [
            {
                targets: [0],
                responsivePriority: 1,
                class: 'details',
                visible: true,
                orderable:      false,
                data:           null,
                defaultContent: ''
            },
            {
                targets: [1],
                responsivePriority: 1,
                visible: true,
                orderable:      false,
                sortable: false,
                className: 'admin_edit',
                render: function (data, type, row, meta) {
                    if (window.isManager) {
                        return '<a href="' + urlTo + '/admin/users/edit/' + row.user_id + '"><i class="fas fa-cog"></i></a>';
                    }
                    return '';
                }

            },
            {
                targets: [2],
                responsivePriority: 1,
                visible: false,
                orderable:      false,
                sortable: false,
                className: 'user_id',
                data: 'user_id'
            },
            {
                targets: [3],
                responsivePriority: 2,
                data: 'user_first_name'
            },
            {
                targets: [4],
                responsivePriority: 4,
                data: 'user_name'
            },
            {
                targets: [5],
                responsivePriority: 5,
                data: 'user_login_name',
                render: function (data, type, row, meta) {
                    return '<a href="' + urlTo + '/user/profile/' + row.user_id + '">' + data + '</a>';
                }
            },
            {
                targets: [6],
                responsivePriority: 6,
                data: 'email',
                render: function (data, type, row, meta) {
                    let html = '<ul style="list-style-type: none; margin: 0; padding: 0;">';
                    html += '<li class="mail_one"><a href="mailto:' + data + '">' + data + '</a></li>';
                    if (row.user_email2 === undefined || row.user_email2 === null) {
                        html += '</ul>';
                    } else if (row.user_email2.length > 0) {
                        html += '<li><a href="mailto:' + row.user_email2 + '">' + row.user_email2 + '</a></li>';
                    }
                    return html;
                }
            },
            {
                targets: [7],
                responsivePriority: 3,
                data: 'user_fon1',
                render: function (data, type, row, meta) {
                    let tel = data.replace(/^0+/, '');
                    tel = tel.replace(/ /g,'');
                    tel = tel.replace('+41', '');
                    return '<a href="tel:+' + row.user_country_code + tel + '">' + data + '</a>'
                }
            },
            {
                targets: [8],
                responsivePriority: 24,
                data: 'user_www_label',
                render: function (data, type, row, meta) {
                    if (row.user_www === undefined || row.user_www === null) {
                        return '';
                    }
                    if (row.user_www.length > 0) {
                        return '<a href="https://' + row.user_www + '">' + data + '</a>';
                    }
                    return '';
                }
            },
            {
                targets: [9],
                responsivePriority: 8,
                data: 'user_address'
            },
            {
                targets: [10],
                responsivePriority: 8,
                data: 'user_zip'
            },
            {
                targets: [11],
                responsivePriority: 8,
                data: 'user_city'
            },
            {
                targets: [12],
                responsivePriority: 8,
                data: 'user_country_code',
                render: function (data, type, row, meta) {
                    if (row.country !== undefined) {
                        return row.country.country
                    }
                    return '-';
                }
            },
            {
                targets: [13],
                responsivePriority: 24,
                data: 'user_birthday'
            },
            {
                targets: [14],
                responsivePriority: 24,
                data: 'clans',
                render: function (data, type, row, meta) {
                    if (data === '' || data == null) {
                        return '';
                    }
                    return data.clan_description;
                }
            },
            {
                targets: [15],
                responsivePriority: 24,
                data: 'families',
                render: function (data, type, row, meta) {
                    if (data === '' || data == null) {
                        return '';
                    }
                    return data.family_description;

                }
            },
            {
                targets: [16],
                responsivePriority: 24,
                data: 'roles',
                render: function (data) {
                    let html = '<ul>';
                    $.each(data, function (i, n) {
                        html += '<li>' + n.role_description + '</li>';
                    });
                    html += '</ul>';
                    return html;
                }
            },

            {
                targets: [17],
                responsivePriority: 25,
                data: 'last_login',
                visible: false
            },
        ],
        searching: false,
        language: {
            paginate: {
                first: window.paginationLang.first,
                previous: window.paginationLang.previous,
                next: window.paginationLang.next,
                last: window.paginationLang.last
            },
            info: window.paginationLang.info,
            sLengthMenu: window.paginationLang.length_menu
        },
        fnDrawCallback: function () {
        },
        lengthChange: false
    },
    searchSortPaginate = function (url, search, sortField, orderByField) {
        "use strict";
        search = {
            search_user: $('#search_user').val(),
            clan_search: $('#clan_search').val(),
            family_search: $('#family_search').val(),
            role_search: $('#role_search').val()
        };
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                search_field: search.search_user,
                sort_field: sortField,
                order_by: orderByField,
                family: search.family_search,
                clan: search.clan_search,
                role: search.role_search,
                user_id: $('#user_id').val()
            },
            success: function (d) {
                GlobalFunctions.unAuthorized(d);
                let userData = $.parseJSON(d);

                userTable.clear();
                userTable.rows.add(userData)
                userTable.draw();
                let uIds = [];
                $.each(userData, function (i, n) {
                    if (n.user_id !== '') {
                        uIds.push(n.user_id);
                    }
                })
                $('#uIDs').val(uIds.join(','))
            }
        });
    },
    userTable;
$(document).ready(function () {
    "use strict";
    userTable = $('#users').DataTable(dataTableSettings);
    $('#print_name_pdf').val('');
    $('#print_table_name_message').html('');
    let d = new Date(),
        y = $('#year'),
        m = $('#month');
    window.yl = GlobalFunctions.createYearList();
    window.ml = GlobalFunctions.createMonthList();
    GlobalFunctions.fillSelect(m, window.ml, false);
    GlobalFunctions.fillSelect(y, window.yl, true);
    y.val(d.getFullYear());
    m.val(d.getMonth());
    //window.putUserSearchResultsToSession(urlSaveData, $('#printer').html());
    $('#newsMessage').hide();
    userTable.clear();
    userTable.rows.add(window.allUsers);
    userTable.draw();
    let uIds = [];
    $.each(window.allUsers, function (i, n) {
        if (n.user_id !== '') {
            uIds.push(n.user_id);
        }
    })
    $('#uIDs').val(uIds.join(','))
});
$('#users tbody').on('click', 'td.00', function () {
    let tr = $(this).closest('tr'),
        row = userTable.row(tr);

    if (row.child.isShown()) {
        row.child.hide();
        tr.removeClass('shown');
    } else {
        let theDesc = row.data();
        row.child(theDesc).show();
        tr.addClass('shown');
    }
});

let timer,
    chk_me = function(e) {
        clearTimeout(timer);
        timer = setTimeout(function () {
            searchIt(e);
        }, 500);
    },
    search = {
        search_user: $('#search_user').val(),
        clan_search: $('#clan_search').val(),
        family_search: $('#family_search').val(),
        role_search: $('#role_search').val()
    },
    searchIt = function (e) {
        let sl,
            field,
            sortby;
        $.each(search, function (i, n) {
            if (n == null) {
                search[i] = '';
            }
        });
        if (search.search_user.length < 3 && search.clan_search.length === 0 && search.family_search.length === 0 && search.role_search.length === 0) {
            return false;
        }
        if ((e.target.hasOwnProperty('config'))) {
            sl = e.target.config.sortList[0];
            field = $(window.cols[sl[0]]).attr('id');
            sortby = (sl[1] === 1) ? 'ASC' : 'DESC';
        } else {
            field = 'user_name';
            sortby = 'ASC';
        }
        searchSortPaginate(urlTop, search, field, sortby);
    };
$(document).on('change', '#clan_search', function (e) {
    "use strict";
    let famOpts = window.families,
        famOptsVal = [];
    $.each(famOpts, function (i, n) {
        if (i !== '') {
            famOptsVal.push({
                val: i,
                text: n
            })
        }
    });
    $('#family_search')
        .find('option')
        .remove()
        .end();

    $.each(famOptsVal, function (i, n) {
        if (n.val.split('|')[1] === $('#clan_search').val()) {
            $('#family_search')
                .append('<option value="' + n.val + '">' + n.text + '</option>')
                .val('')
            ;
        }
    });
    chk_me(e);
});
$(document).on('keyup', '#search_user', function (e) {
    "use strict";
    if (this.value.length < 3) return false;
    chk_me(e);
});
$(document).on('change', '#family_search, #role_search', function (e) {
    "use strict";
    e.preventDefault();
    chk_me(e);
});
$(document).on('submit', 'form:not(#sendToPrint)', function (e) {
    "use strict";
    e.preventDefault();
   return false;
});
$(document).on('click', '#goSearch', function (e) {
    e.preventDefault();
    chk_me(e);
});
$(document).on('click', '#printChoice', function (e) {
    e.preventDefault();
    $('#print_table_name').modal();
});
$(document).on('click', '#dload', function (e) {
    e.preventDefault();
    $('#print_table_name').modal('hide');
    window.open(
        $(this).attr('href'),
        '_blank'
    );
});
$(document).on('click', '#sendToPrintSubmit', function (e) {
    e.preventDefault();
    let data = {
        userIds: $('#uIDs').val(),
        search_user: $('#search_user').val(),
        clan_search: $('#clan_search').val(),
        family_search: $('#family_search').val(),
        role_search: $('#role_search').val(),
        sort_field: $('#sort_field').val(),
        order_by: $('#order_by').val(),
        print_name_pdf: $('#print_name_pdf').val()
    };
    $('#sendToPrintSubmit').attr('disabled', true);
    $.ajax({
        url: window.userListPrintUrl,
        method: 'POST',
        data: data,
        success: function (d) {
            $('#sendToPrintSubmit').attr('disabled', false);
            let data = JSON.parse(d),
            el = $('#print_table_name_message');
            if (data.hasOwnProperty('success')) {
                $('#print_table_name_text').html('');
                el.removeClass('alert-success').removeClass('alert-danger').html('Download: <a id="dload" target="_blank" href="' + data.success + '">' + data.pdf_name + '</a>');
                return false;
            }
        }
    })
});
