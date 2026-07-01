(function ($) {
    'use strict';

    function isMobile() {
        return window.matchMedia('(max-width: 991.98px)').matches;
    }

    function initSidebarMobile() {
        var $body = $('body');

        if (isMobile()) {
            $body.addClass('sidebar-collapse');
        }

        $(window).on('resize', function () {
            if (isMobile()) {
                $body.addClass('sidebar-collapse');
            }
        });

        // Close sidebar overlay after navigating on mobile
        $(document).on('click', '.main-sidebar .nav-link', function () {
            if (isMobile() && !$(this).parent().hasClass('has-treeview')) {
                $body.addClass('sidebar-collapse');
            }
        });
    }

    function wrapTables() {
        $('.card-body > table.table, section.content table.table').each(function () {
            var $table = $(this);

            if ($table.closest('.table-responsive').length) {
                return;
            }

            if ($table.parent().hasClass('dataTables_wrapper')) {
                $table.parent().wrap('<div class="table-responsive"></div>');
                return;
            }

            $table.wrap('<div class="table-responsive"></div>');
        });
    }

    function initDataTableDefaults() {
        if (!$.fn.dataTable) {
            return;
        }

        $.extend(true, $.fn.dataTable.defaults, {
            responsive: true,
            autoWidth: false,
            lengthChange: false,
            pageLength: 25,
            language: {
                paginate: {
                    previous: '&laquo;',
                    next: '&raquo;'
                }
            }
        });
    }

    $(function () {
        initSidebarMobile();
        initDataTableDefaults();
        wrapTables();

        // Re-wrap after DataTables initializes on individual pages
        $(document).on('init.dt', function () {
            wrapTables();
        });
    });
})(jQuery);
