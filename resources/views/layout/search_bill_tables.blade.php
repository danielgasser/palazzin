<tr>
    <td colspan="6">
        <table style="width: 96%;">
            <thead>
                <tr>
                    @if(Request::is('admin'))
                    <th>Benutzer</th>
                    @else
                        <th></th>
                    @endif
                        <th>Rechnungs N°</th>
                    <th>Bezahlt/Unbezahlt</th>
                    <th>Monat</th>
                    <th>Jahr</th>
                </tr>
            </thead>
            <tbody>
                <tr id="searcher">
                    <td>
                        @if(Request::is('admin'))
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
                            @if(Request::is('admin'))
                            <button class="btn btn-default" id="resetUserDropDown">{!!trans('dialog.reset')!!}</button>
                                @endif
                    </td>
                    <td>
                        <input class="form-control" id="billNo" name="billNo" type="text" value="No-">
                    </td>
                    <td>
                        @if(Request::is('bill'))
                            <select name="search_bill_paid" id="search_bill_paid" class="form-control" id="month">
                                <option value="x">{!!trans('dialog.all')!!}</option>
                                <option value="1">{!!trans('dialog.not')!!} {!!trans('bill.paid')!!}</option>
                                <option value="0">{!!trans('bill.paid')!!}</option>
                            </select>
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
                        @if(Request::is('admin'))
                        <button class="btn btn-default" id="searchKeeper">{!!trans('dialog.search')!!}</button>
                            @else
                            <button class="btn btn-default" id="searchUserKeeper">{!!trans('dialog.search')!!}</button>
                            @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="6"><b>Anzahl Rechnungen: <span id="total_search">{!!sizeof($allBills)!!}</span></b></td>
                </tr>

            </tbody>
        </table>
    </td>
</tr>
