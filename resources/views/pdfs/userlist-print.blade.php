<table cellspacing="0">
    <thead>
        <tr>
            <th style="border-bottom: 1px solid #333333;">{{trans('userdata.user_name')}}, {{trans('userdata.user_first_name')}}</th>
            <th style="border-bottom: 1px solid #333333;">{{trans('userdata.user_login_name')}}</th>
            <th style="border-bottom: 1px solid #333333;">{{trans('userdata.email')}}</th>
            <th style="border-bottom: 1px solid #333333;">{{trans('profile.fons')}}</th>
            <th style="border-bottom: 1px solid #333333;">{{trans('profile.www_label')}}</th>
            <th style="border-bottom: 1px solid #333333;">{{trans('userdata.user_address')}}</th>
            <th style="border-bottom: 1px solid #333333;">{{trans('userdata.user_zip')}}</th>
            <th style="border-bottom: 1px solid #333333;">{{trans('userdata.user_city')}}</th>
            <th style="border-bottom: 1px solid #333333;">{{trans('userdata.user_country_code')}}</th>
            <th style="border-bottom: 1px solid #333333;">{{trans('userdata.birthday')}}</th>
            <th style="border-bottom: 1px solid #333333;">{{trans('userdata.clan')}}</th>
            <th style="border-bottom: 1px solid #333333;">{{trans('userdata.halfclan')}}</th>
            <th style="border-bottom: 1px solid #333333;">{{trans('userdata.roles')}}</th>
        </tr>
    </thead>
    <tbody>
    @foreach($allUsers as $u)
            <tr>
                <td style="border: 1px solid #333333">{{$u['user_first_name']}} {{$u['user_name']}}</td>
                <td style="border: 1px solid #333333">{{$u['user_login_name']}}</td>
                <td style="border: 1px solid #333333">{{$u['email']}}<br>{{$u['user_email2']}}</td>
                <td style="border: 1px solid #333333">{{$u['user_fon1_label']}}:<br>{{$u['user_fon1']}}</td>
                <td style="border: 1px solid #333333"><a href="https://{{$u['user_www']}}">{{$u['user_www']}}</a></td>
                <td style="border: 1px solid #333333">{{$u['user_address']}}</td>
                <td style="border: 1px solid #333333">{{$u['user_zip']}}</td>
                <td style="border: 1px solid #333333">{{$u['user_city']}}</td>
                <td style="border: 1px solid #333333">{{$countries[$u['user_country_code']]}}</td>
                <td style="border: 1px solid #333333">{{$u['user_birthday']}}</td>
                <td style="border: 1px solid #333333">{{$u['clans']['clan_description']}}</td>
                <td style="border: 1px solid #333333">{{$u['families']['family_description']}}</td>
                <td style="border: 1px solid #333333">
                    <ul>
                        @foreach($u['roles'] as $r)
                            <li>{{$r['role_description']}}</li>
                        @endforeach
                    </ul>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
