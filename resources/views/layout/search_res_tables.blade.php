<tr>
    <td colspan="6">
        <table style="width: 96%;" class="table">
            <thead>
                <tr>
                    @if(Request::is('admin/reservations'))
                        <th>Benutzer</th>
                    @else
                        <th></th>
                    @endif
                    <th>Monat</th>
                    <th>Jahr</th>
                </tr>
            </thead>
            <tbody>
                <tr id="searcher">
                    <td>
                        @if(Request::is('admin/reservations'))
                        <select id="searchIt" data-placeholder="Benutzer wählen oder suchen" name="searchIt" class="form-control">
                            <option selected disabled hidden value=''>{!!trans('dialog.choose_search_user')!!}</option>
                            @foreach($users as $u)
                                <option value="{!!$u->id!!}">{!!$u->user_name!!} {!!$u->user_first_name!!}</option>
                            @endforeach
                        </select>
                        @elseif(Request::is('bills'))
                            <div id="searchIt" style="display: none">{!!Auth::id()!!}</div>
                        @else
                            <input id="searchIt" name="searchIt" type="hidden" value="{!!Auth::id()!!}">
                        @endif
                            @if(Request::is('admin/reservations'))

                            <button class="btn btn-default" id="resetUserDropDown">{!!trans('dialog.reset')!!}</button>
                                @endif
                    </td>
                    <td>
                        <select name="year" class="form-control" id="month">
                        </select>
                    </td>
                    <td>
                    <select name="month" class="form-control" id="year">
                        </select>
                    </td>
                    <td>
                        <button class="btn btn-default" id="resetKeeper">{!!trans('dialog.all')!!}</button>
                        <button class="btn btn-default" id="searchKeeper">{!!trans('dialog.search')!!}</button>
                    </td>
                </tr>
                <tr>
                    <td colspan="6"><b>Anzahl Reservierungen: <span id="total_search">{!!sizeof($allReservations)!!}</span></b></td>
                </tr>

            </tbody>
        </table>
    </td>
</tr>
