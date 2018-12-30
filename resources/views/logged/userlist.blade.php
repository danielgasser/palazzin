@extends('layout.master')
@section('header')
    @parent
    <link rel="stylesheet" type="text/css" href="{!!asset('assets/css')!!}/datatables_roomapp.css"/>
    <link rel="stylesheet" type="text/css" href="{!!asset('assets/js/v3')!!}/DataTables/datatables.min.css"/>

    <script type="text/javascript" src="{!!asset('assets/js/v3')!!}/DataTables/datatables.min.js"></script>
@stop
@section('content')
    <div class="row">
        {!!Form::open(array('url' => 'userlist', 'class' => 'form-inline', 'style' => 'margin-bottom: 2em', 'role'=> 'form', 'method' => 'post'))!!}
        <div class="col-sm-3 col-md-3">
            {!!Form::label('search_user', "Volltextsuche")!!}
            {!!Form::text('search_user', Input::old('search_user'), array('class' => 'form-control', 'id' => 'search_user', 'placeholder' => trans('admin.user.etc')))!!}
        </div>
        {{-- clan --}}
        <div class="col-sm-3 col-md-3">
            {!!Form::label('clan_search', trans('userdata.clan'))!!}
            {!!Form::select('clan_search', $clans, Input::old('clan_search'), array('class' => 'form-control'))!!}
        </div>
        <div class="col-sm-3 col-md-3">
            {!!Form::label('family_search', trans('userdata.halfclan'))!!}
            {!!Form::select('family_search', $families, Input::old('family_search'), array('class' => 'form-control'))!!}
        </div>
        <div class="col-sm-3 col-md-3">
            {!!Form::label('role_search', trans('userdata.roles'))!!}
            {!!Form::select('role_search', $roleList, Input::old('role_search'), array('class' => 'form-control'))!!}
        </div>
        <div class="col-sm-12 col-md-12">
            <a href="{!!URL::to(Request::url())!!}" class="btn btn-default">{!!trans('dialog.all')!!}</a>
            <a href="{!!URL::to('userlist/print')!!}" class="btn btn-default">{!!trans('dialog.choice')!!} {!!trans('dialog.print')!!}</a>

            <div id="sendMessage" class="btn btn-default">{!!trans('message.send_message')!!}</div>
        </div>
        {!!Form::close()!!}
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            @include('message.new_message')
        </div>
    </div>
    <div id="printer">
        <table id="users">
            <thead>
                <tr>
                    <th class="00" id="more"></th>
                    <th class="2" id="user_first_name">{!!trans('userdata.user_first_name')!!}</th>
                    <th class="3" id="user_name">{!!trans('userdata.user_name')!!}</th>
                    <th class="4" id="user_login_name">{!!trans('userdata.user_login_name')!!}</th>
                    <th class="5" id="email">{!!trans('userdata.email')!!}</th>
                    <th class="11" id="user_fon" class="fon-header">{!!trans('profile.fons')!!}</th>
                    <th class="6" id="user_www_label">{!!trans('profile.www_label')!!}</th>
                    <th class="7" id="user_address">{!!trans('userdata.user_address')!!}</th>
                    <th class="8" id="user_zip">{!!trans('userdata.user_zip')!!}</th>
                    <th class="9" id="user_city">{!!trans('userdata.user_city')!!}</th>
                    <th class="10" id="user_country_code">{!!trans('userdata.user_country_code')!!}</th>
                    <th class="12" id="user_birthday" class="date-header">{!!trans('userdata.birthday')!!}</th>
                    <th class="0" id="clan_id">{!!trans('userdata.clan')!!}</th>
                    <th class="1" id="family_id">{!!trans('userdata.halfclan')!!}</th>
                    <th class="14" id="user_role">{!!trans('userdata.roles')!!}</th>
                    <th class="13" id="user_last_login" class="date-header">{!!trans('userdata.user_last_login')!!}</th>
                </tr>
            </thead>
            <tbody id="table-body">
                    <tr>
                        <td class="00"></td>
                        <td class="0"></td>
                        <td class="1"></td>
                        <td class="2"></td>
                        <td class="3"></td>
                        <td class="4"></td>
                        <td class="5"></td>
                        <td class="6"></td>
                        <td class="7"></td>
                        <td class="8"></td>
                        <td class="9"></td>
                        <td class="10"></td>
                        <td class="11"></td>
                        <td class="12"></td>
                        <td class="13"></td>
                        <td class="14"></td>
                    </tr>
            </tbody>
        </table>
    </div>
