

<?php $__env->startSection('heading', 'All Payments'); ?>
<?php $__env->startSection('title', 'view payment'); ?>

<?php $__env->startSection('main-content'); ?>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3 class="font-weight-bolder">All Payments</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <?php if($isSuperAdmin || $isManager || !empty($myResponsibilities)): ?>
                            <a href="<?php echo e(route('admin.add.payment')); ?>" class="viewall"><i class="far fa-money-bill-alt"></i> Add Payment</a>
                        <?php endif; ?>
                        <?php if($isSuperAdmin || $isManager || !empty($myResponsibilities)): ?>
                            <a href="<?php echo e(route('admin.payment.downloadPdf', request()->all())); ?>" class="viewall bg-cyan"><i class="far fa-file-pdf"></i> Download pdf</a>
                        <?php endif; ?>
                    </div>
                </div>
 
                <?php echo $__env->make('admin.includes.message', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form method="GET" action="<?php echo e(route('admin.view.payment')); ?>" id="filterForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold"><i class="fas fa-filter"></i> Filter by Member</label>
                                            <select name="member_id" class="form-control" onchange="document.getElementById('filterForm').submit()" style="border-radius: 5px;">
                                                <option value="">All Members</option>
                                                <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($member->id); ?>" <?php echo e($selectedMemberId == $member->id ? 'selected' : ''); ?>>
                                                        <?php echo e($member->full_name); ?>

                                                        <?php if($member->role_id == 2): ?>
                                                            (Manager)
                                                        <?php endif; ?>
                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold"><i class="fas fa-filter"></i> Filter by Payment Type</label>
                                            <select name="payment_type" class="form-control" onchange="document.getElementById('filterForm').submit()" style="border-radius: 5px;">
                                                <option value="">All Payment Types</option>
                                                <?php $__currentLoopData = $paymentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($key); ?>" <?php echo e(($selectedPaymentType == $key || ($key == 'meal_expanse_payment' && $selectedPaymentType == 'meal_payment')) ? 'selected' : ''); ?>>
                                                        <?php echo e($label); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <table id="all-category" class="table table-bordered table-hover">
                        <thead class="bg-olive">
                            <tr>
                                <th>SL NO</th>
                                <th>Member's Name</th>
                                <th>Month</th>
                                <th>Total Payment Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                            <?php
                                $monthMap = [
                                    'Jan' => 'January', 'Feb' => 'February', 'Mar' => 'March', 'Apr' => 'April',
                                    'May' => 'May', 'Jun' => 'June', 'Jul' => 'July', 'Aug' => 'August',
                                    'Sep' => 'September', 'Oct' => 'October', 'Nov' => 'November', 'Dec' => 'December'
                                ];
                                $totalSum = collect($payments)->sum('total_amount');
                            ?>
                            <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($loop->iteration); ?></td>
                                    <td><strong><?php echo e($payment['full_name']); ?></strong></td>
                                    <td><strong><?php echo e($monthMap[$payment['month']] ?? $payment['month']); ?></strong></td>
                                    <td data-order="<?php echo e($payment['total_amount']); ?>"><strong>Tk. <?php echo e(number_format($payment['total_amount'], 2)); ?></strong></td>
                                    <td style="width: 120px">
                                        <button type="button" class="btn btn-info btn-xs view-payment-details" 
                                                data-member-id="<?php echo e($payment['member_id']); ?>" 
                                                data-member-name="<?php echo e($payment['full_name']); ?>"
                                                data-month="<?php echo e($monthMap[$payment['month']] ?? $payment['month']); ?>">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center">No payments found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot class="bg-light font-weight-bold">
                            <tr>
                                <td colspan="3" class="text-right" style="font-size: 15px;">Total:</td>
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
    </section>

    <!-- Payment Details Modal -->
    <div class="modal fade" id="paymentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="paymentDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="paymentDetailsModalLabel">Payment Details - <span id="modalMemberName"></span> (<span id="modalMonth"></span>)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
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
                                    <td class="text-right" id="paymentTotal">Tk. 0.00</td>
                                    <td colspan="3"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_js'); ?>
    <script>
        //  SweetAlert2 
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            type: 'success',
            showConfirmButton: false,
            timer: 3000
        });

        $(document).ready(function(){
            $("#all-category").DataTable({
                "footerCallback": function (row, data, start, end, display) {
                    var api = this.api();
                    var total = api
                        .column(3, { search: 'applied' })
                        .data()
                        .reduce(function (acc, val) {
                            var clean = String(val).replace(/Tk\.?/gi, '').replace(/,/g, '').replace(/<[^>]*>/g, '').trim();
                            var num = parseFloat(clean);
                            return acc + (isNaN(num) ? 0 : num);
                        }, 0);

                    $('#tableGrandTotal').html('<strong class="text-success">Tk. ' + total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</strong>');
                }
            });

            // Store current member ID and month for reload
            var currentMemberId = null;
            var currentMonth = null;
            var paymentStatusChanged = false;

            function loadPaymentDetails(memberId, month) {
                var paymentType = $('select[name="payment_type"]').val() || '';
                var url = "<?php echo e(url('payment/details')); ?>/" + memberId + "/" + encodeURIComponent(month);
                if (paymentType) {
                    url += "?payment_type=" + encodeURIComponent(paymentType);
                }
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            var html = '';
                            if (response.payments && response.payments.length > 0) {
                                response.payments.forEach(function(payment, index) {
                                    var statusBadge = payment.status == 1 
                                        ? '<span class="badge badge-success">Paid</span>' 
                                        : '<span class="badge badge-warning">Pending</span>';
                                    
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
                                    html += '<td class="text-right"><strong class="text-success">Tk. ' + parseFloat(payment.amount).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</strong></td>';
                                    html += '<td>' + statusBadge + '</td>';
                                    html += '<td>' + (payment.notes ? payment.notes : '-') + '</td>';
                                    html += '<td class="text-nowrap">' + actionButtons + '</td>';
                                    html += '</tr>';
                                });
                            } else {
                                html = '<tr><td colspan="7" class="text-center">No payments found for this month.</td></tr>';
                            }
                            $('#paymentDetailsBody').html(html);
                            $('#paymentTotal').text('Tk. ' + parseFloat(response.total).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
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
                
                // Store for reload
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
                                
                                // Reload payment details in modal
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
            $('#paymentDetailsModal').on('hidden.bs.modal', function () {
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