/**
 * Created by pc-shooter on 17.12.14.
 */
var urlTop = (window.route.indexOf('admin') > -1) ? '/admin/users/search' : '/userlist_search';

var searchSortPaginate = function (url, search, sortField, orderByField) {
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
            window.unAuthorized(d);
            var userData = $.parseJSON(d);
            console.log(userData)

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
};
var fillUserTable = function (obj) {
    "use strict";
    var hrStr = '',
        trStr = '',
        db,
        showdb = '',
        tbodyel = '#table-body',
        opts = {
            day: 'numeric',
            month: 'numeric',
            year: 'numeric'
        },
        tb =  $('#users').outerWidth();
    $(tbodyel).html('');
    if (obj === undefined || obj.length === 0) {
        $(tbodyel).html('Keine Daten');
        $('#records_no').html(obj.length);
        return false;
    }
    $('#records_no').html(obj.length);
    $.each(obj, function (i, n) {
        var address = (n.user_address === undefined || n.user_address === '') ? ' - ' :  n.user_address,
            zip = (n.user_zip === undefined || n.user_zip === '') ? ' - ' :  n.user_zip,
            city = (n.user_city === undefined || n.user_city === '') ? ' - ' :  n.user_city,
            country = (n.country.country === undefined || n.country.country === '') ? ' - ' :  n.country.country,
            userNew = (n.user_new === '0') ? '<i class="far fa-registered"></i>' : 'neu',
            fonlabelOne = (window.langUser.fonlabel[n.user_fon1_label] === undefined || window.langUser.fonlabel[n.user_fon1_label] === '') ? ' - ' : window.langUser.fonlabel[n.user_fon1_label];
        trStr += '<tr>' +
            '<th><a href="' + window.baseUrl + '/user/profile/' + n.id + '">' +
            '<span class="glyphicon glyphicon-th-list" aria-hidden="true"></span></a></th>';
        if (window.isManager) {
            trStr += '<th><a href="' + window.urlTo + '/admin/users/edit/' + n.id + '">' +
                '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></th>';
            trStr += '<th>' +
                '<a id="destroyUser_' + n.id + '_' + n.user_first_name + '_' + n.user_name + '" href="#"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>' +
                '</th>' +
                '<th>' + userNew + '</th>';
        }
        trStr += '<td>' + n.clans.clan_description + '/<br> ';
        if (n.families !== null) {
            trStr += n.families.family_description;
        }
        trStr += ' </td>' +
            '<td class="firstname_1">' + n.user_first_name + '</td>' +
            '<td class="name_1">' + n.user_name + '</td>' +
            '<td>' + n.user_login_name + '</td>' +
            '<td>' +
            '<ul class="mailz">' +
            '<li>' +
            '<a class="mail_one" href="mailto:' + n.email + '">' + n.email + '</a>' +
            '</li>';
        if (n.user_email2 !== '' && n.user_email2 !== null) {
            trStr += '<li>' +
                '<a href="mailto:' + n.user_email2 + '">' + n.user_email2 + '</a>' +
                '</li>';
        }
        trStr += '</ul>';
        if ((n.user_www !== '' && n.user_www !== null) && (n.user_www_label !== '' && n.user_www_label !== null)) {
            trStr += '<td><a href="https://' + n.user_www + '" target="_blank">' + n.user_www_label + '</a></td>';
        } else {
            trStr += '<td><a href="#"></a></td>';
        }
        trStr += '<td>' + address + '</td>' +
            '<td>' + zip + '</td>' +
            '<td>' + city + '</td>' +
            '<td>' + country + '</td>' +
            '<td>' +
            '<ul class="fonz">' +
            '<li>' + fonlabelOne + '<br>' + n.user_fon1 + '</li>';
        if (n.user_fon2 !== '' && n.user_fon2 !== null) {
            trStr += '<li>' + window.langUser.fonlabel[n.user_fon2_label] + '<br>' + n.user_fon2 + '</li>';
        }
        if (n.user_fon3 !== '' && n.user_fon3 !== null) {
            trStr += '<li>' + window.langUser.fonlabel[n.user_fon3_label] + '<br>' + n.user_fon3 + '</li>';
        }
        trStr += '</ul>';
        db = new Date(n.user_birthday);
        if (!isNaN(db.getTime())) {
            showdb = window.showDate(db, '');
        } else {
            showdb = '-';
        }
        trStr += '<td class="date-header">' + showdb + '</td>' +
            '<td class="date-header">' + n.user_last_login + '</td>' +
            '<td>' +
            '<ul class="rolez">';
        if (n.roles !== undefined) {
            $.each(n.roles, function (i, m) {
                trStr += '<li>' +
                    window.langRole[m.role_code] +
                    '</li>';
            });
        }
    });
    $(tbodyel).html(trStr);
    //window.putUserSearchResultsToSession(urlSaveData, $('#printer').html());
};

$(document).ready(function () {
    "use strict";
    var d = new Date(),
        y = $('#year'),
        m = $('#month');
    window.yl = window.createYearList();
    window.ml = window.createMonthList();
    window.fillSelect(m, window.ml, false);
    window.fillSelect(y, window.yl, true);
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
  //  chk_me(e);
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
