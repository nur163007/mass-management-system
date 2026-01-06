@extends('admin.master')

@section('heading', 'Add Bill')
@section('title', 'add bill')

@section('main-content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3 class="font-weight-bolder">Add Bill</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="{{route('admin.bill.index')}}" class="viewall"><i class="fas fa-list"></i> All Bills</a>
                    </div>
                </div>

                @include('admin.includes.message')

                <div class="card-body">
                    <form action="{{ route('admin.bill.store') }}" method="POST" id="form">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="bill_type">Bill Type</label>
                                <select class="form-control" id="bill_type" name="bill_type" required autocomplete="off">
                                    <option value="">--Select Bill Type--</option>
                                    <option value="water">Water Bill</option>
                                    <option value="internet">Internet Bill</option>
                                    <option value="electricity">Electricity Bill</option>
                                    <option value="gas">Gas Bill</option>
                                    <option value="bua_moyla">Bua & Moyla Bill</option>
                                </select>
                                @if ($errors->has('bill_type'))
                                    <p class="text-danger">{{ $errors->first('bill_type') }}</p>
                                @endif
                            </div>

                            <!-- Gas Bill Fields (shown only when gas is selected) -->
                            <div class="form-group col-md-6" id="gas_cylinder_count_field" style="display: none;">
                                <label for="cylinder_count">Number of Cylinders</label>
                                <input type="number" class="form-control" id="cylinder_count" name="cylinder_count" placeholder="Enter number of cylinders" value="1" step="1" min="1" autocomplete="off">
                                @if ($errors->has('cylinder_count'))
                                    <p class="text-danger">{{ $errors->first('cylinder_count') }}</p>
                                @endif
                            </div>

                            <div class="form-group col-md-6" id="gas_cylinder_cost_field" style="display: none;">
                                <label for="cylinder_cost">Cylinder Cost (Tk.)</label>
                                <input type="number" class="form-control" id="cylinder_cost" name="cylinder_cost" placeholder="Enter cost per cylinder" value="1500" step="0.01" min="0" autocomplete="off">
                                @if ($errors->has('cylinder_cost'))
                                    <p class="text-danger">{{ $errors->first('cylinder_cost') }}</p>
                                @endif
                            </div>

                            <div class="form-group col-md-6" id="total_amount_field">
                                <label for="total_amount">Total Amount (Tk.)</label>
                                <input type="number" class="form-control" id="total_amount" name="total_amount" placeholder="Enter total bill amount" step="0.01" min="0" required autocomplete="off">
                                @if ($errors->has('total_amount'))
                                    <p class="text-danger">{{ $errors->first('total_amount') }}</p>
                                @endif
                                <small class="text-muted" id="total_amount_hint"></small>
                            </div>

                            <div class="form-group col-md-12" id="extra_gas_users_field" style="display: none;">
                                <label>Members Using Extra Gas (Pay 100tk extra)</label>
                                <select class="form-control select2bs4" id="extra_gas_users" name="extra_gas_users[]" multiple autocomplete="off">
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}">
                                            {{ $member->full_name }} 
                                            @if($member->role_id == 2)
                                                (Manager)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('extra_gas_users'))
                                    <p class="text-danger">{{ $errors->first('extra_gas_users') }}</p>
                                @endif
                                <small class="text-muted">Select members who use extra gas and will pay 100tk extra</small>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="bill_date">Bill Date</label>
                                <input type="date" class="form-control" id="bill_date" name="bill_date" value="{{ date('Y-m-d') }}" required autocomplete="off">
                                @if ($errors->has('bill_date'))
                                    <p class="text-danger">{{ $errors->first('bill_date') }}</p>
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
                                        <option value="{{ $m }}" {{ $currentMonth == $m ? 'selected' : '' }}>
                                            {{ $m }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('month'))
                                    <p class="text-danger">{{ $errors->first('month') }}</p>
                                @endif
                            </div>

                            <div class="form-group col-md-12">
                                <label for="notes">Notes (Optional)</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Add any notes about this bill" autocomplete="off"></textarea>
                                @if ($errors->has('notes'))
                                    <p class="text-danger">{{ $errors->first('notes') }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Add Bill</button>
                            <a href="{{ route('admin.bill.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom_js')
<script>
    //  SweetAlert2 
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        icon: 'success',
        showConfirmButton: false,
        timer: 3000
    });

    // pass the csrf token for post method.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        // Initialize Select2
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });

        // Handle bill type change
        $('#bill_type').on('change', function() {
            var billType = $(this).val();
            
            if (billType === 'gas') {
                // Show gas-specific fields
                $('#gas_cylinder_count_field').show();
                $('#gas_cylinder_cost_field').show();
                $('#extra_gas_users_field').show();
                
                // Hide regular total amount field
                $('#total_amount_field').hide();
                $('#total_amount').removeAttr('required');
                
                // Make gas fields required
                $('#cylinder_count').attr('required', 'required');
                $('#cylinder_cost').attr('required', 'required');
                
                // Calculate and update total
                updateGasTotal();
            } else {
                // Hide gas-specific fields
                $('#gas_cylinder_count_field').hide();
                $('#gas_cylinder_cost_field').hide();
                $('#extra_gas_users_field').hide();
                
                // Show regular total amount field
                $('#total_amount_field').show();
                $('#total_amount').attr('required', 'required');
                
                // Remove required from gas fields
                $('#cylinder_count').removeAttr('required');
                $('#cylinder_cost').removeAttr('required');
                
                $('#total_amount_hint').text('');
            }
        });

        // Update gas total when cylinder count or cost changes
        $('#cylinder_count, #cylinder_cost').on('input', function() {
            if ($('#bill_type').val() === 'gas') {
                updateGasTotal();
            }
        });

        function updateGasTotal() {
            var cylinderCount = parseFloat($('#cylinder_count').val()) || 1;
            var cylinderCost = parseFloat($('#cylinder_cost').val()) || 1500;
            var total = cylinderCount * cylinderCost;
            
            // Calculate per person amount
            var extraUsersCount = $('#extra_gas_users').val() ? $('#extra_gas_users').val().length : 0;
            var extraAmount = extraUsersCount * 100;
            var remainingAmount = total - extraAmount;
            var perPersonBase = remainingAmount / 7;
            var perPersonWithExtra = perPersonBase + 100;
            
            $('#total_amount_hint').html(
                '<strong>Total: Tk. ' + total.toFixed(2) + '</strong><br>' +
                'Extra users: ' + extraUsersCount + ' (Tk. ' + extraAmount.toFixed(2) + ')<br>' +
                'Remaining: Tk. ' + remainingAmount.toFixed(2) + ' / 7 = Tk. ' + perPersonBase.toFixed(2) + ' per person<br>' +
                'Extra users pay: Tk. ' + perPersonWithExtra.toFixed(2) + ' each'
            );
        }

        // Update when extra gas users change
        $('#extra_gas_users').on('change', function() {
            if ($('#bill_type').val() === 'gas') {
                updateGasTotal();
            }
        });

        // Calculate total for gas bill before submit
        $('#form').on("submit", function(event) {
            var billType = $('#bill_type').val();
            if (billType === 'gas') {
                var cylinderCount = parseFloat($('#cylinder_count').val()) || 1;
                var cylinderCost = parseFloat($('#cylinder_cost').val()) || 1500;
                var total = cylinderCount * cylinderCost;
                $('#total_amount').val(total);
            }
            
            event.preventDefault();
            var form = $(this);
            var formData = new FormData(this);

            $.ajax({
                url: form.attr('action'),
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                method: "POST",
                success: function(response) {
                    Toast.fire({
                        type: 'success',
                        title: 'Bill added successfully.'
                    });
                    setTimeout(function() {
                        window.location.href = '{{ route("admin.bill.index") }}';
                    }, 1000);
                },
                error: function(xhr) {
                    var errorMsg = 'Something Error Found, Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        errorMsg = Object.values(errors).flat().join('<br>');
                    } else if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMsg = xhr.responseJSON.error;
                    }
                    Toast.fire({
                        type: 'error',
                        title: errorMsg
                    });
                }
            });
        });
    });
</script>
@endsection

