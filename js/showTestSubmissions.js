$(document).ready( function () {
    $('#submissions-table').DataTable({
        "language": {
            "zeroRecords": "Tabuľka neobsahuje žiadny záznam.",
            "info": "Zobrazuje sa _START_. až _END_. z _TOTAL_ záznamov.",
            "lengthMenu": "Zobraziť _MENU_ záznamov.",
            "infoEmpty": "Nenašiel sa žiadny záznam",
            "infoFiltered": "(z celkového počtu _MAX_ záznamov).",
            "search": "Hľadať:",
            "paginate": {
                "next": "Ďalšia strana",
                "previous": "Predošlá strana",
            }
        },
    });
} );