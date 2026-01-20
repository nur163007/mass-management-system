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
                                        <option value="{{ $member->id }}" data-member-id="{{ $member->id }}">
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
                                <small class="text-muted">Select members from applicable members who use extra gas and will pay 100tk extra. Only selected applicable members will be shown here.</small>
                            </div>

                            <div class="form-group col-md-12">
                                <label>Applicable Members <span class="text-danger">*</span></label>
                                <div class="card card-info">
                                    <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="select_all_members">
                                            <label class="form-check-label font-weight-bold" for="select_all_members">
                                                Select All
                                            </label>
                                        </div>
                                        <hr>
                                        @foreach($members as $member)
                                            <div class="form-check">
                                                <input class="form-check-input applicable_member_checkbox" type="checkbox" name="applicable_member_ids[]" value="{{ $member->id }}" id="member_{{ $member->id }}">
                                                <label class="form-check-label" for="member_{{ $member->id }}">
                                                    {{ $member->full_name }}
                                                    @if($member->role_id == 2)
                                                        <span class="badge badge-info">(Manager)</span>
                                                    @endif
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @if ($errors->has('applicable_member_ids'))
                                    <p class="text-danger">{{ $errors->first('applicable_member_ids') }}</p>
                                @endif
                                <small class="text-muted">Select members who will be responsible for this bill. Bill amount will be divided by the number of selected members.</small>
                                <div id="per_person_calculation" class="mt-2" style="display: none;">
                                    <div class="alert alert-success">
                                        <strong>Per Person Amount: <span id="calculated_per_person">Tk. 0.00</span></strong>
                                        <br><small>Total Amount: <span id="display_total_amount">Tk. 0.00</span> / <span id="selected_member_count">0</span> members</small>
                                    </div>
                                </div>
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
                
                // Calculate and update total (extra gas users will be validated)
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
            
            // Get selected applicable members count
            var selectedMembers = $('.applicable_member_checkbox:checked').length;
            
            // Get selected extra gas users
            var extraUsersSelected = $('#extra_gas_users').val() || [];
            var extraUsersCount = extraUsersSelected.length;
            
            // Calculation must be based on applicable members count (not extra users count)
            // If no applicable members selected yet, show warning but use default for calculation
            var memberCount = selectedMembers > 0 ? selectedMembers : 0;
            
            // Calculate extra amount: extra users count × 100tk
            var extraAmount = extraUsersCount * 100;
            
            // Calculate remaining amount: total - extra amount (only if extra users exist)
            var remainingAmount = extraUsersCount > 0 ? (total - extraAmount) : total;
            
            // Prevent division by zero
            var perPersonBase = 0;
            if (memberCount > 0) {
                perPersonBase = remainingAmount / memberCount;
            } else if (extraUsersCount > 0) {
                // If only extra users selected but no applicable members, show temporary calculation
                perPersonBase = remainingAmount / extraUsersCount;
            }
            
            var perPersonWithExtra = perPersonBase + 100;
            
            var normalUsersCount = Math.max(0, memberCount - extraUsersCount);
            var normalUsersTotal = normalUsersCount * perPersonBase;
            var extraUsersTotal = extraUsersCount * perPersonWithExtra;
            
            var warningMsg = '';
            if (memberCount === 0) {
                if (extraUsersCount > 0) {
                    warningMsg = '<div class="alert alert-warning p-2 mb-2"><i class="fas fa-exclamation-triangle"></i> <strong>Please select applicable members!</strong> Calculation shown below is temporary and will update when you select applicable members.</div>';
                } else {
                    warningMsg = '<div class="alert alert-warning p-2 mb-2"><i class="fas fa-exclamation-triangle"></i> <strong>Please select applicable members!</strong> Calculation will appear once you select members.</div>';
                }
            }
            
            var calculationHtml = '<div class="alert alert-info p-2">' +
                '<strong>Total Bill Amount: Tk. ' + total.toFixed(2) + '</strong><br><br>' +
                '<strong>Applicable Members Selected:</strong> ' + (memberCount > 0 ? memberCount : '0 (Please select)') + ' person(s)<br>';
            
            if (extraUsersCount > 0) {
                // If extra users exist, show deduction calculation
                calculationHtml += '<strong>Extra Gas Users Selected:</strong> ' + extraUsersCount + ' person(s) × Tk. 100 = <span class="text-danger">Tk. ' + extraAmount.toFixed(2) + '</span><br>' +
                    '<strong>Remaining Amount:</strong> Tk. ' + total.toFixed(2) + ' - Tk. ' + extraAmount.toFixed(2) + ' = <span class="text-primary">Tk. ' + remainingAmount.toFixed(2) + '</span><br><br>';
                
                if (memberCount > 0) {
                    calculationHtml += '<strong>Division:</strong> Tk. ' + remainingAmount.toFixed(2) + ' ÷ ' + memberCount + ' applicable members = <span class="text-success">Tk. ' + perPersonBase.toFixed(2) + '</span> per person (base)<br><br>' +
                        '<strong>Normal Users (' + normalUsersCount + '):</strong> Tk. ' + perPersonBase.toFixed(2) + ' each (Total: Tk. ' + normalUsersTotal.toFixed(2) + ')<br>' +
                        '<strong>Extra Users (' + extraUsersCount + '):</strong> Tk. ' + perPersonBase.toFixed(2) + ' + Tk. 100 = <span class="text-warning">Tk. ' + perPersonWithExtra.toFixed(2) + '</span> each (Total: Tk. ' + extraUsersTotal.toFixed(2) + ')<br><br>' +
                        '<strong>Verification:</strong> Tk. ' + normalUsersTotal.toFixed(2) + ' + Tk. ' + extraUsersTotal.toFixed(2) + ' = Tk. ' + (normalUsersTotal + extraUsersTotal).toFixed(2);
                } else {
                    calculationHtml += '<strong class="text-warning">Waiting for applicable members selection...</strong>';
                }
            } else {
                // If no extra users, no deduction, divide total by applicable members
                if (memberCount > 0) {
                    calculationHtml += '<strong>Extra Gas Users:</strong> <span class="text-success">None selected</span> (No amount deduction)<br><br>' +
                        '<strong>Division:</strong> Tk. ' + total.toFixed(2) + ' ÷ ' + memberCount + ' applicable members = <span class="text-success">Tk. ' + perPersonBase.toFixed(2) + '</span> per person<br><br>' +
                        '<strong>All Members (' + memberCount + '):</strong> Tk. ' + perPersonBase.toFixed(2) + ' each (Total: Tk. ' + (perPersonBase * memberCount).toFixed(2) + ')';
                } else {
                    calculationHtml += '<strong>Extra Gas Users:</strong> <span class="text-success">None selected</span> (No amount deduction)<br><br>' +
                        '<strong class="text-warning">Waiting for applicable members selection...</strong>';
                }
            }
            
            calculationHtml += '</div>';
            
            $('#total_amount_hint').html(warningMsg + calculationHtml);
            
            // Also update per person calculation
            updatePerPersonCalculation();
        }

        // Update when extra gas users change
        $('#extra_gas_users').on('change', function() {
            // Validate extra gas users are in applicable members
            updateExtraGasUsersDropdown();
            
            if ($('#bill_type').val() === 'gas') {
                updateGasTotal();
            }
            updatePerPersonCalculation();
        });

        // Select all members checkbox
        $('#select_all_members').on('change', function() {
            $('.applicable_member_checkbox').prop('checked', $(this).is(':checked'));
            updatePerPersonCalculation();
            updateExtraGasUsersDropdown();
        });

        // Update per person calculation when members are selected/deselected
        $('.applicable_member_checkbox').on('change', function() {
            // Update extra gas users validation first
            updateExtraGasUsersDropdown();
            
            // Update calculations
            if ($('#bill_type').val() === 'gas') {
                updateGasTotal();
            }
            updatePerPersonCalculation();
            
            // Uncheck select all if any checkbox is unchecked
            if (!$(this).is(':checked')) {
                $('#select_all_members').prop('checked', false);
            } else {
                // Check select all if all checkboxes are checked
                var totalCheckboxes = $('.applicable_member_checkbox').length;
                var checkedCheckboxes = $('.applicable_member_checkbox:checked').length;
                if (totalCheckboxes === checkedCheckboxes) {
                    $('#select_all_members').prop('checked', true);
                }
            }
        });

        // Function to validate and update extra gas users based on applicable members
        function updateExtraGasUsersDropdown() {
            if ($('#bill_type').val() !== 'gas') {
                return;
            }
            
            var selectedApplicableMemberIds = [];
            $('.applicable_member_checkbox:checked').each(function() {
                selectedApplicableMemberIds.push($(this).val());
            });
            
            // Get currently selected extra gas users
            var currentSelected = $('#extra_gas_users').val() || [];
            var validSelected = [];
            
            // Validate: extra gas users must be in applicable members
            // If applicable members are selected, filter extra gas users
            if (selectedApplicableMemberIds.length > 0) {
                currentSelected.forEach(function(memberId) {
                    if (selectedApplicableMemberIds.includes(memberId)) {
                        validSelected.push(memberId);
                    }
                });
            } else {
                // If no applicable members selected, keep all extra gas users (will be validated on submit)
                validSelected = currentSelected;
            }
            
            // Update selected values (remove invalid selections)
            if (validSelected.length !== currentSelected.length) {
                $('#extra_gas_users').val(validSelected).trigger('change');
            }
            
            // Update calculation
            if ($('#bill_type').val() === 'gas') {
                updateGasTotal();
            }
            updatePerPersonCalculation();
        }

        // Update per person calculation when total amount changes
        $('#total_amount, #cylinder_count, #cylinder_cost').on('input', function() {
            updatePerPersonCalculation();
        });

        function updatePerPersonCalculation() {
            var selectedMembers = $('.applicable_member_checkbox:checked').length;
            var totalAmount = 0;
            var perPerson = 0;
            
            if ($('#bill_type').val() === 'gas') {
                var cylinderCount = parseFloat($('#cylinder_count').val()) || 1;
                var cylinderCost = parseFloat($('#cylinder_cost').val()) || 1500;
                totalAmount = cylinderCount * cylinderCost;
                
                // Get extra gas users count
                var extraUsersSelected = $('#extra_gas_users').val() || [];
                var extraUsersCount = extraUsersSelected.length;
                
                // Calculate extra amount deduction
                var extraAmount = extraUsersCount * 100;
                var remainingAmount = extraUsersCount > 0 ? (totalAmount - extraAmount) : totalAmount;
                
                // Calculate per person (base amount)
                if (selectedMembers > 0) {
                    perPerson = remainingAmount / selectedMembers;
                }
                
                // Update display with proper calculation
                if (selectedMembers > 0 && totalAmount > 0) {
                    if (extraUsersCount > 0) {
                        $('#calculated_per_person').text('Tk. ' + perPerson.toFixed(2) + ' (base)');
                        $('#display_total_amount').html('Tk. ' + totalAmount.toFixed(2) + ' - Tk. ' + extraAmount.toFixed(2) + ' (extra) = Tk. ' + remainingAmount.toFixed(2) + ' / ' + selectedMembers + ' members');
                    } else {
                        $('#calculated_per_person').text('Tk. ' + perPerson.toFixed(2));
                        $('#display_total_amount').text('Tk. ' + totalAmount.toFixed(2) + ' / ' + selectedMembers + ' members');
                    }
                    $('#selected_member_count').text(selectedMembers);
                    $('#per_person_calculation').show();
                } else {
                    $('#per_person_calculation').hide();
                }
            } else {
                totalAmount = parseFloat($('#total_amount').val()) || 0;
                
                if (selectedMembers > 0 && totalAmount > 0) {
                    perPerson = totalAmount / selectedMembers;
                    $('#calculated_per_person').text('Tk. ' + perPerson.toFixed(2));
                    $('#display_total_amount').text('Tk. ' + totalAmount.toFixed(2));
                    $('#selected_member_count').text(selectedMembers);
                    $('#per_person_calculation').show();
                } else {
                    $('#per_person_calculation').hide();
                }
            }
        }

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

