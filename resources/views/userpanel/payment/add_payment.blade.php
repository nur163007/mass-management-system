@extends('admin.master')

@section('heading', 'User add-payment')
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
                    <a href="{{route('user.viewPayment')}}" class="viewall bg-cyan"><i class="far fa-money-bill-alt"></i> All Payment</a>
                      <a href="{{route('user.pendingPayment')}}" class="viewall bg-olive"><i class="fas fa-parking"></i> Pending Payment</a>
                </div>
            </div>
            @include('admin.includes.message')
            <div class="card-body">
                <form method="POST"  enctype="multipart/form-data" id="form">
                    @csrf
                       <div class="row">               

                           <div class="form-group col-md-6">
                               <label for="payment_type">Payment Type <span class="text-danger">*</span></label>
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
                                       <h3 class="card-title"><i class="fas fa-info-circle"></i> Payment Details</h3>
                                   </div>
                                   <div class="card-body">
                                       <div class="row">
                                           <div class="col-md-6">
                                               <p><strong>Total Bill Amount:</strong> <span id="total_bill_amount">Tk. 0.00</span></p>
                                           </div>
                                           <div class="col-md-6">
                                               <p><strong>Already Paid:</strong> <span id="already_paid_amount">Tk. 0.00</span></p>
                                           </div>
                                           <div class="col-md-6">
                                               <p><strong class="text-danger">Due Amount:</strong> <span id="due_amount" class="text-danger font-weight-bold">Tk. 0.00</span></p>
                                           </div>
                                           <div class="col-md-12" id="payment_exists_warning" style="display: none;">
                                               <div class="alert alert-warning">
                                                   <strong><i class="fas fa-exclamation-triangle"></i> Warning:</strong> 
                                                   <span id="warning_message"></span>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           </div>

                           <div class="form-group col-md-6">
                               <label for="amount">Payment Amount <span class="text-danger">*</span></label>
                               <input class="form-control" type="number" id="amount" name="amount" placeholder="Enter amount" step="0.01" min="0.01" required>
   
                               @if ($errors->has('amount'))
                                   <p class="text-danger">{{ $errors->first('amount') }}</p>
                               @endif
                           </div>     
                           
                           <div class="form-group col-md-6">
                            <label for="date">Payment Date <span class="text-danger">*</span></label>
                            <input class="form-control" type="date" id="date" name="date" placeholder="Enter date" required>

                            @if ($errors->has('date'))
                                <p class="text-danger">{{ $errors->first('date') }}</p>
                            @endif
                        </div>   
                           
                       </div>
                       <input class="btn btn-success" type="submit" id="submit" name="submit" value="Submit" disabled>
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
    var paymentExists = false;

    // Set default date to today
    var today = new Date().toISOString().split('T')[0];
    $('#date').val(today);

    // Function to get bill amount when payment type is selected
    function getBillAmount() {
        var paymentType = $('#payment_type').val();
        
        if (!paymentType) {
            $('#bill_amount_info').hide();
            $('#submit').prop('disabled', true);
            paymentExists = false;
            return;
        }

        // Show loading state
        $('#bill_amount_info').show();
        $('#total_bill_amount').text('Loading...');
        $('#already_paid_amount').text('Loading...');
        $('#due_amount').text('Loading...');

        $.ajax({
            url: "{{ route('user.payment.getBillAmount') }}",
            method: 'POST',
            data: {
                payment_type: paymentType
            },
            success: function(response) {
                if (response.success) {
                    var paymentType = $('#payment_type').val();
                    
                    // For food_advance, don't show bill amount info, just check for duplicate
                    if (paymentType === 'food_advance') {
                        $('#bill_amount_info').hide();
                        
                        // Check if payment already exists
                        if (response.payment_exists) {
                            paymentExists = true;
                            $('#submit').prop('disabled', true);
                            Toast.fire({
                                type: 'warning',
                                title: 'Payment already exists for current month!'
                            });
                        } else {
                            paymentExists = false;
                            $('#submit').prop('disabled', false);
                        }
                        return;
                    }

                    // For other payment types, show bill amount info
                    $('#total_bill_amount').text('Tk. ' + parseFloat(response.total_bill || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                    $('#already_paid_amount').text('Tk. ' + parseFloat(response.paid || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                    $('#due_amount').text('Tk. ' + parseFloat(response.due || response.amount || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                    
                    // Auto-fill amount field with due amount
                    var dueAmount = parseFloat(response.due || response.amount || 0);
                    if (dueAmount > 0) {
                        $('#amount').val(dueAmount.toFixed(2));
                    } else {
                        $('#amount').val('');
                    }

                    // Check if payment already exists
                    if (response.payment_exists) {
                        paymentExists = true;
                        $('#payment_exists_warning').show();
                        var warningMsg = 'Payment already exists for current month. ';
                        if (response.existing_payment_status == 1) {
                            warningMsg += 'Status: Approved (Amount: Tk. ' + parseFloat(response.existing_payment_amount || 0).toFixed(2) + ').';
                        } else {
                            warningMsg += 'Status: Pending (Amount: Tk. ' + parseFloat(response.existing_payment_amount || 0).toFixed(2) + ').';
                        }
                        warningMsg += ' You cannot add duplicate payment for the same month.';
                        $('#warning_message').text(warningMsg);
                        $('#submit').prop('disabled', true);
                        Toast.fire({
                            type: 'warning',
                            title: 'Payment already exists for this month!'
                        });
                    } else {
                        paymentExists = false;
                        $('#payment_exists_warning').hide();
                        $('#submit').prop('disabled', false);
                    }

                    // Show info card for bill payments
                    $('#bill_amount_info').show();
                } else {
                    $('#bill_amount_info').hide();
                    $('#submit').prop('disabled', false);
                    paymentExists = false;
                    
                    if (response.message) {
                        Toast.fire({
                            type: 'info',
                            title: response.message
                        });
                    }
                }
            },
            error: function(xhr) {
                $('#bill_amount_info').hide();
                $('#submit').prop('disabled', false);
                paymentExists = false;
                
                var errorMsg = 'Failed to load bill information.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                
                Toast.fire({
                    type: 'error',
                    title: errorMsg
                });
            }
        });
    }

    // Trigger bill amount lookup when payment type changes
    $('#payment_type').on('change', function() {
        $('#amount').val('');
        getBillAmount();
    });

    // Check for duplicate before form submission
    $('#form').on("submit",function(event){
        if (paymentExists) {
            event.preventDefault();
            Toast.fire({
                type: 'error',
                title: 'Duplicate payment not allowed for current month!'
            });
            return false;
        }

        // Validate amount
        var amount = parseFloat($('#amount').val()) || 0;
        if (amount <= 0 || isNaN(amount) || $('#amount').val() === '') {
            event.preventDefault();
            Toast.fire({
                type: 'error',
                title: 'Payment amount must be greater than 0.'
            });
            $('#amount').focus();
            return false;
        }

        event.preventDefault();
        var form = new FormData(this);
    
        $.ajax({
            url:"{{route('user.store.payment')}}",
            data:form,
            contentType:false,
            cache:false,
            processData:false,
            method:"POST",
            success:function(response){
               if(response.success || response == "success"){
                var message = 'Payment successfully saved.';
                if (response.status == "1") {
                    message = 'Payment automatically approved (you are responsible for this bill type).';
                } else if (response.message) {
                    message = response.message;
                }
                Toast.fire({
                            type:'success',
                            title: message,
                        });
               }
               $("#form")[0].reset();
               $('#bill_amount_info').hide();
               $('#submit').prop('disabled', true);
               paymentExists = false;
               $('#date').val(today); // Reset date to today
               $('#payment_type').val(''); // Reset payment type
            },
            error:function(xhr){
                var errorMsg = 'Something Error Found, Please try again.';
                
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMsg = xhr.responseJSON.error;
                }
                
                Toast.fire({
                    type:'error',
                    title: errorMsg,
                });
            }
        });
    });

});



</script>
@endsection

