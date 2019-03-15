<table>
    <thead>
        <tr>
            <th>{{trans('userdata.user_name')}}, {{trans('userdata.user_first_name')}}</th>
            <th>{{trans('userdata.user_login_name')}}</th>
            <th>{{trans('userdata.email')}}</th>
            <th>{{trans('profile.fons')}}</th>
            <th>{{trans('profile.www_label')}}</th>
            <th>{{trans('userdata.user_address')}}</th>
            <th>{{trans('userdata.user_zip')}}</th>
            <th>{{trans('userdata.user_city')}}</th>
            <th>{{trans('userdata.user_country_code')}}</th>
            <th>{{trans('userdata.birthday')}}</th>
            <th>{{trans('userdata.clan')}}</th>
            <th>{{trans('userdata.halfclan')}}</th>
            <th>{{trans('userdata.roles')}}</th>
            <th>{{trans('userdata.user_last_login')}}</th>
        </tr>
    </thead>
    <tbody>
    @foreach($allUsers as $u)
        @php
        $birthDay = '';
        if (isset($u->user_birthday)) {
            $c = explode(' ', $u->user_birthday);
            $b = explode('-', $c[0]);
            $birthDay = $b[2] . '.' . $b[1] . ' ' . $b[0];
        }
        if (isset($u->family_code)) {
            $fam = $families[$u->family_code];
        } else {
            $fam = '';
        }
        @endphp
            <tr>
                <td>{{$u->user_first_name}} {{$u->user_name}}</td>
                <td>{{$u->user_login_name}}</td>
                <td>{{$u->email}}<br>{{$u->user_email2}}</td>
                <td>{{$u->user_fon1_label}}: {{$u->user_fon1}}</td>
                <td><a href="https://{{$u->user_www}}">{{$u->user_www}}</a></td>
                <td>{{$u->user_address}}</td>
                <td>{{$u->user_zip}}</td>
                <td>{{$u->user_city}}</td>
                <td>{{$countries[$u->user_country_code]}}</td>
                <td>{{$birthDay}}</td>
                <td>{{$clans[$u->clan_id]}}</td>
                <td>{{$fam}}</td>
                <td>
                    <ul>
                        @foreach($u->roles as $r)
                            <li>{{$r->role_description}}</li>
                        @endforeach
                    </ul>
                </td>
                <td>{{$u->user_last_login}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
