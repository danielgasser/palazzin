@extends('layout.master')
@section('content')

<div class="row">
    <div class="col-sm-12 col-md-6">
        <h1>{!!$right->right_code!!} - {!!trans('rights.' . $right->right_code)!!}</h1>
            <h3><a href="{!!URL::to('admin/rights')!!}">{!!trans('dialog.back', ['to' => 'zu ' . trans('navigation.admin') . ' > ' . trans('navigation.admin/rights')])!!}</a></h3>
    </div>
</div>
    {!!Form::model($right, array('url' => array('admin/rights/edit', $right->id)))!!}
        {!!Form::hidden('id', $right->id)!!}
        <fieldset>
            <legend>{!!trans('rights.right_description', ['n' => ''])!!}</legend>
            <div class="row">
                {!!Form::label('right_code', trans('rights.right_code'), array('class' => 'col-sm-2 col-md-1'))!!}
                <div class="col-sm-4 col-md-5">
                    {!!Form::text('right_code', $right->right_code, array('class' => 'form-control', 'disabled'))!!}
                </div>
                {!!Form::label('right_description', trans('rights.right_description', ['n' => '']), array('class' => 'col-sm-2 col-md-1'))!!}
                    <div class="col-sm-4 col-md-5">
                {!!Form::text('right_description', trans('rights.' . $right->right_code), array('class' => 'form-control', 'disabled'))!!}
                </div>
            </div>
        </fieldset>
        <fieldset>
            <legend>{!!trans('profile.actions')!!}</legend>
            <div class="row">
                <div class="col-sm-3 col-md-4">
                    {!!Form::submit(trans('dialog.save'), ['class' => 'btn btn-default', 'id' => 'saveRole'])!!}
                </div>
                <div class="col-sm-3 col-md-2">

                </div>
            </div>
        </fieldset>
    {!!Form::close()!!}
    @section('scripts')
    @parent
        <script>
        var role_rights = '{!!URL::to('admin/roles/rights')!!}',
            role_delete = '{!!URL::to('admin/roles/rights/delete')!!}'
        </script>
        <script src="{!!asset('assets/min/js/admin.min.js')!!}"></script>
    @stop
@stop