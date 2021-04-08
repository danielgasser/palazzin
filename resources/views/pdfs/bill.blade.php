<table>
    <thead>
        <tr>
            <td colspan="7" style="border-bottom: none">
                <a href="{{ $url }}">
                    <img style="width: 182px; height: auto;" src="{{ $url }}/public/img/Palazzin_Logo.png" alt="{!!$settings['setting_app_owner']!!}" title="{!!$settings['setting_app_owner']!!}">
                </a>
            </td>
        </tr>
        <tr>
            <td colspan="5" style="height: 99px">
                <h3>{!!trans('bill.bill_no')!!} {!!$bill['billId']!!}</h3>
            </td>
            <td colspan="2">
                <h3>{!!trans('bill.date')!!}: {!!$bill['billDate']!!}</h3>
            </td>
        </tr>
        <tr>
            <td colspan="7">
            <br>{!!$bill['userBill']['user_first_name']!!} {!!$bill['userBill']['user_name']!!}<br>
            {!!$bill['userBill']['user_address']!!}<br>
            {!!$bill['userBill']['user_zip']!!} {!!$bill['userBill']['user_city']!!}<p>
            {!!$bill['billAddressCountry']!!}</p><br>
                <h3>{!!trans('reservation.title_singular')!!} {!!trans('reservation.from_small')!!} {!!$bill['resDate'][0]!!} {!!trans('reservation.till')!!} {!!$bill['resDate'][1]!!}</h3>
            </td>
        </tr>
    </thead>
    <tbody>
    <tr>
        <th style="width:25px">
            N°
        </th>
        <th style="width:245px">
            {!!trans('reservation.guests.role')!!}
        </th>
        <th style="width:50px">
            {!!trans('reservation.guests.number')!!}
        </th>
        <th style="width:70px">
            {!!trans('reservation.guests.tax_night')!!}
        </th>
        <th style="width:70px">
            {!!trans('reservation.nights')!!}
        </th>
        <th style="text-align: right;width: 50px;">
            {!!trans('bill.currency')!!}
        </th>
        <th style="text-align: right;width: 135px;">
            {!!trans('bill.total_guest')!!}
        </th>
    </tr>
    <?php $a=1;
    ?>

    @foreach($bill['resBill']['guests'] as $res)
        <tr>
            <td style="padding: 0 3px;">
                <?php echo $a;
                ?>
            </td>
            <td style="padding: 0 3px;">
                {!!$res['role_code']!!}
            </td>
            <td style="padding: 0 3px;">
                {!!$res['guest_number']!!}
            </td>
            <td style="padding: 0 3px;">
                {!!$res['role_tax_night']!!}
            </td>
            <td style="padding: 0 3px;">
                {!!$res['guest_night']!!}
            </td>
            <td style="text-align: right">
                {!!$bill['currency']!!}
            </td>
            <td style="padding: 0 3px; text-align: right">
                {!! number_format($res['guestSum'], 2, '.', '\'') !!}
            </td>
        </tr>
        <?php $a++; ?>
    @endforeach
    <tr>
        <td colspan="7">&nbsp;</td>
    </tr>
    </tbody>
    <tfoot>
    <tr>
        <th colspan="5" style="border-bottom: 1px solid #9b280b">
            {!!trans('bill.sub_total_bill')!!}
        </th>
        <th style="text-align: right; border-bottom: 1px solid #9b280b">
            {!!$bill['currency']!!}
        </th>
        <th style="text-align: right; border-bottom: 1px solid #9b280b">
            {!! number_format($bill['bill']['bill_sub_total'], 2, '.', '\'') !!}
        </th>
    </tr>
    <tr>
        <th colspan="6">
            {!!trans('bill.taxes')!!}
        </th>
        <th style="text-align: right;">
            {!!$bill['tax']!!}%
        </th>
    </tr>
    <tr>
        <th colspan="5" style="border-bottom: 2px solid #9b280b">
            {!!trans('bill.total_bill')!!}
        </th>
        <th style="text-align: right; border-bottom: 2px solid #9b280b">
            {!!$bill['currency']!!}
        </th>
        <th style="text-align: right; border: none; border-bottom: 2px solid #9b280b">
            {!! number_format($bill['bill']['bill_total'], 2, '.', '\'') !!}
        </th>
    </tr>
    <tr>
        <td colspan="7">&nbsp;
        </td>
    </tr>
    <tr>
        <td colspan="7">
            {!! trans('settings.setting_bill_deadline', array('days' => $bill['setting_bill_deadline'])) !!}
        </td>
    </tr>
    <tr>
        <td colspan="4">
            {!!$bill['billtext']!!}
        </td>
    </tr>
    </tfoot>
</table>
