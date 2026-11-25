@extends('admin.master')

@section('heading', 'Add Payment')
@section('title', 'Add Payment | MMS')

@section('custom_css')
    <style>
        :root {
            --ap-radius: 16px;
            --ap-transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Hero Banner */
        .ap-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%);
            border-radius: var(--ap-radius);
            color: #ffffff;
            padding: 24px 28px;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.22);
        }

        .ap-hero::after {
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

        .ap-hero-title {
            font-size: 1.45rem;
            font-weight: 700;
            margin-bottom: 4px;
            letter-spacing: -0.3px;
        }

        .ap-hero-sub {
            font-size: 0.88rem;
            color: #94a3b8;
            margin-bottom: 0;
        }

        .ap-hero-btn {
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
            transition: var(--ap-transition);
        }

        .ap-hero-btn:hover {
            background: rgba(255, 255, 255, 0.22);
            color: #ffffff;
            transform: translateY(-2px);
            text-decoration: none;
        }

        /* Main Form Panel */
        .ap-panel {
            background: #ffffff;
            border-radius: var(--ap-radius);
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.05);
            overflow: hidden;
            margin-bottom: 24px;
        }

        .ap-panel-head {
            padding: 18px 24px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .ap-panel-title {
            font-size: 1.02rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .ap-panel-body {
            padding: 26px 24px;
        }

        /* Form Fields */
        .ap-label {
            font-size: 0.82rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 7px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .ap-input,
        .ap-select {
            border-radius: 10px !important;
            border: 1px solid #cbd5e1 !important;
            font-size: 0.92rem;
            height: 44px;
            padding: 8px 14px;
            color: #0f172a;
            background-color: #ffffff;
            transition: var(--ap-transition);
        }

        .ap-textarea {
            border-radius: 10px !important;
            border: 1px solid #cbd5e1 !important;
            font-size: 0.92rem;
            padding: 10px 14px;
            color: #0f172a;
            transition: var(--ap-transition);
        }

        .ap-input:focus,
        .ap-select:focus,
        .ap-textarea:focus {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.14) !important;
            outline: none;
        }

        .ap-me-btn {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: #ffffff;
            border: none;
            border-radius: 0 10px 10px 0 !important;
            padding: 0 16px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: var(--ap-transition);
        }

        .ap-me-btn:hover {
            color: #ffffff;
            opacity: 0.92;
        }

        /* Dynamic Bill Breakdown Card */
        .ap-rent-box {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 14px;
            overflow: hidden;
        }

        .ap-rent-head {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            color: #ffffff;
            padding: 12px 18px;
            font-weight: 700;
            font-size: 0.92rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .ap-rent-grid {
            padding: 16px 18px;
        }

        .ap-rent-item {
            background: #ffffff;
            border: 1px solid #e0f2fe;
            border-radius: 10px;
            padding: 10px 14px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .ap-reduction-box {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 12px;
            padding: 14px 18px;
        }

        .ap-simple-due {
            background: #eef2ff;
            border: 1px solid #c7d2fe;
            color: #3730a3;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 0.92rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Submit Button */
        .ap-submit-btn {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 12px 28px;
            font-size: 0.95rem;
            font-weight: 700;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
            transition: var(--ap-transition);
            cursor: pointer;
        }

        .ap-submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.45);
        }
    </style>
@endsection

@section('main-content')
<section class="content">
    <div class="container-fluid">

        <!-- Hero Banner -->
        <div class="ap-hero">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <div class="mb-3 mb-md-0">
                    <h3 class="ap-hero-title">
                        <i class="fas fa-money-check-alt mr-2 text-success"></i> Record Member Payment
                    </h3>
                    <p class="ap-hero-sub">
                        Add meal payments, house rent, or monthly utility bill payments with automatic due calculation.
                    </p>
                </div>
                <div class="d-flex flex-wrap" style="gap: 10px;">
                    <a href="{{ route('admin.view.payment') }}" class="ap-hero-btn">
                        <i class="fas fa-list-ul text-info"></i> All Payments
                    </a>
                    <a href="{{ route('admin.view.payment', ['category' => 'rent_utility']) }}" class="ap-hero-btn">
                        <i class="fas fa-home text-success"></i> Rent & Utility
                    </a>
                </div>
            </div>
        </div>

        @include('admin.includes.message')

        <!-- Payment Form Panel -->
        <div class="ap-panel">
            <div class="ap-panel-head">
                <h5 class="ap-panel-title">
                    <i class="fas fa-file-invoice-dollar text-primary"></i>
                    Payment Entry Details
                </h5>
                <span class="badge badge-light border text-muted px-3 py-1" style="border-radius: 50px;">
                    <i class="far fa-calendar-check mr-1"></i> {{ \Carbon\Carbon::now()->format('F Y') }}
                </span>
            </div>

            <div class="ap-panel-body">
                <form method="POST" enctype="multipart/form-data" id="form" autocomplete="off">
                    @csrf
                    <div class="row">
                        <!-- Member Name -->
                        <div class="form-group col-md-6 mb-4">
                            <label for="member_id" class="ap-label">
                                <i class="fas fa-user text-primary"></i> Member's Name <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <select id="member_id" class="custom-select ap-select" style="border-radius: 10px 0 0 10px !important;" name="member_id" autocomplete="off">
                                    <option value="">-- Select Member Name --</option>
                                    @foreach ($members as $member)
                                        <option value="{{ $member->id }}" @if(session('member_id') == $member->id) selected @endif>
                                            {{ $member->full_name }} @if($member->role_id == 2) (Manager) @endif
                                        </option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <button type="button" class="btn ap-me-btn" id="selectMyselfPayment" title="Select Myself">
                                        <i class="fas fa-user-check mr-1"></i> Me
                                    </button>
                                </div>
                            </div>
                            @if ($errors->has('member_id'))
                                <small class="text-danger font-weight-bold">{{ $errors->first('member_id') }}</small>
                            @endif
                        </div>

                        <!-- Payment Type -->
                        <div class="form-group col-md-6 mb-4">
                            <label for="payment_type" class="ap-label">
                                <i class="fas fa-tags text-indigo"></i> Payment Type <span class="text-danger">*</span>
                            </label>
                            <select class="form-control ap-select" id="payment_type" name="payment_type" required autocomplete="off">
                                <option value="">-- Select Payment Type --</option>
                                @php
                                    $allPaymentTypes = [
                                        'meal_expanse_payment' => 'Meal Payment',
                                        'room_rent' => 'House Rent Payment',
                                        'room_advance' => 'Member Ways Amount (Room Advance)',
                                        'water' => 'Water Bill Payment',
                                        'internet' => 'Internet Bill Payment',
                                        'electricity' => 'Electricity Bill Payment',
                                        'gas' => 'Gas Bill Payment',
                                        'bua_moyla' => 'Bua & Moyla Bill Payment',
                                    ];

                                    $allowedTypes = $allPaymentTypes;
                                    if (!$isSuperAdmin && !$isManager && !empty($myResponsibilities)) {
                                        $allowedTypes = array_filter($allPaymentTypes, function($key) use ($myResponsibilities) {
                                            return in_array($key, $myResponsibilities);
                                        }, ARRAY_FILTER_USE_KEY);
                                    }
                                @endphp
                                @foreach($allowedTypes as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('payment_type'))
                                <small class="text-danger font-weight-bold">{{ $errors->first('payment_type') }}</small>
                            @endif
                            @if (!$isSuperAdmin && !$isManager && empty($myResponsibilities))
                                <small class="text-warning font-weight-bold d-block mt-1">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> You are not assigned to any bill type. Please contact admin.
                                </small>
                            @endif
                        </div>

                        <!-- House Rent Payment Breakdown -->
                        <div class="form-group col-md-12 mb-4" id="bill_amount_info" style="display: none;">
                            <div class="ap-rent-box">
                                <div class="ap-rent-head">
                                    <i class="fas fa-info-circle"></i> House Rent Payment Details
                                </div>
                                <div class="ap-rent-grid">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="ap-rent-item">
                                                <span class="text-muted small font-weight-bold">Monthly Rent</span>
                                                <strong id="monthly_rent_amount" class="text-dark">Tk. 0.00</strong>
                                            </div>
                                        </div>
                                        <div class="col-md-6" id="extra_reduction_info" style="display: none;">
                                            <div class="ap-rent-item">
                                                <span class="text-muted small font-weight-bold">Available Extra Reduction</span>
                                                <strong id="extra_reduction_amount" class="text-success">Tk. 0.00</strong>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="ap-rent-item">
                                                <span class="text-muted small font-weight-bold">Already Paid</span>
                                                <strong id="already_paid_amount" class="text-info">Tk. 0.00</strong>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="ap-rent-item">
                                                <span class="text-danger small font-weight-bold">Due Amount (Full Rent)</span>
                                                <strong id="full_rent_due" class="text-danger">Tk. 0.00</strong>
                                            </div>
                                        </div>
                                        <div class="col-md-6" id="reduction_applied_info" style="display: none;">
                                            <div class="ap-rent-item">
                                                <span class="text-success small font-weight-bold">Reduction Applied</span>
                                                <strong id="reduction_applied_amount" class="text-success">Tk. 0.00</strong>
                                            </div>
                                        </div>
                                        <div class="col-md-6" id="final_amount_info" style="display: none;">
                                            <div class="ap-rent-item">
                                                <span class="text-primary small font-weight-bold">Final Payment Amount</span>
                                                <strong id="final_payment_amount" class="text-primary">Tk. 0.00</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Extra Reduction Checkbox -->
                        <div class="form-group col-md-12 mb-4" id="use_extra_reduction_field" style="display: none;">
                            <div class="ap-reduction-box">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" id="use_extra_reduction" name="use_extra_reduction" value="1">
                                    <label class="form-check-label font-weight-bold text-dark" for="use_extra_reduction">
                                        Apply Extra Payment Reduction
                                    </label>
                                    <small class="form-text text-muted">
                                        If checked, available extra payment reduction amount will be deducted from house rent.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Simple Bill Due Alert -->
                        <div class="form-group col-md-12 mb-4" id="bill_amount_info_simple" style="display: none;">
                            <div class="ap-simple-due">
                                <span class="font-weight-bold">
                                    <i class="fas fa-calculator mr-2"></i> Bill Amount Due for Selected Month:
                                </span>
                                <strong id="bill_amount_due_simple" style="font-size: 1.05rem;">Tk. 0.00</strong>
                            </div>
                        </div>

                        <!-- Payment Amount -->
                        <div class="form-group col-md-4 mb-4">
                            <label for="amount" class="ap-label">
                                <i class="fas fa-coins text-success"></i> Payment Amount (Tk.) <span class="text-danger">*</span>
                            </label>
                            <input class="form-control ap-input font-weight-bold" type="number" id="amount" name="amount" placeholder="0.00" step="0.01" min="0.01" required autocomplete="off">
                            @if ($errors->has('amount'))
                                <small class="text-danger font-weight-bold">{{ $errors->first('amount') }}</small>
                            @endif
                        </div>

                        <!-- Payment Date -->
                        <div class="form-group col-md-4 mb-4">
                            <label for="date" class="ap-label">
                                <i class="far fa-calendar-alt text-info"></i> Payment Date
                            </label>
                            <input class="form-control ap-input" type="date" id="date" name="date" value="{{ date('Y-m-d') }}" autocomplete="off">
                            @if ($errors->has('date'))
                                <small class="text-danger font-weight-bold">{{ $errors->first('date') }}</small>
                            @endif
                        </div>

                        <!-- Month -->
                        <div class="form-group col-md-4 mb-4">
                            <label for="month" class="ap-label">
                                <i class="fas fa-calendar-week text-warning"></i> Month <span class="text-danger">*</span>
                            </label>
                            <select class="form-control ap-select" id="month" name="month" required autocomplete="off">
                                <option value="">-- Select Month --</option>
                                @php
                                    $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                                    $currentMonth = \Carbon\Carbon::now()->format('F');
                                @endphp
                                @foreach($months as $m)
                                    <option value="{{ $m }}" {{ $currentMonth == $m ? 'selected' : '' }}>{{ $m }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('month'))
                                <small class="text-danger font-weight-bold">{{ $errors->first('month') }}</small>
                            @endif
                        </div>

                        <!-- Notes -->
                        <div class="form-group col-md-12 mb-4">
                            <label for="notes" class="ap-label">
                                <i class="far fa-sticky-note text-secondary"></i> Notes (Optional)
                            </label>
                            <textarea class="form-control ap-textarea" id="notes" name="notes" rows="2" placeholder="Add any notes about this payment..." autocomplete="off"></textarea>
                            @if ($errors->has('notes'))
                                <small class="text-danger font-weight-bold">{{ $errors->first('notes') }}</small>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex justify-content-end align-items-center pt-2 border-top">
                        <button class="ap-submit-btn" type="submit" id="submit" name="submit">
                            <i class="fas fa-check-circle mr-2"></i> Save Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div><!-- /.container-fluid -->
</section>
@endsection

{{-- data store with ajax --}}
@section('custom_js')
<script>
    // Initialize Select2 Elements
    $('.select2').select2();
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    });

    // SweetAlert2
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        icon: 'success',
        showConfirmButton: false,
        timer: 3000
    });

    // Pass the CSRF token for post method
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        // Select myself (admin) for payment entry
        $('#selectMyselfPayment').on('click', function() {
            var myMemberId = '{{ session("member_id") }}';
            $('#member_id').val(myMemberId).trigger('change');
        });

        // Function to get payment amount for selected member and payment type
        function getBillAmount() {
            var memberId = $('#member_id').val();
            var paymentType = $('#payment_type').val();
            var month = $('#month').val();

            var isMealOrAdvance = paymentType === 'food_advance' || paymentType === 'meal_expanse' || paymentType === 'meal_expanse_payment' || paymentType === 'meal_payment';
            if (!month && paymentType && !isMealOrAdvance && memberId) {
                var currentMonth = new Date().toLocaleString('default', { month: 'long' });
                $('#month').val(currentMonth);
                month = currentMonth;
            }

            if (paymentType && !isMealOrAdvance && memberId && month) {
                $.ajax({
                    url: '{{ route("admin.payment.getBillAmount") }}',
                    method: 'POST',
                    data: {
                        member_id: memberId,
                        bill_type: paymentType,
                        month: month
                    },
                    success: function(response) {
                        if (response.success) {
                            if (paymentType === 'room_rent' && response.extra_reduction !== undefined) {
                                var monthlyRent = parseFloat(response.total_bill || 0);
                                var extraReduction = parseFloat(response.extra_reduction || 0);
                                var alreadyPaid = parseFloat(response.paid || 0);
                                var finalRent = parseFloat(response.final_rent || 0);

                                $('#use_extra_reduction').data('monthly-rent', monthlyRent);
                                $('#use_extra_reduction').data('extra-reduction', extraReduction);
                                $('#use_extra_reduction').data('already-paid', alreadyPaid);
                                $('#use_extra_reduction').data('final-rent', finalRent);

                                $('#monthly_rent_amount').text('Tk. ' + monthlyRent.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

                                if (extraReduction > 0) {
                                    $('#extra_reduction_amount').text('Tk. ' + extraReduction.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                                    $('#extra_reduction_info').show();
                                    $('#use_extra_reduction_field').show();
                                } else {
                                    $('#extra_reduction_info').hide();
                                    $('#use_extra_reduction_field').hide();
                                }

                                $('#already_paid_amount').text('Tk. ' + alreadyPaid.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

                                var fullRentDue = Math.max(0, monthlyRent - alreadyPaid);
                                $('#full_rent_due').text('Tk. ' + fullRentDue.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

                                var useReduction = $('#use_extra_reduction').is(':checked');
                                calculateRoomRentAmount(useReduction, monthlyRent, extraReduction, alreadyPaid);

                                $('#bill_amount_info').show();
                                $('#bill_amount_info_simple').hide();
                            } else {
                                $('#bill_amount_due_simple').text('Tk. ' + parseFloat(response.amount || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                                $('#bill_amount_info_simple').show();
                                $('#bill_amount_info').hide();
                                $('#amount').val(response.amount || 0);
                            }
                        } else {
                            $('#bill_amount_info').hide();
                            $('#bill_amount_info_simple').hide();
                            $('#bill_amount_due_simple').text('Tk. 0.00');
                            $('#amount').val('');
                        }
                    },
                    error: function() {
                        $('#bill_amount_info').hide();
                        $('#bill_amount_info_simple').hide();
                        $('#bill_amount_due_simple').text('Tk. 0.00');
                        $('#amount').val('');
                    }
                });
            } else {
                $('#bill_amount_info').hide();
                $('#bill_amount_info_simple').hide();
                $('#bill_amount_due_simple').text('Tk. 0.00');
                if (isMealOrAdvance || !paymentType) {
                    $('#amount').val('');
                }
            }
        }

        function calculateRoomRentAmount(useReduction, monthlyRent, extraReduction, alreadyPaid) {
            var fullRentDue = Math.max(0, monthlyRent - alreadyPaid);

            if (useReduction && extraReduction > 0) {
                var finalRent = Math.max(0, monthlyRent - extraReduction);
                var paymentAmount = Math.max(0, finalRent - alreadyPaid);

                $('#reduction_applied_amount').text('Tk. ' + extraReduction.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $('#reduction_applied_info').show();
                $('#final_amount_info').show();
                $('#final_payment_amount').text('Tk. ' + paymentAmount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

                $('#amount').val(paymentAmount.toFixed(2));
            } else {
                $('#reduction_applied_info').hide();
                $('#final_amount_info').hide();
                $('#amount').val(fullRentDue.toFixed(2));
            }
        }

        $(document).on('change', '#use_extra_reduction', function() {
            var paymentType = $('#payment_type').val();
            if (paymentType === 'room_rent') {
                var monthlyRent = parseFloat($(this).data('monthly-rent') || 0);
                var extraReduction = parseFloat($(this).data('extra-reduction') || 0);
                var alreadyPaid = parseFloat($(this).data('already-paid') || 0);
                var useReduction = $(this).is(':checked');

                calculateRoomRentAmount(useReduction, monthlyRent, extraReduction, alreadyPaid);
            }
        });

        $('#member_id, #payment_type').on('change', function() {
            if ($('#payment_type').val() !== 'room_rent') {
                $('#use_extra_reduction_field').hide();
                $('#use_extra_reduction').prop('checked', false);
            }
            getBillAmount();
        });

        $('#month').on('change', function() {
            if ($('#payment_type').val() !== 'room_rent') {
                $('#use_extra_reduction_field').hide();
                $('#use_extra_reduction').prop('checked', false);
            }
            getBillAmount();
        });

        $('#form').on("submit", function(event) {
            event.preventDefault();

            var amount = parseFloat($('#amount').val()) || 0;
            if (amount <= 0 || isNaN(amount)) {
                Toast.fire({
                    type: 'error',
                    title: 'Payment amount must be greater than 0.'
                });
                $('#amount').focus();
                return false;
            }

            var form = new FormData(this);

            $.ajax({
                url: "{{ route('admin.store.payment') }}",
                data: form,
                contentType: false,
                cache: false,
                processData: false,
                method: "POST",
                success: function(response) {
                    var message = 'Payment successfully saved.';
                    if (typeof response === 'string' && response !== 'success' && response !== 'error') {
                        message = response;
                    } else if (response == "success") {
                        message = 'Payment successfully saved.';
                    }

                    Toast.fire({
                        type: 'success',
                        title: message
                    });

                    $("#form")[0].reset();
                    $('#bill_amount_info').hide();
                    $('#bill_amount_info_simple').hide();
                    $('#use_extra_reduction_field').hide();
                    $('#use_extra_reduction').prop('checked', false);
                },
                error: function() {
                    Toast.fire({
                        type: 'error',
                        title: 'Something Error Found, Please try again.'
                    });
                }
            });
        });
    });
</script>
@endsection
