@extends('admin.master')

@section('heading', 'Add-payment')
@section('title', 'Payment')
@section('main-content')
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="card">
            <div class="row">
                <div class="card-header col-md-6 col-6">
                    <h3 class="font-weight-bolder">Add Payment</h3>
                </div>
                <div class="card-header col-md-6 col-6 text-right">
                    <a href="{{route('admin.view.payment')}}" class="viewall"><i class="fas fa-money-bill-alt"></i> All Payments</a>
                </div>
            </div>
            @include('admin.includes.message')
            <div class="card-body">
                <form method="POST"  enctype="multipart/form-data" id="form" autocomplete="off">
                    @csrf
                       <div class="row">
                           <div class="form-group col-md-6">
                               <label for="member_id">Member's Name</label>
                               <div class="input-group">
                                   <select id="member_id" class="custom-select" name="member_id" autocomplete="off">
                                       <option value="">--select member name--</option>
                                       @foreach ($members as $member)
                                       <option value="{{$member->id}}" @if(session('member_id') == $member->id) selected @endif>{{$member->full_name}} @if($member->role_id == 2) (Manager) @endif</option>
                                       @endforeach
                                   </select>
                                   <div class="input-group-append">
                                       <button type="button" class="btn btn-info" id="selectMyselfPayment" title="Select Myself">
                                           <i class="fas fa-user"></i> Me
                                       </button>
                                   </div>
                               </div>
                               @if ($errors->has('member_id'))
                                   <p class="text-danger">{{ $errors->first('member_id') }}</p>
                               @endif
                           </div>

                           <div class="form-group col-md-6">
                               <label for="payment_type">Payment Type</label>
                               <select class="form-control" id="payment_type" name="payment_type" required autocomplete="off">
                                   <option value="">--Select Payment Type--</option>
                                   <option value="food_advance">Meal Payment</option>
                                   <option value="room_rent">House Rent Payment</option>
                                   <option value="room_advance">Member Ways Amount (Room Advance)</option>
                                   <option value="water">Water Bill Payment</option>
                                   <option value="internet">Internet Bill Payment</option>
                                   <option value="electricity">Electricity Bill Payment</option>
                                   <option value="gas">Gas Bill Payment</option>
                                   <option value="bua_moyla">Bua & Moyla Bill Payment</option>
                               </select>
                               @if ($errors->has('payment_type'))
                                   <p class="text-danger">{{ $errors->first('payment_type') }}</p>
                               @endif
                           </div>

                           <div class="form-group col-md-12" id="bill_amount_info" style="display: none;">
                               <div class="card card-info">
                                   <div class="card-header">
                                       <h3 class="card-title"><i class="fas fa-info-circle"></i> House Rent Payment Details</h3>
                                   </div>
                                   <div class="card-body">
                                       <div class="row">
                                           <div class="col-md-6">
                                               <p><strong>Monthly Rent:</strong> <span id="monthly_rent_amount">Tk. 0.00</span></p>
                                           </div>
                                           <div class="col-md-6" id="extra_reduction_info" style="display: none;">
                                               <p><strong>Available Extra Payment Reduction:</strong> <span id="extra_reduction_amount" class="text-success">Tk. 0.00</span></p>
                                           </div>
                                           <div class="col-md-6">
                                               <p><strong>Already Paid:</strong> <span id="already_paid_amount">Tk. 0.00</span></p>
                                           </div>
                                           <div class="col-md-6">
                                               <p><strong class="text-danger">Due Amount (Full Rent):</strong> <span id="full_rent_due" class="text-danger font-weight-bold">Tk. 0.00</span></p>
                                           </div>
                                           <div class="col-md-6" id="reduction_applied_info" style="display: none;">
                                               <p><strong class="text-success">Reduction Applied:</strong> <span id="reduction_applied_amount" class="text-success font-weight-bold">Tk. 0.00</span></p>
                                           </div>
                                           <div class="col-md-6" id="final_amount_info" style="display: none;">
                                               <p><strong class="text-primary">Final Payment Amount:</strong> <span id="final_payment_amount" class="text-primary font-weight-bold">Tk. 0.00</span></p>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           </div>

                           <div class="form-group col-md-12" id="use_extra_reduction_field" style="display: none;">
                               <div class="card card-warning">
                                   <div class="card-body">
                                       <div class="form-check">
                                           <input class="form-check-input" type="checkbox" id="use_extra_reduction" name="use_extra_reduction" value="1">
                                           <label class="form-check-label" for="use_extra_reduction">
                                               <strong>Apply Extra Payment Reduction</strong>
                                           </label>
                                           <small class="form-text text-muted d-block">If checked, extra payment reduction amount will be deducted from house rent</small>
                                       </div>
                                   </div>
                               </div>
                           </div>

                           <div class="form-group col-md-6" id="bill_amount_info_simple" style="display: none;">
                               <div class="alert alert-info">
                                   <strong>Bill Amount Due:</strong> <span id="bill_amount_due_simple">Tk. 0.00</span>
                               </div>
                           </div>

                           <div class="form-group col-md-6">
                               <label for="amount">Payment Amount</label>
                               <input class="form-control" type="number" id="amount" name="amount" placeholder="Enter amount" step="0.01" min="0" autocomplete="off">
   
                               @if ($errors->has('amount'))
                                   <p class="text-danger">{{ $errors->first('amount') }}</p>
                               @endif
                           </div>     
                           
                           <div class="form-group col-md-6">
                            <label for="date">Payment Date</label>
                            <input class="form-control" type="date" id="date" name="date" value="{{ date('Y-m-d') }}" placeholder="Enter date" autocomplete="off">

                            @if ($errors->has('date'))
                                <p class="text-danger">{{ $errors->first('date') }}</p>
                            @endif
                        </div>

                        <div class="form-group col-md-6">
                            <label for="month">Month</label>
                            <select class="form-control" id="month" name="month" required autocomplete="off">
                                <option value="">--Select Month--</option>
                                @php
                                    $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                                    $currentMonth = \Carbon\Carbon::now()->format('F');
                                @endphp
                                @foreach($months as $m)
                                    <option value="{{ $m }}" {{ $currentMonth == $m ? 'selected' : '' }}>{{ $m }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('month'))
                                <p class="text-danger">{{ $errors->first('month') }}</p>
                            @endif
                        </div>

                        <div class="form-group col-md-12">
                            <label for="notes">Notes (Optional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Add any notes about this payment" autocomplete="off"></textarea>
                            @if ($errors->has('notes'))
                                <p class="text-danger">{{ $errors->first('notes') }}</p>
                            @endif
                        </div>
                       </div>
                       <input class="btn btn-success" type="submit" id="submit" name="submit" value="Submit">
                   </form>
            </div>
        </div>

        <!-- /.row -->

    </div><!-- /.container-fluid -->
</section>
@endsection

{{-- data store with ajax --}}
@section('custom_js')

<script>
  //Initialize Select2 Elements
  $('.select2').select2()

//Initialize Select2 Elements
$('.select2bs4').select2({
    theme: 'bootstrap4'
})
    //  SweetAlert2 
    const Toast = Swal.mixin({
                        toast:true,
                        position:'top-end',
                        icon:'success',
                        showConfirmbutton: false,
                        timer:3000
                    });


    // pass the csrf token for post method.
       $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            }
        });

