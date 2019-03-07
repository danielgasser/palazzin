/**
 * Created by pc-shooter on 17.12.14.
 */
var urlTop = (window.route.indexOf('admin') > -1) ? '/admin/users/search' : '/userlist_search',
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
                orderable:      false,
                sortable: false,
                className: 'user_id',
                data: 'user_id'
            },
            {
                targets: [2],
                responsivePriority: 2,
                data: 'user_first_name'
            },
            {
                targets: [3],
                responsivePriority: 4,
                data: 'user_name'
            },
            {
                targets: [4],
                responsivePriority: 5,
                data: 'user_login_name',
                render: function (data, type, row, meta) {
                    if (window.isManager) {
                        return '<a href="' + urlTo + '/admin/users/edit/' + row.user_id + '">' + data + '</a>';
                    }
                    return '<a href="' + urlTo + '/user/profile/' + row.user_id + '">' + data + '</a>';
                }
            },
            {
                targets: [5],
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
                targets: [6],
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
                targets: [7],
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
                targets: [8],
                responsivePriority: 8,
                data: 'user_address'
            },
            {
                targets: [9],
                responsivePriority: 8,
                data: 'user_zip'
            },
            {
                targets: [10],
                responsivePriority: 8,
                data: 'user_city'
            },
            {
                targets: [11],
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
                targets: [12],
                responsivePriority: 24,
                data: 'user_birthday'
            },
            {
                targets: [13],
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
                targets: [14],
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
                targets: [15],
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
                targets: [16],
                responsivePriority: 24,
                data: 'last_login'
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
                var userData = $.parseJSON(d);

                window.userTable.clear();
                window.userTable.rows.add(userData)
                window.userTable.draw();
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
    $('#users tbody').on('click', 'td.00', function () {
        var tr = $(this).closest('tr');
        var row = userTable.row(tr);

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
        } else {
            var theDesc = row.data();
            row.child(theDesc).show();
            tr.addClass('shown');
        }
    } );
    var d = new Date(),
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
    searchSortPaginate(urlTop, search, 'user_name', 'ASC');
});

var timer;
var chk_me = function(e) {
    clearTimeout(timer);
    timer = setTimeout(function () {
        searchIt(e);
    }, 500);
};
var search = {
    search_user: $('#search_user').val(),
    clan_search: $('#clan_search').val(),
    family_search: $('#family_search').val(),
    role_search: $('#role_search').val()
};

var searchIt = function (e) {
    var sl,
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
    var famOpts = window.families,
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
    $('#sendToPrint').submit();
});
