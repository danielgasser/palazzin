/**
 * Created by pc-shooter on 17.12.14.
 */
$(function () {
    $('#bill_all_totals').dataTable({
        language: {
            paginate: {
                first: window.paginationLang.first,
                previous: window.paginationLang.previous,
                next: window.paginationLang.next,
                last: window.paginationLang.last
            },
            search: window.langDialog.search,
            info: window.paginationLang.info,
            sLengthMenu: window.paginationLang.length_menu
        },

    })
})