<div id="debug"></div>
    @include('logged.dialog.user_delete')
    @include('logged.dialog.messagesent')
    @include('logged.dialog.fourchar')
    @section('scripts')
    @parent
    <script src="{!!asset('assets/js/inits/userlist_init.js')!!}"></script>
        <script>
            var ss = 'ASC',
                    a = '#allReservations',
                    locale = '{!!Lang::get('formats.langlangjs')!!}',
                    langDialog = '{!!json_encode(Lang::get('dialog'))!!}',
                    langUser = JSON.parse('{!!json_encode(array_merge(Lang::get('userdata'), Lang::get('profile')))!!}'),
                    langRole = JSON.parse('{!!json_encode(Lang::get('roles'))!!}'),
                    cols = $('th'),
                    yl = [],
                    families = JSON.parse('{!!json_encode($families)!!}'),
                    ml = [],
                    baseUrl = '{!! URL::to('/') !!}',
            isManager = ('{!! User::isManager() || User::isLoggedAdmin() !!}' === '1'),
            userTable,
            dataTableSettings = {
                dataSrc: '',
                responsive: true,
                autoWidth: true,
                sScrollY: "35em",
                sScrollX: "100%",
                sScrollXInner: "100%",
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
                        "orderable":      false,
                        "data":           null,
                        "defaultContent": ''
                    },
                    {
                        targets: [1],
                        responsivePriority: 2,
                        data: 'user_first_name'
                    },
                    {
                        targets: [2],
                        responsivePriority: 4,
                        data: 'user_name'
                    },
                    {
                        targets: [3],
                        responsivePriority: 5,
                        data: 'user_login_name'
                    },
                    {
                        targets: [4],
                        responsivePriority: 6,
                        data: 'email',
                        render: function (data, type, row, meta) {
                            let html = '<ul>';
                            html += '<li><a href="mailto:' + data + '">' + data + '</a></li>';
                            if (row.user_email2 === undefined || row.user_email2 === null) {
                                html += '</ul>';
                            } else if (row.user_email2.length > 0) {
                                html += '<li><a href="mailto:' + row.user_email2 + '">' + row.user_email2 + '</a></li>';
                            }
                            return html;
                        }
                    },
                    {
                        targets: [5],
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
                        targets: [6],
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
                        targets: [7],
                        responsivePriority: 8,
                        data: 'user_address'
                    },
                    {
                        targets: [8],
                        responsivePriority: 8,
                        data: 'user_zip'
                    },
                    {
                        targets: [9],
                        responsivePriority: 8,
                        data: 'user_city'
                    },
                    {
                        targets: [10],
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
                        targets: [11],
                        responsivePriority: 24,
                        data: 'user_birthday'
                    },
                    {
                        targets: [12],
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
                        targets: [13],
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
                        targets: [14],
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
                        targets: [15],
                        responsivePriority: 24,
                        data: 'last_login'
                    },
                ],
                searching: false,
                language: {
                    paginate: {
                        first: '{!!trans('pagination.first')!!}',
                        previous: '{!!trans('pagination.previous')!!}',
                        next: '{!!trans('pagination.next')!!}',
                        last: '{!!trans('pagination.last')!!}'
                    },
                    info: '{!!trans('pagination.info')!!}',
                    sLengthMenu: '{!!trans('pagination.length_menu')!!}'
                },
                fnDrawCallback: function () {
                }
            };
        </script>
    <script src="{!!asset('assets/js/inits/search_user_tables_init.js')!!}"></script>
    <script>
        $(document).ready( function () {
            userTable = $('#users').DataTable(dataTableSettings);
            setTimeout(function () {
                userTable.columns.adjust();
            }, 770)
            $(window).resize( function () {
                userTable.columns.adjust();
            } );
            $('#users tbody').on('click', 'td.00', function () {
                var tr = $(this).closest('tr');
                var row = userTable.row(tr);

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    var theDesc = row.data();
                    row.child(theDesc).show();
                    tr.addClass('shown');
                }
            } );
        });
        </script>
    @stop

@stop
