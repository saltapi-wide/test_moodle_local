define(
    [
        "jquery",
        'report_customreports/repository',
        "report_customreports/jquery.dataTables",
        "report_customreports/dataTables.responsive",
        "report_customreports/responsive.jqueryui",
        "report_customreports/responsive.bootstrap",
        "report_customreports/responsive.bootstrap4",
        "report_customreports/dataTables.buttons",
        "report_customreports/dataTables.bootstrap",
        'report_customreports/dataTables.bootstrap4',
        "report_customreports/buttons.bootstrap",
        "report_customreports/buttons.bootstrap4",
        "report_customreports/buttons.print",
        'report_customreports/buttons.colVis',
        'report_customreports/buttons.html5',
        'report_customreports/jszip'
    ],
    function ($,
              Repository,
              DataTable) {

        var init = function () {

            $('.reporttable thead tr').clone(true).appendTo('.reporttable thead');
            $('.reporttable thead tr:eq(1) th').each(function (i) {
                var title = $(this).text();
                $(this).html('<input type="text" placeholder="Filter ' + title + '" />');
                $('input', this).on('keyup change', function () {
                    //exact match search, we do not need this at the moment
                    //exact_search = '\\b' + this.value + '\\b';
                    if ($('.reporttable').DataTable().column(i).search() !== this.value) {
                        $('.reporttable').DataTable()
                            .column(i)
                            /*.search(exact_search, true, false)*/
                            .search(this.value)
                            .draw();
                    }
                });
            });
            $('.reporttable thead tr:eq(1) th:eq(0) input').remove();



            $(document).ready(function () {


                var table = $('.reporttable').DataTable({
                    orderCellsTop: true,
                    responsive: {
                        details: {
                            type: 'column',
                            target: 0
                        }
                    },

                    columnDefs: [
                        {
                            className: 'control',
                            orderable: false,
                            targets: 0
                        }
                    ],
                    /* select: {
                         style: 'multi',
                         selector: '.select-checkbox'
                     },*/
                    order: [
                        [1, 'asc']
                    ],
                    //Buttons before lengthMenu and Filter(searchbar), all these 3 before the table, and then information and pagination
                    //https://datatables.net/reference/option/dom
                    dom: '<B<lf><t>ip>',
                    buttons: [{

                        extend: 'collection',
                        className: 'exportButton',
                        text: 'Export',

                        buttons: [
                            {
                                extend: 'copy',
                                exportOptions: {
                                    columns: ':visible',
                                    rows: ':visible'
                                }
                            },
                            {
                                extend: 'print',
                                exportOptions: {
                                    columns: ':visible',
                                    rows: ':visible'
                                }
                            },
                            {
                                extend: 'excel',
                                exportOptions: {
                                    columns: ':visible',
                                    rows: ':visible'
                                }
                            },
                            {
                                extend: 'pdf',
                                exportOptions: {
                                    columns: ':visible',
                                    rows: ':visible'
                                }
                            }

                        ]

                    },
                        'colvis']
                });//end DataTable
                table.columns().every(function () {
                    var that = this;
                    $('input', this.header()).on('keyup change', function () {
                        if (that.search() !== this.value) {
                            that
                                .search(this.value)
                                .draw();
                        }
                    });
                });

                $('thead tr:eq(0) th:eq(0)').removeClass('select-checkbox');

//event to stop sorting when clicking on
                $('thead tr th input').click(function (e) {
                    e.stopPropagation();
                });

                $(window).resize(function () {
                    $('.reporttable thead tr:eq(0) th').each(function () {
                        var display = $(this).css('display');
                        var position = $(this).index();

                        if (display == 'none') {
                            $('thead tr:eq(1)').find('th:eq(' + position + ') ').addClass('hidden');

                        } else {
                            $('thead tr:eq(1)').find('th:eq(' + position + ') ').removeClass('hidden');

                        }
                    });
                });

                //gia to collumn visibility
                $('.reporttable thead tr:eq(0) th').each(function () {
                    var display = $(this).css('display');
                    var position = $(this).index();
                    if (display == 'none') {
                        $('thead tr:eq(1)').find('th:eq(' + position + ') ').addClass('hidden');
                    } else {
                        $('thead tr:eq(1)').find('th:eq(' + position + ') ').removeClass('hidden');
                    }
                });

            });//end document.ready

            $('.dashboard_view').on('click',function(){
                window.location='/my';
            });
            $('.toreports').on('click',function(){
                window.location='/report/customreports/index.php';
            });

        };//end init

        return {
            init: init
        };
    });