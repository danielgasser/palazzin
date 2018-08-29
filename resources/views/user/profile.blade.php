@extends('layout.master')
@section('content')

<div class="row">
    <div class="col-sm-12 col-md-6">
 @if($disabledForm == '')
       <h1>{!!trans('profile.title', array(
            'first_name' => User::find(Auth::id())->user_first_name,
            'name' => User::find(Auth::id())->user_name,
            'login_name' => User::find(Auth::id())->user_login_name,
            'id' => User::find(Auth::id())->id,
            'files'=>true))!!}
            </h1>
@else
        <h1>{!!trans('profile.title', array(
             'first_name' => $user->user_first_name,
             'name' => $user->user_name,
             'login_name' => $user->user_login_name,
             'id' => $user->id,
             'files'=>true))!!}
             </h1>
@endif   </div>
</div>
    {!!Form::model($user, array('url' => 'user/profile', 'class' => '', 'id' => 'userProfile', 'files' => true))!!}
        {!!Form::hidden('id', $user->id)!!}
        @if($disabledForm == '' && $user->user_new == 1)
       <fieldset>
            <legend>{!!trans('profile.info')!!}</legend>
             <div class="row">
                  <div class="col-sm-1 col-md-1"></div>
                  <div class="col-sm-10 col-md-10{!!$requIred[0]!!}ired">
                      {!!trans('userdata.profile_text')!!} <img class="smile" src="{!!asset('assets/img/smile_frech.png')!!}" alt="Zwinker" title="Zwinker">
                  </div>
                  <div class="col-sm-1 col-md-1"></div>
             </div>
       </fieldset>
       @endif
       <fieldset>
         <legend>{!!trans('profile.names')!!}</legend>
             <div class="row">
                {{-- name --}}
                {!!Form::label('user_name', trans('userdata.user_name'), array('class' => 'col-sm-2 col-md-1 ' . $requIred[0]))!!}
                <div class="col-sm-4 col-md-5">
                    {!!Form::text('user_name', $user->user_name, array('class' => 'form-control ' . $requIred[1] . ' ' . trans('userdata.user_name'), $disabledForm))!!}
                </div>
                {{-- first name --}}
                {!!Form::label('user_first_name', trans('userdata.user_first_name'), array('class' => 'col-sm-2 col-md-1 ' . $requIred[0]))!!}
                    <div class="col-sm-4 col-md-5">
                {!!Form::text('user_first_name', $user->user_first_name, array('class' => 'form-control ' . $requIred[1] . ' ' . trans('userdata.user_first_name'), $disabledForm))!!}
                </div>
            </div>
            <div class="row">
                {{-- login name --}}
                {!!Form::label('user_login_name', trans('userdata.user_login_name'), array('class' => 'col-sm-2 col-md-1'))!!}
                <div class="col-sm-4 col-md-5">
                    {!!Form::text('user_login_name_show', $user->user_login_name, array('class' => 'form-control' . ' ' . trans('userdata.user_login_name'), 'disabled', 'id' => 'user_login_name_show'))!!}
                    {!!Form::hidden('user_login_name', $user->user_login_name, array('class' => 'form-control', $disabledForm))!!}
                </div>
                {{-- clan --}}
                {!!Form::label('clan_id', trans('userdata.clan'), array('class' => 'col-sm-2 col-md-1'))!!}
                <div class="col-sm-4 col-md-5">
                {!!Form::text('clan_id', $clan, array('class' => 'form-control', 'disabled'))!!}
                </div>
            </div>
                <div class="row">
                    <div class="col-sm-6 col-md-6">
                    </div>
                    {{-- family --}}
                    {!!Form::label('user_family', trans('userdata.halfclan'), array('class' => 'col-sm-2 col-md-1'))!!}
                    <div class="col-sm-4 col-md-5">
                    {!!Form::text('user_family', $user->family_description, array('class' => 'form-control', 'disabled'))!!}
                    </div>
                </div>
        </fieldset>
        <fieldset>
            <legend>{!!trans('profile.emails')!!}</legend>
            <div class="row">
               {{-- email --}}
                {!!Form::label('email', trans('userdata.email'), array('class' => 'col-sm-2 col-md-1 ' . $requIred[0]))!!}
                <div class="col-sm-4 col-md-5">
                  @if($disabledForm == '')
                      {!!Form::text('email', $user->email, array('class' => 'form-control ' . $requIred[1] . ' ' . trans('userdata.email'), $disabledForm))!!}
                  @else
                    <a href="mailto:{!!$user->email!!}">{!!$user->email!!}</a>
                  @endif
                </div>
                {{-- email2 --}}
                {!!Form::label('user_email2', trans('userdata.email2'), array('class' => 'col-sm-2 col-md-1'))!!}
                <div class="col-sm-4 col-md-5">
                  @if($disabledForm == '')
                    {!!Form::text('user_email2', (isset($user->user_email2)) ? $user->user_email2 : Input::old('user_email2'), array('class' => 'form-control', $disabledForm))!!}
                  @else
                    <a href="mailto:{!!$user->user_email2!!}">{!!$user->user_email2!!}</a>
                  @endif
                </div>
            </div>
            <div class="row">
               {{-- www --}}
                {!!Form::label('user_www', trans('profile.www'), array('class' => 'col-sm-2 col-md-1'))!!}
                <div class="col-sm-4 col-md-5">
                    <div class="input-group">
                  @if($disabledForm == '')
                        {!!Form::text('user_www', (isset($user->user_www)) ? $user->user_www : Input::old('user_www'), array('class' => 'form-control', $disabledForm))!!}
                  @else
                    <a target="_blank" href="http://{!!$user->user_www!!}">{!!$user->user_www!!}</a>
                  @endif
                        {{-- ToDo www --}}
                        {{--<span class="input-group-btn">
                            <a href="http://{!!$user->user_www!!}" target="_blank" class="btn btn-default" type="button">Go!</a>
                        </span>--}}
                   </div>
                </div>
                {{-- www label --}}
                {!!Form::label('user_www_label', trans('profile.www_label'), array('class' => 'col-sm-2 col-md-1'))!!}
                <div class="col-sm-4 col-md-5">
                    {!!Form::text('user_www_label', (isset($user->user_www_label)) ? $user->user_www_label : Input::old('user_www_label'), array('class' => 'form-control', $disabledForm))!!}
                </div>
            </div>
        </fieldset>
        <fieldset>
            <legend>{!!trans('profile.fons')!!}</legend>
            <div class="row">
               {{-- fon1 label --}}
                <div class="col-sm-1 col-md-1">
                    {{-- fon1 --}}
                    {!!Form::label('user_fon1', trans('userdata.fon', array('n' => 1)), array('class' => 'requ'))!!}
                </div>
                <div class="col-sm-5 col-md-3">
                    <div class="input-group">
                        @if($disabledForm == '')
                        <div class="input-group-btn" style="vertical-align: top">
                            <button style="margin-top: 4px" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                            <ul class="dropdown-menu dropdown-menu-left" role="menu">
                                @foreach(Constants::translateFonLabels() as $k => $s)
                                    <li class="user_fon1_label"><a href="#{!!$k!!}">{!! $s !!}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        {!!Form::hidden('user_fon1_label', $user->user_fon1_label or 'home', array('class' => 'form-control ' . $requIred[1]))!!}
                        {!!Form::text('user_fon1_label_show', (strlen($user->user_fon1_label) > 0) ? trans('userdata.fonlabel.' . $user->user_fon1_label) : trans('userdata.fonlabel.x'), array('class' => 'form-control required inline-input-long' . ' ' . trans('userdata.fonlabel.' . $user->user_fon1_label), 'disabled'))!!}
                        {!!Form::text('user_fon1', (isset($user->user_fon1)) ? $user->user_fon1 : Input::old('user_fon1'), array('class' => 'form-control required inline-input-long' . ' ' . trans('userdata.fon', array('n' => 1)), $disabledForm))!!}
                    </div>
                </div>
               {{-- fon2 label --}}
                <div class="col-sm-1 col-md-1">
                    {{-- fon2 --}}
                    {!!Form::label('user_fon2', trans('userdata.fon', array('n' => 2)))!!}
                </div>
                <div class="col-sm-5 col-md-3">
                    <div class="input-group">
                        @if($disabledForm == '')
                        <div class="input-group-btn" style="vertical-align: top">
                            <button style="margin-top: 4px" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                            <ul class="dropdown-menu dropdown-menu-left" role="menu">
                                @foreach(Constants::translateFonLabels() as $k => $s)
                                    <li class="user_fon2_label"><a href="#{!!$k!!}">{!! $s !!}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        {!!Form::hidden('user_fon2_label', (strlen($user->user_fon2_label) > 1) ? trans('userdata.fonlabel.' . $user->user_fon2_label) : trans('userdata.fonlabel.x'), array('class' => 'form-control'))!!}
                        {!!Form::text('user_fon2_label_show', (strlen($user->user_fon2_label) > 0) ? trans('userdata.fonlabel.' . $user->user_fon2_label) : trans('userdata.fonlabel.x'), array('class' => 'form-control inline-input-long', 'disabled'))!!}
                        {!!Form::text('user_fon2', (isset($user->user_fon2)) ? $user->user_fon2 : Input::old('user_fon2'), array('class' => 'form-control inline-input-long', $disabledForm))!!}
                    </div>
                </div>
               {{-- fon3 label --}}
                <div class="col-sm-1 col-md-1">
                    {{-- fon3 --}}
                    {!!Form::label('user_fon3', trans('userdata.fon', array('n' => 3)))!!}
                </div>
                <div class="col-sm-5 col-md-3">
                    <div class="input-group">
                        @if($disabledForm == '')
                        <div class="input-group-btn" style="vertical-align: top">
                            <button style="margin-top: 4px" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                            <ul class="dropdown-menu dropdown-menu-left" role="menu">
                                @foreach(Constants::translateFonLabels() as $k => $s)
                                    <li class="user_fon3_label"><a href="#{!!$k!!}">{!! $s !!}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        {!!Form::hidden('user_fon3_label', $user->user_fon3_label, array('class' => 'form-control'))!!}
                        {!!Form::text('user_fon3_label_show', (strlen($user->user_fon3_label) > 0) ? trans('userdata.fonlabel.' . $user->user_fon3_label) : trans('userdata.fonlabel.x'), array('class' => 'form-control inline-input-long', 'disabled'))!!}
                        {!!Form::text('user_fon3', (isset($user->user_fon3)) ? $user->user_fon3 : Input::old('user_fon3'), array('class' => 'form-control inline-input-long', $disabledForm))!!}
                    </div>
                </div>
            </div>
        </fieldset>
        <fieldset>
        <legend>{!!trans('profile.address')!!}</legend>
            <div class="row">
                {{-- address --}}
                {!!Form::label('user_address', trans('userdata.user_address'), array('class' => 'col-sm-2 col-md-1 ' . $requIred[0]))!!}
                <div class="col-sm-4 col-md-5">
                    {!!Form::text('user_address', (isset($user->user_address)) ? $user->user_address : Input::old('user_address'), array('class' => 'form-control ' . $requIred[1] . ' ' . trans('userdata.user_address'), $disabledForm))!!}
                </div>
                {{-- zip --}}
                {!!Form::label('user_zip', trans('userdata.user_zip') . '/' . trans('userdata.user_city'), array('class' => 'col-sm-1 col-md-1 ' . $requIred[0]))!!}
                <div class="col-sm-4 col-md-5">
                    {!!Form::text('user_zip', (isset($user->user_zip)) ? $user->user_zip : Input::old('user_zip'), array('class' => 'form-control input-small ' . $requIred[1] . ' ' . trans('userdata.zip'), $disabledForm))!!}
                    {{-- city --}}
                    {!!Form::text('user_city', (isset($user->user_city)) ? $user->user_city : Input::old('user_city'), array('class' => 'form-control input-medium ' . $requIred[1] . ' ' . trans('userdata.city'), $disabledForm))!!}
                </div>
            </div>
            <div class="row">
                  {{-- country --}}
                  {!!Form::label('user_country_code', trans('userdata.user_country_code'), array('class' => 'col-sm-2 col-md-1 ' . $requIred[0]))!!}
                  <div class="col-sm-4 col-md-5">
                      {!!Form::select('user_country_code', $countries, (isset($user->user_country_code)) ? $user->user_country_code : Input::old('user_country_code'), array('class' => 'form-control ' . $requIred[1] . ' ' . trans('userdata.user_country_code'), $disabledForm))!!}
                  </div>
                  <div class="col-sm-6 col-md-6">
                  </div>
          </div>
        </fieldset>
       <fieldset>
        <legend>{!!trans('profile.pers')!!}</legend>
            <div class="row">
                {{-- birthday --}}
                {!!Form::label('user_birthday', trans('userdata.birthday'), array('class' => 'col-sm-2 col-md-1'))!!}
                <div class="col-sm-3 col-md-3">
                    {!!Form::input('text', 'user_birthday', (isset($user->user_birthday)) ? $user->user_birthday : Input::old('user_birthday'), array('class' => 'form-control date_type_birthday' . ' ' . trans('userdata.birthday'), $disabledForm, 'readonly' => 'readonly'))!!}
                </div>
                {{-- avatar --}}
                {!!Form::label('user_avatar', trans('userdata.avatar'), array('class' => 'col-sm-1 col-md-1'))!!}
                <div class="col-sm-3 col-md-3">
                @if($disabledForm == '')
                    {!!Form::file('user_avatar', array('class' => 'form-control', $disabledForm))!!}
                @endif
                </div>
                <div class="col-sm-3 col-md-4">
                    <img class="img-responsive" src="{!!$user->user_avatar!!}" />
                </div>
            </div>
        </fieldset>
        @if($disabledForm == '')
        <fieldset>
        <legend>{!!trans('profile.secure')!!}</legend>
            <div class="row">
                {{-- question --}}
                {!!Form::label('user_question', trans('profile.user_question'), array('class' => 'col-sm-2 col-md-1 ' . $requIred[0]))!!}
                <div class="col-sm-4 col-md-5">
                    {!!Form::text('user_question', (isset($user->user_question)) ? $user->user_question : Input::old('user_question'), array('class' => 'form-control ' . $requIred[1] . ' ' . trans('userdata.user_question')))!!}
                </div>
                {{-- answer --}}
                {!!Form::label('user_answer', trans('profile.user_answer'), array('class' => 'col-sm-2 col-md-1 ' . $requIred[0]))!!}
                <div class="col-sm-4 col-md-5">
                    {!!Form::text('user_answer', (isset($user->user_answer)) ? $user->user_answer : Input::old('user_answer'), array('class' => 'form-control ' . $requIred[1] . ' ' . trans('userdata.user_answer')))!!}
                </div>
            </div>
        </fieldset>
       @endif
        <fieldset>
             <legend>{!!trans('profile.roles')!!}</legend>
             <div class="table-responsive">
                 <table class="table">
                    <thead>
                        <tr>
                            <th>{!!trans('roles.role_description', ['n' => ''])!!}</th>
                            <th>{!!trans('roles.role_tax_annual', array('n' => 'CHF '))!!}</th>
                            <th>{!!trans('roles.role_tax_night', array('n' => 'CHF '))!!}</th>
                            <th>{!!trans('roles.role_tax_stock', array('n' => 'CHF '))!!}</th>
                            <th>{!!trans('rights.right')!!}</th>
                        </tr>
                    </thead>
                    <tbody>
                       @foreach($user->roles as $key => $roles)
                       <tr>
                            <td>
                                {!!trans('roles.' . $roles->role_code)!!}
                            </td>
                            <td>
                                {!!$roles->role_tax_annual!!}
                            </td>
                            <td>
                                {!!$roles->role_tax_night!!}
                            </td>
                            <td>
                                {!!$roles->role_tax_stock!!}
                            </td>
                            <td>
                                <ul>
                                @if(sizeof($roles->rights) == 0)
                                    <li>{!!trans('rights.norights')!!}</li>
                                @else

                                @foreach($roles->rights as $right)
                                    <li>
                                        {!!trans('rights.' . $right->right_code)!!}
                                    </li>
                                @endforeach
                                @endif
                                </ul>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                 </table>
             </div>
         </fieldset>
         @if($disabledForm == '')

         <fieldset>
             <legend>{!!trans('profile.payment_methods')!!}</legend>
                {!!Form::label('user_payment_method', trans('profile.payment_methods'), array('class' => 'col-sm-2 col-md-3 ' . $requIred[0]))!!}
                <div class="col-sm-6 col-md-3">
                      {!!Form::select('user_payment_method', [''] + $payment_method, (isset($user->user_payment_method)) ? $user->user_payment_method : Input::old('user_payment_method'), array('class' => 'form-control ' . $requIred[1] . ' ' . trans('userdata.payment_method')))!!}
                </div>
         </fieldset>
       <fieldset>
        <legend>{!!trans('profile.actions')!!}</legend>
            <div class="row">
              <div class="col-sm-12 col-md-12">
                    {!!Form::submit(trans('navigation.profile') . ' ' . trans('dialog.save'), array('class' => 'btn btn-success btn-lg', 'id' => 'saveIt'))!!}
              </div>
              <div class="col-sm-6 col-md-6">
                  <!-- button type="button" id="profilePrint" class="btn btn-default">{!!trans('dialog.print')!!}</button -->
              </div>
            </div>
        </fieldset>
            @endif
    {!!Form::close()!!}
         @if($disabledForm == '')
       {!!Form::open(array('url' => 'user/profile/changepass'))!!}
                <fieldset>
                <legend>{!!trans('userdata.new_pass')!!}</legend>
                     <div class="row">
                          <div class="col-sm-1 col-md-1"></div>
                          <div class="col-sm-10 col-md-10 required required-help-text">
                              {!!trans('userdata.new_pass_text')!!}
                          </div>
                          <div class="col-sm-1 col-md-1"></div>
                     </div>
                     <div class="row">
                        {{-- old password --}}
                        {!!Form::label('old_pass', trans('userdata.old_pass'), array('class' => 'col-sm-2 col-md-1'))!!}
                        <div class="col-sm-2 col-md-3">
                            {!!Form::password('old_pass', array('class' => 'form-control' . ' ' . trans('userdata.pass')))!!}
                        </div>
                        {{-- new pass --}}
                        {!!Form::label('new_pass', trans('userdata.new_pass'), array('class' => 'col-sm-2 col-md-1'))!!}
                        <div class="col-sm-2 col-md-3">
                            {!!Form::password('new_pass', array('class' => 'form-control' . ' ' . trans('userdata.new_pass')))!!}
                        </div>
                        {{-- new pass confirm --}}
                        {!!Form::label('new_pass_confirmation', trans('userdata.new_pass_confirm'), array('class' => 'col-sm-2 col-md-1'))!!}
                        <div class="col-sm-2 col-md-3">
                            {!!Form::password('new_pass_confirmation', array('class' => 'form-control' . ' ' . trans('userdata.new_pass_confirm')))!!}
                        </div>
                    </div>
                    <div class="row">
                        {{-- confirm --}}
                        <div class="col-sm-2 col-md-3">
                            {!!Form::submit(trans('userdata.confirm_new_pass_go'), array('class' => 'btn btn-default','id' => 'saveNewPass'))!!}
                        </div>
                    </div>
                </fieldset>
        {!!Form::close()!!}
@endif
    @section('scripts')
    @parent

        <script src="{!!asset('assets/js/inits/profile_init.js')!!}"></script>
        <script>            window.adaptInputs(false);
        </script>
    @stop
@stop
