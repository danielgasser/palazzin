<div id="print_table_name" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title-info">{!! trans('dialog.info') !!}</h4>
            </div>
            <div class="modal-body">
                <div id="print_table_name_message" style="padding: 0.5em; font-weight: bold; font-size: 130%;"></div>
                    <input type="hidden" name="uIDs" id="uIDs">
                    <input type="hidden" name="sort_field" id="sort_field">
                    <input type="hidden" name="order_by" id="order_by">
                    <input type="text" class="form-control" name="print_name_pdf" id="print_name_pdf" placeholder="Name des PDF's" />
                <p id="print_table_name_text">Es kann eine Weile dauern, bis das PDF erstellt ist. Bitte verlasse oder aktualisere diese Seite nicht.</p>
            </div>
            <div class="modal-footer">
                <button type="button" id="sendToPrintSubmit" class="btn btn-default">Drucken</button>
            </div>

        </div>
    </div>
</div>