$(document).ready(function(){
    
    // Select myself (admin) for payment entry
    $('#selectMyselfPayment').on('click', function(){
        var myMemberId = '{{ session("member_id") }}';
        $('#member_id').val(myMemberId).trigger('change');
    });

    // Function to get payment amount for selected member and payment type
    function getBillAmount() {
        var memberId = $('#member_id').val();
        var paymentType = $('#payment_type').val();
        var month = $('#month').val();

        // If month is not selected, use current month
        if (!month && paymentType && paymentType !== 'food_advance' && memberId) {
            var currentMonth = new Date().toLocaleString('default', { month: 'long' });
            $('#month').val(currentMonth);
            month = currentMonth;
        }

        // Show amount info for bill payments, room rent, and room advance
        if (paymentType && paymentType !== 'food_advance' && memberId && month) {
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
                        // Handle room rent payment with extra reduction details
                        if (paymentType === 'room_rent' && response.extra_reduction !== undefined) {
                            var monthlyRent = parseFloat(response.total_bill || 0);
                            var extraReduction = parseFloat(response.extra_reduction || 0);
                            var alreadyPaid = parseFloat(response.paid || 0);
                            var finalRent = parseFloat(response.final_rent || 0);
                            
                            // Store values in data attributes for checkbox handler
                            $('#use_extra_reduction').data('monthly-rent', monthlyRent);
                            $('#use_extra_reduction').data('extra-reduction', extraReduction);
                            $('#use_extra_reduction').data('already-paid', alreadyPaid);
                            $('#use_extra_reduction').data('final-rent', finalRent);
                            
                            // Show monthly rent
                            $('#monthly_rent_amount').text('Tk. ' + monthlyRent.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                            
                            // Show extra reduction if available
                            if (extraReduction > 0) {
                                $('#extra_reduction_amount').text('Tk. ' + extraReduction.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                                $('#extra_reduction_info').show();
                                $('#use_extra_reduction_field').show();
                            } else {
                                $('#extra_reduction_info').hide();
                                $('#use_extra_reduction_field').hide();
                            }
                            
                            // Show already paid
                            $('#already_paid_amount').text('Tk. ' + alreadyPaid.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                            
                            // Calculate and show full rent due (without reduction)
                            var fullRentDue = Math.max(0, monthlyRent - alreadyPaid);
                            $('#full_rent_due').text('Tk. ' + fullRentDue.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                            
                            // Check if checkbox is checked
                            var useReduction = $('#use_extra_reduction').is(':checked');
                            calculateRoomRentAmount(useReduction, monthlyRent, extraReduction, alreadyPaid);
                            
                            $('#bill_amount_info').show();
                            $('#bill_amount_info_simple').hide();
                        } else {
                            // Handle other bill payments
                            $('#bill_amount_due_simple').text('Tk. ' + parseFloat(response.amount || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                            $('#bill_amount_info_simple').show();
                            $('#bill_amount_info').hide();
                            $('#amount').val(response.amount || 0);
                        }
                    } else {
                        $('#bill_amount_info').hide();
                        $('#bill_amount_info_simple').hide();
                        $('#bill_amount_due').text('Tk. 0.00');
                        $('#bill_amount_due_simple').text('Tk. 0.00');
                        $('#amount').val('');
                    }
                },
                error: function() {
                    $('#bill_amount_info').hide();
                    $('#bill_amount_info_simple').hide();
                    $('#bill_amount_due').text('Tk. 0.00');
                    $('#bill_amount_due_simple').text('Tk. 0.00');
                    $('#amount').val('');
                }
            });
        } else {
            // For meal payment or no payment type selected
            $('#bill_amount_info').hide();
            $('#bill_amount_info_simple').hide();
            $('#bill_amount_due').text('Tk. 0.00');
            $('#bill_amount_due_simple').text('Tk. 0.00');
            // Clear amount if payment type changed to meal payment or no type selected
            if (paymentType === 'food_advance' || !paymentType) {
                $('#amount').val('');
            }
        }
    }

    // Function to calculate room rent payment amount based on checkbox
    function calculateRoomRentAmount(useReduction, monthlyRent, extraReduction, alreadyPaid) {
        var fullRentDue = Math.max(0, monthlyRent - alreadyPaid);
        
        if (useReduction && extraReduction > 0) {
            // Apply reduction: final rent - already paid
            var finalRent = Math.max(0, monthlyRent - extraReduction);
            var paymentAmount = Math.max(0, finalRent - alreadyPaid);
            
            // Show reduction applied info
            $('#reduction_applied_amount').text('Tk. ' + extraReduction.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            $('#reduction_applied_info').show();
            $('#final_amount_info').show();
            $('#final_payment_amount').text('Tk. ' + paymentAmount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            
            // Update payment amount field
            $('#amount').val(paymentAmount.toFixed(2));
        } else {
            // No reduction: full rent - already paid
            $('#reduction_applied_info').hide();
            $('#final_amount_info').hide();
            
            // Update payment amount field with full rent due
            $('#amount').val(fullRentDue.toFixed(2));
        }
    }

    // Handle checkbox change for extra payment reduction
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

    // Trigger bill amount lookup when member or payment type changes
    $('#member_id, #payment_type').on('change', function() {
        // Hide checkbox field when payment type changes to non-room-rent
        if ($('#payment_type').val() !== 'room_rent') {
            $('#use_extra_reduction_field').hide();
            $('#use_extra_reduction').prop('checked', false);
        }
        // Auto-load amount when member and payment type are selected
        getBillAmount();
    });

    // Trigger bill amount lookup when month changes (to update amount for different month)
    $('#month').on('change', function() {
        // Hide checkbox field when payment type changes to non-room-rent
        if ($('#payment_type').val() !== 'room_rent') {
            $('#use_extra_reduction_field').hide();
            $('#use_extra_reduction').prop('checked', false);
        }
        getBillAmount();
    });

    $('#form').on("submit",function(event){
        event.preventDefault();
        var form = new FormData(this);
    
        $.ajax({
            url:"{{route('admin.store.payment')}}",
            data:form,
            contentType:false,
            cache:false,
            processData:false,
            method:"POST",
            success:function(response){
            // alert(response);
                // alert('successfully stored');
               
                              
              // console.lo()
               if(response == "success"){
                Toast.fire({
                            type:'success',
                            title:'Payment successfully saved.',
                        });
               }
               $("#form")[0].reset();
               $('#bill_amount_info').hide();

            //   msg ="<div class='alert alert-dark'>"+response+"</div>";
			// 	      $("#msg").html(msg);
            },
            error:function(error){
                Toast.fire({
                            type:'error',
                            title:'Something Error Found, Please try again.',
                        });
            }
        });
      
    
    });

});


</script>
@endsection
