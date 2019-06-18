$(document).ready(function () {
   $('#pricelist').DataTable({
       paging: false,
       searching: false,
       dom: 'Bfrtip',
       buttons: [
           {
               extend: 'copy',
               text: 'Kopieren',
               className: 'btn btn-default'
           },
           {
               extend: 'csv',
               text: 'CSV',
               className: 'btn btn-default'
           },
           {
               extend: 'excel',
               text: 'Excel',
               className: 'btn btn-default'
           },
           {
               extend: 'pdf',
               text: 'PDF',
               className: 'btn btn-default'
           },
           {
               extend: 'print',
               text: 'Drucken',
               className: 'btn btn-default'
           }
       ],

   });
});
