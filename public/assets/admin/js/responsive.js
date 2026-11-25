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

    function initDataTableDefaults() {
        if (!$.fn.dataTable) {
            return;
        }

        $.extend(true, $.fn.dataTable.defaults, {
            responsive: true,
            autoWidth: false,
            lengthChange: true,
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

        // Fix DataTables filter label — run once after each DataTable initializes
        $(document).on('init.dt', function () {
            // Remove "Search:" label text, keep only the input box
            $('.dataTables_filter label').contents().filter(function () {
                return this.nodeType === 3; // plain text nodes only
            }).remove();

            $('.dataTables_filter input[type="search"]').attr('placeholder', 'Search...');
        });
    });
})(jQuery);
