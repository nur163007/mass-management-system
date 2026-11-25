<?php $__env->startSection('heading', 'All Payments'); ?>
<?php $__env->startSection('title', 'Payments | MMS'); ?>

<?php $__env->startSection('custom_css'); ?>
    <style>
        :root {
            --vp-radius: 16px;
            --vp-transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Hero Banner */
        .vp-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%);
            border-radius: var(--vp-radius);
            color: #ffffff;
            padding: 24px 28px;
            margin-bottom: 22px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.22);
        }

        .vp-hero::after {
            content: '';
            position: absolute;
            top: -50px;
            right: -40px;
            width: 220px;
            height: 220px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.25) 0%, rgba(16, 185, 129, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .vp-hero-title {
            font-size: 1.45rem;
            font-weight: 700;
            margin-bottom: 4px;
            letter-spacing: -0.3px;
        }

        .vp-hero-sub {
            font-size: 0.88rem;
            color: #94a3b8;
            margin-bottom: 0;
        }

        .vp-hero-btn {
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 0.84rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: var(--vp-transition);
        }

        .vp-hero-btn:hover {
            background: rgba(255, 255, 255, 0.22);
            color: #ffffff;
            transform: translateY(-2px);
            text-decoration: none;
        }

        .vp-hero-btn.btn-emerald {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-color: transparent;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .vp-hero-btn.btn-emerald:hover {
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.45);
        }

        /* Summary Stat Cards */
        .vp-stat-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 18px 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: var(--vp-transition);
            height: 100%;
        }

        .vp-stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px -5px rgba(15, 23, 42, 0.08);
        }

        .vp-stat-label {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .vp-stat-value {
            font-size: 1.45rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
        }

        .vp-stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .vp-icon-emerald {
            background: #ecfdf5;
            color: #059669;
        }

        .vp-icon-indigo {
            background: #eef2ff;
            color: #4f46e5;
        }

        .vp-icon-amber {
            background: #fffbeb;
            color: #d97706;
        }

        /* Modern Panel */
        .vp-panel {
            background: #ffffff;
            border-radius: var(--vp-radius);
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
            overflow: hidden;
            margin-bottom: 24px;
        }

        .vp-filter-bar {
            padding: 18px 22px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        /* Category Pills */
        .vp-cat-pills {
            display: inline-flex;
            background: #e2e8f0;
            padding: 4px;
            border-radius: 10px;
            gap: 4px;
            flex-wrap: wrap;
        }

        .vp-cat-pill {
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            color: #475569;
            text-decoration: none;
            transition: var(--vp-transition);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .vp-cat-pill:hover {
            color: #0f172a;
            text-decoration: none;
        }

        .vp-cat-pill.active {
            background: #ffffff;
            color: #0f172a;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.08);
        }

        .vp-cat-pill.active.pill-rent {
            color: #059669;
        }

        /* Form Inputs */
        .vp-select {
            border-radius: 10px !important;
            border: 1px solid #cbd5e1 !important;
            font-size: 0.88rem;
            height: 40px;
            background-color: #ffffff;
            transition: var(--vp-transition);
        }

        .vp-select:focus {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12) !important;
        }

        /* Modern Table */
        .vp-table-wrap {
            padding: 20px 22px;
        }

        .vp-table {
            width: 100% !important;
            border-collapse: separate !important;
            border-spacing: 0 !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px;
            overflow: hidden;
        }

        .vp-table thead th {
            background: #f1f5f9 !important;
            color: #334155 !important;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 13px 16px !important;
            border-bottom: 1px solid #e2e8f0 !important;
            border-top: none !important;
            border-left: none !important;
            border-right: none !important;
        }

        .vp-table tbody td {
            padding: 13px 16px !important;
            vertical-align: middle !important;
            border-top: none !important;
            border-left: none !important;
            border-right: none !important;
            border-bottom: 1px solid #f1f5f9 !important;
            font-size: 0.9rem;
            color: #1e293b;
        }

        .vp-table tbody tr {
            transition: background-color 0.15s ease;
        }

        .vp-table tbody tr:hover {
            background-color: #f8fafc !important;
        }

        .vp-table tfoot td {
            background: #f8fafc !important;
            padding: 14px 16px !important;
            border-top: 2px solid #cbd5e1 !important;
            border-bottom: none !important;
            border-left: none !important;
            border-right: none !important;
        }

        .vp-avatar {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.82rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            flex-shrink: 0;
        }

        .vp-month-badge {
            background: #eef2ff;
            color: #4338ca;
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 700;
            display: inline-block;
        }

        .vp-amount-text {
            font-weight: 800;
            color: #059669;
            font-size: 0.95rem;
        }

        .vp-view-btn {
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
            padding: 6px 13px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            transition: var(--vp-transition);
        }

        .vp-view-btn:hover {
            background: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
        }

        /* Modal Polish */
        .vp-modal-content {
            border-radius: 16px;
            border: none;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.2);
        }

        .vp-modal-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #ffffff;
            padding: 18px 24px;
            border-bottom: none;
        }

        .vp-modal-header .close {
            color: #cbd5e1;
            opacity: 1;
            text-shadow: none;
        }

        .vp-modal-header .close:hover {
            color: #ffffff;
        }

        /* DataTables Tweaks */
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            padding: 5px 10px;
            margin-left: 8px;
        }

        .dataTables_wrapper .dataTables_length select {
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            padding: 4px 8px;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main-content'); ?>
    <?php
        $monthMap = [
            'Jan' => 'January', 'Feb' => 'February', 'Mar' => 'March', 'Apr' => 'April',
            'May' => 'May', 'Jun' => 'June', 'Jul' => 'July', 'Aug' => 'August',
            'Sep' => 'September', 'Oct' => 'October', 'Nov' => 'November', 'Dec' => 'December'
        ];
        $totalSum = collect($payments)->sum('total_amount');
        $activeCategory = $selectedCategory ?? '';
        $scopeLabel = $activeCategory === 'rent_utility'
            ? 'Rent & Utility Only'
            : ($activeCategory === 'food_meal' ? 'Food & Meal Only' : 'All Payment Types');
    ?>

    <section class="content">
        <div class="container-fluid">

            <!-- Hero Header -->
            <div class="vp-hero">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div class="mb-3 mb-md-0">
                        <h3 class="vp-hero-title">
                            <i class="fas fa-receipt mr-2 text-success"></i>
                            <?php echo e($activeCategory === 'rent_utility' ? "Members' Rent & Utility Statements" : 'All Member Payments'); ?>

                        </h3>
                        <p class="vp-hero-sub">
                            <?php if($activeCategory === 'rent_utility'): ?>
                                Showing only House Rent, Room Advance & Utility Bill collections (meal payments excluded).
                            <?php elseif($activeCategory === 'food_meal'): ?>
                                Showing only Food Advance & Meal Payment collections.
                            <?php else: ?>
                                Complete overview of member monthly payments, rent, utility bills, and meal collections.
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="d-flex flex-wrap" style="gap: 10px;">
                        <?php if($isSuperAdmin || $isManager || !empty($myResponsibilities)): ?>
                            <a href="<?php echo e(route('admin.add.payment')); ?>" class="vp-hero-btn btn-emerald">
                                <i class="fas fa-plus-circle"></i> Add Payment
                            </a>
                            <a href="<?php echo e(route('admin.payment.downloadPdf', request()->all())); ?>" class="vp-hero-btn">
                                <i class="far fa-file-pdf text-danger"></i> Download PDF
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php echo $__env->make('admin.includes.message', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- Summary KPI Strip -->
            <div class="row mb-4">
                <div class="col-md-4 col-sm-6 mb-3 mb-md-0">
                    <div class="vp-stat-card">
                        <div>
                            <div class="vp-stat-label">Total Collected (Filtered)</div>
                            <div class="vp-stat-value text-success" id="summaryGrandTotal">
                                Tk. <?php echo e(number_format($totalSum, 2)); ?>

                            </div>
                        </div>
                        <div class="vp-stat-icon vp-icon-emerald">
                            <i class="fas fa-coins"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 mb-3 mb-md-0">
                    <div class="vp-stat-card">
                        <div>
                            <div class="vp-stat-label">Grouped Statements</div>
                            <div class="vp-stat-value"><?php echo e(count($payments)); ?> Records</div>
                        </div>
                        <div class="vp-stat-icon vp-icon-indigo">
                            <i class="fas fa-layer-group"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="vp-stat-card">
                        <div>
                            <div class="vp-stat-label">Active Filter Scope</div>
                            <div class="vp-stat-value" style="font-size: 1.15rem;">
                                <span class="badge <?php echo e($activeCategory === 'rent_utility' ? 'badge-success' : ($activeCategory === 'food_meal' ? 'badge-warning' : 'badge-primary')); ?> px-3 py-2"
                                    style="border-radius: 8px;">
                                    <?php echo e($scopeLabel); ?>

                                </span>
                            </div>
                        </div>
                        <div class="vp-stat-icon vp-icon-amber">
                            <i class="fas fa-filter"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Table Panel -->
            <div class="vp-panel">
                <!-- Filter Bar -->
                <div class="vp-filter-bar">
                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-3" style="gap: 12px;">
                        <!-- Category Switcher Pills -->
                        <div class="vp-cat-pills">
                            <a href="<?php echo e(route('admin.view.payment', array_filter(['member_id' => $selectedMemberId]))); ?>"
                                class="vp-cat-pill <?php echo e($activeCategory === '' ? 'active' : ''); ?>">
                                <i class="fas fa-th-list"></i> All Payments
                            </a>
                            <a href="<?php echo e(route('admin.view.payment', array_filter(['category' => 'rent_utility', 'member_id' => $selectedMemberId]))); ?>"
                                class="vp-cat-pill pill-rent <?php echo e($activeCategory === 'rent_utility' ? 'active' : ''); ?>">
                                <i class="fas fa-home"></i> Rent & Utility Only
                            </a>
                            <a href="<?php echo e(route('admin.view.payment', array_filter(['category' => 'food_meal', 'member_id' => $selectedMemberId]))); ?>"
                                class="vp-cat-pill <?php echo e($activeCategory === 'food_meal' ? 'active' : ''); ?>">
                                <i class="fas fa-utensils"></i> Food & Meal Only
                            </a>
                        </div>

                        <?php if($selectedMemberId || $selectedPaymentType || $activeCategory): ?>
                            <a href="<?php echo e(route('admin.view.payment')); ?>" class="btn btn-sm btn-outline-secondary font-weight-bold"
                                style="border-radius: 8px;">
                                <i class="fas fa-undo-alt mr-1"></i> Reset All Filters
                            </a>
                        <?php endif; ?>
                    </div>

                    <form method="GET" action="<?php echo e(route('admin.view.payment')); ?>" id="filterForm">
                        <?php if($activeCategory !== ''): ?>
                            <input type="hidden" name="category" value="<?php echo e($activeCategory); ?>">
                        <?php endif; ?>
                        <div class="row">
                            <div class="col-md-4 mb-2 mb-md-0">
                                <label class="small font-weight-bold text-muted mb-1">
                                    <i class="fas fa-user mr-1"></i> Filter by Member
                                </label>
                                <select name="member_id" class="form-control vp-select"
                                    onchange="document.getElementById('filterForm').submit()">
                                    <option value="">All Members</option>
                                    <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($member->id); ?>"
                                            <?php echo e($selectedMemberId == $member->id ? 'selected' : ''); ?>>
                                            <?php echo e($member->full_name); ?>

                                            <?php if($member->role_id == 2): ?>
                                                (Manager)
                                            <?php endif; ?>
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="small font-weight-bold text-muted mb-1">
                                    <i class="fas fa-tags mr-1"></i> Filter by Payment Type
                                </label>
                                <select name="payment_type" class="form-control vp-select"
                                    onchange="document.getElementById('filterForm').submit()">
                                    <option value="">
                                        <?php echo e($activeCategory === 'rent_utility' ? 'All Rent & Utility Types' : 'All Payment Types'); ?>

                                    </option>
                                    <?php $__currentLoopData = $paymentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e($selectedPaymentType == $key || ($key == 'meal_expanse_payment' && $selectedPaymentType == 'meal_payment') ? 'selected' : ''); ?>>
                                            <?php echo e($label); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div class="vp-table-wrap">
                    <div class="table-responsive">
                        <table id="all-category" class="table vp-table">
                            <thead>
                                <tr>
                                    <th style="width: 70px;">SL</th>
                                    <th>Member's Name</th>
                                    <th>Month</th>
                                    <th>Total Payment Amount</th>
                                    <th class="text-center" style="width: 130px;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                                <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="text-muted font-weight-bold"><?php echo e($loop->iteration); ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="vp-avatar">
                                                    <?php echo e(strtoupper(substr($payment['full_name'], 0, 1))); ?>

                                                </span>
                                                <strong class="text-dark"><?php echo e($payment['full_name']); ?></strong>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="vp-month-badge">
                                                <i class="far fa-calendar-alt mr-1"></i>
                                                <?php echo e($monthMap[$payment['month']] ?? $payment['month']); ?>

                                            </span>
                                        </td>
                                        <td data-order="<?php echo e($payment['total_amount']); ?>">
                                            <span class="vp-amount-text">
                                                Tk. <?php echo e(number_format($payment['total_amount'], 2)); ?>

                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn vp-view-btn view-payment-details"
                                                data-member-id="<?php echo e($payment['member_id']); ?>"
                                                data-member-name="<?php echo e($payment['full_name']); ?>"
                                                data-month="<?php echo e($monthMap[$payment['month']] ?? $payment['month']); ?>">
                                                <i class="fas fa-eye mr-1"></i> View
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                            No payments found for this filter.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right font-weight-bold text-dark" style="font-size: 15px;">
                                        Grand Total:
                                    </td>
                                    <td style="font-size: 15px;" id="tableGrandTotal">
                                        <strong class="text-success">Tk. <?php echo e(number_format($totalSum, 2)); ?></strong>
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Payment Details Modal -->
    <div class="modal fade" id="paymentDetailsModal" tabindex="-1" role="dialog"
        aria-labelledby="paymentDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content vp-modal-content">
                <div class="modal-header vp-modal-header">
                    <h5 class="modal-title font-weight-bold" id="paymentDetailsModalLabel">
                        <i class="fas fa-file-invoice-dollar mr-2 text-success"></i>
                        Payment Details - <span id="modalMemberName"></span> (<span id="modalMonth"></span>)
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>SL</th>
                                    <th>Bill Type</th>
                                    <th>Payment Date</th>
                                    <th class="text-right">Amount</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                    <th class="text-nowrap">Action</th>
                                </tr>
                            </thead>
                            <tbody id="paymentDetailsBody">
                                <tr>
                                    <td colspan="7" class="text-center">Loading...</td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-light font-weight-bold">
                                <tr>
                                    <td colspan="3" class="text-right">Total:</td>
                                    <td class="text-right text-success" id="paymentTotal">Tk. 0.00</td>
                                    <td colspan="3"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary font-weight-bold" style="border-radius: 8px;"
                        data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_js'); ?>
    <script>
        // SweetAlert2
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            type: 'success',
            showConfirmButton: false,
            timer: 3000
        });

        $(document).ready(function() {
            $("#all-category").DataTable({
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api();
                    var total = api
                        .column(3, { search: 'applied' })
                        .data()
                        .reduce(function(acc, val) {
                            var clean = String(val).replace(/Tk\.?/gi, '').replace(/,/g, '').replace(/<[^>]*>/g, '').trim();
                            var num = parseFloat(clean);
                            return acc + (isNaN(num) ? 0 : num);
                        }, 0);

                    var formatted = 'Tk. ' + total.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                    $('#tableGrandTotal').html('<strong class="text-success">' + formatted + '</strong>');
                    $('#summaryGrandTotal').text(formatted);
                }
            });

            // Store current member ID and month for reload
            var currentMemberId = null;
            var currentMonth = null;
            var paymentStatusChanged = false;
            var activeCategory = "<?php echo e($activeCategory); ?>";

            function loadPaymentDetails(memberId, month) {
                var paymentType = $('select[name="payment_type"]').val() || '';
                var url = "<?php echo e(url('payment/details')); ?>/" + memberId + "/" + encodeURIComponent(month);
                var params = [];
                if (paymentType) {
                    params.push("payment_type=" + encodeURIComponent(paymentType));
                }
                if (activeCategory) {
                    params.push("category=" + encodeURIComponent(activeCategory));
                }
                if (params.length > 0) {
                    url += "?" + params.join("&");
                }

                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            var html = '';
                            if (response.payments && response.payments.length > 0) {
                                response.payments.forEach(function(payment, index) {
                                    var statusBadge = payment.status == 1 ?
                                        '<span class="badge badge-success px-2 py-1">Paid</span>' :
                                        '<span class="badge badge-warning px-2 py-1">Pending</span>';

                                    // Action buttons (icon only)
                                    var actionButtons = '';
                                    if (payment.status == 0 && payment.can_approve) {
                                        actionButtons += '<button type="button" class="btn btn-success btn-xs approve-payment" data-id="' + payment.id + '" title="Approve Payment"><i class="fas fa-check"></i></button>';
                                    }
                                    if (payment.can_edit) {
                                        if (actionButtons) actionButtons += ' ';
                                        actionButtons += '<button type="button" class="btn btn-primary btn-xs edit-payment" data-id="' + payment.id + '" title="Edit Payment"><i class="fas fa-edit"></i></button>';
                                    }
                                    if (!actionButtons) {
                                        actionButtons = '-';
                                    }

                                    html += '<tr>';
                                    html += '<td>' + (index + 1) + '</td>';
                                    html += '<td><strong>' + payment.type + '</strong></td>';
                                    html += '<td><span class="badge badge-secondary">' + payment.date + '</span></td>';
                                    html += '<td class="text-right"><strong class="text-success">Tk. ' + parseFloat(payment.amount).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</strong></td>';
                                    html += '<td>' + statusBadge + '</td>';
                                    html += '<td>' + (payment.notes ? payment.notes : '-') + '</td>';
                                    html += '<td class="text-nowrap">' + actionButtons + '</td>';
                                    html += '</tr>';
                                });
                            } else {
                                html = '<tr><td colspan="7" class="text-center text-muted">No payments found for this month.</td></tr>';
                            }
                            $('#paymentDetailsBody').html(html);
                            $('#paymentTotal').text('Tk. ' + parseFloat(response.total).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                        } else {
                            $('#paymentDetailsBody').html('<tr><td colspan="7" class="text-center text-danger">Failed to load payment details.</td></tr>');
                        }
                    },
                    error: function() {
                        $('#paymentDetailsBody').html('<tr><td colspan="7" class="text-center text-danger">Error loading payment details. Please try again.</td></tr>');
                        Toast.fire({
                            type: 'error',
                            title: 'Failed to load payment details.'
                        });
                    }
                });
            }

            // Handle view payment details button click
            $(document).on('click', '.view-payment-details', function() {
                var memberId = $(this).data('member-id');
                var memberName = $(this).data('member-name');
                var month = $(this).data('month');

                currentMemberId = memberId;
                currentMonth = month;
                paymentStatusChanged = false;

                $('#modalMemberName').text(memberName);
                $('#modalMonth').text(month);
                $('#paymentDetailsBody').html('<tr><td colspan="7" class="text-center">Loading...</td></tr>');
                $('#paymentDetailsModal').modal('show');

                loadPaymentDetails(memberId, month);
            });

            // Handle approve payment button click
            $(document).on('click', '.approve-payment', function() {
                var paymentId = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to approve this payment?',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, Approve!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.value || result.isConfirmed) {
                        $.ajax({
                            url: "<?php echo e(url('payment/paymentStatus')); ?>/" + paymentId + "/1",
                            method: 'GET',
                            success: function(response) {
                                Toast.fire({
                                    type: 'success',
                                    title: 'Payment approved successfully!'
                                });
                                paymentStatusChanged = true;

                                if (currentMemberId && currentMonth) {
                                    loadPaymentDetails(currentMemberId, currentMonth);
                                }
                            },
                            error: function(xhr) {
                                var errorMsg = 'Failed to approve payment.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMsg = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    type: 'error',
                                    title: 'Error',
                                    text: errorMsg
                                });
                            }
                        });
                    }
                });
            });

            // Reload main table if any payment status changed when modal closes
            $('#paymentDetailsModal').on('hidden.bs.modal', function() {
                if (paymentStatusChanged) {
                    location.reload();
                }
            });

            // Handle edit payment button click
            $(document).on('click', '.edit-payment', function() {
                var paymentId = $(this).data('id');
                window.location.href = "<?php echo e(url('payment/editPayment')); ?>/" + paymentId;
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Herd\mass-management-system\resources\views/admin/payment/viewPayment.blade.php ENDPATH**/ ?>