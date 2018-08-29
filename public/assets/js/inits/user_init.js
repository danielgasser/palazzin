/**
 * Created by pc-shooter on 15.12.14.
 */
jQuery(document).ready(function () {
    "use strict";
    jQuery('#users').tablesorter({
        cssAsc: 'headerSortUp',
        cssDesc: 'headerSortDown',
        headers: {
            0: {
                sorter: false
            }
        }
    });
    $('#users').TableWizard();

});
