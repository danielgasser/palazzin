var dataTableSettings = {
    responsive: true,
    autoWidth: false,
    orderable: false,
    paging: false,
    fixedHeader: {
        header: true,
        footer: true
    },
    order: [
        1,
        'asc'
    ],
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
    fnDrawCallback: function () {
    },
    lengthChange: false
};
$(function () {
    $('#rights').dataTable(dataTableSettings)
});
