@extends('admin.master')

@section('heading', 'Add-expanse')
@section('title', 'Expanse')
@section('main-content')
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3 class="font-weight-bolder">Add Expanse</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="{{ route('admin.view.expanse') }}" class="viewall"><i class="fas fa-money-bill-alt"></i> All
                            Expanses</a>
                    </div>
                </div>
                @include('admin.includes.message')
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" id="form" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="member_id">Member's Name</label>
                                <div class="input-group">
                                    <select id="member_id" class="custom-select" name="member_id" autocomplete="off">
                                        <option value="">--select member name--</option>
                                        @foreach ($members as $member)
                                            <option value="{{ $member->id }}"
                                                @if (session('member_id') == $member->id) selected @endif>{{ $member->full_name }}
                                                @if ($member->role_id == 2)
                                                    (Manager)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-info" id="selectMyselfExpanse"
                                            title="Select Myself">
                                            <i class="fas fa-user"></i> Me
                                        </button>
                                    </div>
                                </div>
                                @if ($errors->has('member_id'))
                                    <p class="text-danger">{{ $errors->first('member_id') }}</p>
                                @endif
                            </div>

                            <div class="form-group col-md-4">
                                <label for="cost_bearer">Expanse Cost Will Be Paid By <span
                                        class="text-danger">*</span></label>
                                <select id="cost_bearer" class="custom-select" name="cost_bearer" autocomplete="off"
                                    required>
                                    <option value="">--select payment source--</option>
                                    <option value="food_advance">Food Advance (Meal Collection)</option>
                                    <option value="user">User Account (Will be deducted from user account)</option>
                                </select>
                                @if ($errors->has('cost_bearer'))
                                    <p class="text-danger">{{ $errors->first('cost_bearer') }}</p>
                                @endif
                            </div>

                            <div class="form-group col-md-4">
                                <label for="date">Date</label>
                                <input class="form-control" type="date" id="date" name="date" autocomplete="off">

                                @if ($errors->has('date'))
                                    <p class="text-danger">{{ $errors->first('date') }}</p>
                                @endif
                            </div>



                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12" id="voucher_details">
                                        <div class="row" id="row_1">

                                            <div class="form-group col-md-4">
                                                <label for="item_name_id">Item Name <span class="text-danger">*</span></label>
                                                <select id="item_name_id" class="custom-select select2bs4 item-select"
                                                    name="item_name_id[]" autocomplete="off" required>
                                                    <option value="">----select item----</option>
                                                    @foreach ($items as $item)
                                                        <option value="{{ $item->id }}" data-unit="{{ $item->unit ?? (strlen($item->item_description) <= 20 ? $item->item_description : '') }}">{{ $item->item_name }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('item_name_id'))
                                                    <p class="text-danger">{{ $errors->first('item_name_id') }}</p>
                                                @endif
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label for="unit">Unit</label>
                                                <input class="form-control unit-input" type="text" id="unit"
                                                    name="unit[]" placeholder="Unit (kg, pcs...)" autocomplete="off">
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label for="weight">Weight</label>
                                                <input class="form-control weight-input" type="text" inputmode="decimal" id="weight"
                                                    name="weight[]" placeholder="e.g. 1.5" autocomplete="off">

                                                @if ($errors->has('weight'))
                                                    <p class="text-danger">{{ $errors->first('weight') }}</p>
                                                @endif
                                            </div>

                                            <div class="form-group col-md-3 col-10">
                                                <label for="amount">Price <span class="text-danger">*</span></label>
                                                <input class="form-control" type="number" step="any" min="0" id="amount"
                                                    name="amount[]" placeholder="Enter Price" autocomplete="off" required>

                                                @if ($errors->has('amount'))
                                                    <p class="text-danger">{{ $errors->first('amount') }}</p>
                                                @endif
                                            </div>

                                            <div class="col-md-1 col-2 text-center">
                                                <div class="form-group pt-4">
                                                    <span style="font-size: 1.5em; color: Tomato; cursor: pointer;" id="addButton" title="Add More Item">
                                                        <i class="far fa-plus-square fa-lg pt-3"></i>
                                                    </span>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
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

        // Store original item options
        var originalItems = {!! json_encode($items->pluck('item_name', 'id')->toArray()) !!};
        var originalItemIds = {!! json_encode($items->pluck('id')->toArray()) !!};
        var itemUnits = {!! json_encode($items->mapWithKeys(function($item) {
            return [$item->id => $item->unit ?? (strlen($item->item_description) <= 20 ? $item->item_description : '')];
        })) !!};

        //Initialize Item Select2 Elements
        function initializeItemSelect2(selector) {
            // Check if already initialized
            if ($(selector).hasClass('select2-hidden-accessible')) {
                $(selector).select2('destroy');
            }
            $(selector).select2({
                theme: 'bootstrap4',
                tags: true,
                placeholder: '----select item----',
                allowClear: true
            });
        }

        //Initialize Select2 Elements for items
        $('select[name="item_name_id[]"]').each(function() {
            initializeItemSelect2(this);
        });
        //  SweetAlert2 
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            icon: 'success',
            showConfirmbutton: false,
            timer: 3000
        });


        // pass the csrf token for post method.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {

            // Select myself (admin) for expanse entry
            $('#selectMyselfExpanse').on('click', function() {
                var myMemberId = '{{ session('member_id') }}';
                $('#member_id').val(myMemberId).trigger('change');
            });

            // Handle item selection/create for all item dropdowns (using event delegation)
            $(document).on('select2:select', 'select[name="item_name_id[]"]', function(e) {
                var data = e.params.data;
                var itemId = data.id;
                var itemName = data.text;
                var $select = $(this);

                // Check if it's a new item (not in original options)
                if (itemId && !originalItemIds.includes(parseInt(itemId))) {
                    // This is a new item, show SweetAlert
                    Swal.fire({
                        title: 'Create New Item?',
                        text: 'Do you want to create "' + itemName + '" as a new item?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Create it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        console.log('Item SweetAlert result:', result);
                        console.log('isConfirmed:', result.isConfirmed);
                        console.log('value:', result.value);

                        if (result.isConfirmed || result.value === true) {
                            console.log('Creating item:', itemName);
                            // Create item via AJAX (without category)
                            $.ajax({
                                url: "{{ route('store.foodItem') }}",
                                method: 'POST',
                                dataType: 'json',
                                data: {
                                    item_name: itemName,
                                    food_category_id: null
                                },
                                success: function(response) {
                                    console.log('Item creation response:', response);
                                    if (response && response.success) {
                                        // Update original items
                                        originalItems[response.item.id] = response.item
                                            .item_name;
                                        originalItemIds.push(response.item.id);
                                        if (response.item.unit) {
                                            itemUnits[response.item.id] = response.item.unit;
                                        }

                                        // Add new option to all item selects
                                        $('select[name="item_name_id[]"]').each(
                                            function() {
                                                var $itemSelect = $(this);

                                                // Only add if not already exists
                                                if ($itemSelect.find(
                                                        'option[value="' + response
                                                        .item.id + '"]').length ===
                                                    0) {
                                                    var newOption = new Option(
                                                        response.item.item_name,
                                                        response.item.id, false,
                                                        false);
                                                    $itemSelect.append(newOption);
                                                }
                                            });

                                        // Select the new item in current select
                                        $select.val(response.item.id).trigger('change');

                                        Toast.fire({
                                            type: 'success',
                                            title: 'Item created successfully!'
                                        });
                                    } else {
                                        $select.val('').trigger('change');
                                        Toast.fire({
                                            type: 'error',
                                            title: response.message ||
                                                'Failed to create item. Please try again.'
                                        });
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error('AJAX Error:', error);
                                    console.error('Status:', status);
                                    console.error('Response:', xhr.responseText);
                                    $select.val('').trigger('change');
                                    Toast.fire({
                                        type: 'error',
                                        title: 'Failed to create item. Please try again.'
                                    });
                                }
                            });
                        } else {
                            // User cancelled, clear selection
                            console.log('User cancelled item creation');
                            $select.val('').trigger('change');
                        }
                    });
                }
            });

            $('#form').on("submit", function(event) {
                event.preventDefault();
                var form = new FormData(this);

                $.ajax({
                    url: "{{ route('store.expanse') }}",
                    data: form,
                    contentType: false,
                    cache: false,
                    processData: false,
                    method: "POST",
                    success: function(response) {
                        // alert(response);
                        // alert('successfully stored');


                        // console.lo()
                        if (response == "success") {
                            Toast.fire({
                                type: 'success',
                                title: 'Expanse successfully saved.',
                            });
                            // Redirect to expanse list after 1.5 seconds
                            setTimeout(function() {
                                window.location.href =
                                    "{{ route('admin.view.expanse') }}";
                            }, 1500);
                        } else {
                            $("#form")[0].reset();
                        }

                        //   msg ="<div class='alert alert-dark'>"+response+"</div>";
                        // 	      $("#msg").html(msg);
                    },
                    error: function(error) {
                        Toast.fire({
                            type: 'error',
                            title: 'Something Error Found, Please try again.',
                        });
                    }
                });


            });

            // $('#category_id').on("change",function(){
            //     var category_id = $('#category_id').val();
            //    // alert(category_id);
            //     $.ajax({
            //         url:"{{ route('expanse.onChange') }}",
            //         data:{id:category_id},
            //         contentType:false,
            //         cache:false,
            //         processData:true,
            //         method:"GET",
            //         success:function(response){
            //             if(response){
            //                 $('#item_name_id').empty();
            //                 $('#item_name_id').focus();
            //                 $('#item_name_id').append('<option>----Please select----</option>');
            //                 $.each(response, function(key,value){
            //                     $('select[name="item_name_id[]"]').append('<option value=" '+value.id+' ">'+value.item_name+'</option>');
            //                 });
            //             }else{
            //                 $('#item_name_id').empty();
            //             }
            //         },
            //         error:function(error){

            //         }
            //     });

            // });


            // Auto-fill Unit when item is selected
            $(document).on('change select2:select', 'select[name="item_name_id[]"]', function(e) {
                var itemId = (e.params && e.params.data) ? e.params.data.id : $(this).val();
                var $row = $(this).closest('.row');
                var $unitInput = $row.find('.unit-input');
                var unit = '';

                if (itemId) {
                    var $opt = $(this).find('option[value="' + itemId + '"]');
                    if ($opt.length && $opt.data('unit')) {
                        unit = $opt.data('unit');
                    } else if (itemUnits && itemUnits[itemId]) {
                        unit = itemUnits[itemId];
                    }
                }

                $unitInput.val(unit || '');
            });

            // Enforce strictly fraction numbers only in weight input (digits and at most one decimal point, absolutely no text)
            $(document).on('input', '.weight-input', function() {
                var val = $(this).val();
                var clean = val.replace(/[^0-9.]/g, '');
                var parts = clean.split('.');
                if (parts.length > 2) {
                    clean = parts[0] + '.' + parts.slice(1).join('');
                }
                if (val !== clean) {
                    $(this).val(clean);
                }
            });

            $(document).on('keydown', '.weight-input', function(e) {
                // Allow: backspace(8), tab(9), enter(13), escape(27), decimal point(110, 190)
                if ([8, 9, 13, 27, 46, 110, 190].indexOf(e.keyCode) !== -1 ||
                    // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                    (e.ctrlKey === true || e.metaKey === true) ||
                    // Allow: home, end, left, right, up, down
                    (e.keyCode >= 35 && e.keyCode <= 40)) {
                    
                    // Prevent more than one decimal point
                    if ((e.keyCode === 110 || e.keyCode === 190 || e.key === '.') && $(this).val().indexOf('.') !== -1) {
                        e.preventDefault();
                        return false;
                    }
                    return;
                }
                // Block any key that is not a numeric digit (0-9 from top row 48-57, or numpad 96-105)
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                    return false;
                }
            });

            $(document).on('paste', '.weight-input', function(e) {
                e.preventDefault();
                var clipboardData = e.originalEvent.clipboardData || window.clipboardData;
                var pastedData = clipboardData ? clipboardData.getData('text') : '';
                var clean = pastedData.replace(/[^0-9.]/g, '');
                var currentVal = $(this).val();
                if (currentVal.indexOf('.') !== -1) {
                    clean = clean.replace(/\./g, '');
                } else {
                    var parts = clean.split('.');
                    if (parts.length > 2) {
                        clean = parts[0] + '.' + parts.slice(1).join('');
                    }
                }
                document.execCommand('insertText', false, clean);
            });

            var i = 1;

            $("#addButton").click(function(e) {
                e.preventDefault();
                i++;

                var itemsOptions = '<option value="">----select item----</option>';
                @foreach ($items as $item)
                    itemsOptions += '<option value="{{ $item->id }}" data-unit="{{ $item->unit ?? (strlen($item->item_description) <= 20 ? $item->item_description : '') }}">{{ $item->item_name }}</option>';
                @endforeach

                _dynamic_div = ` <div class="row" id="row_` + i + `">

                    <div class="form-group col-md-4">
                        <label for="item_name_id">Item Name <span class="text-danger">*</span></label>
                        <select class="custom-select select2bs4 item-select" name="item_name_id[]" autocomplete="off" required>
                            ` + itemsOptions + `
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <label for="unit">Unit</label>
                        <input class="form-control unit-input" type="text" name="unit[]" placeholder="Unit (kg, pcs...)" autocomplete="off">
                    </div>

                    <div class="form-group col-md-2">
                        <label for="weight">Weight</label>
                        <input class="form-control weight-input" type="text" inputmode="decimal" name="weight[]" placeholder="e.g. 1.5" autocomplete="off">
                    </div>

                    <div class="form-group col-md-3 col-10">
                        <label for="amount">Price <span class="text-danger">*</span></label>
                        <input class="form-control" type="number" step="any" min="0" name="amount[]" placeholder="Enter Price" autocomplete="off" required>
                    </div>

                    <div class="col-md-1 col-2 text-center">
                        <div class="form-group pt-4">
                            <span style="font-size: 1.2em; color: red; cursor: pointer;" class="btn_remove" id="` + i + `" title="Remove Item"> 
                                <i class="far fa-trash-alt pt-3"></i>
                            </span>
                        </div>
                    </div>

                </div>`;
                $('#voucher_details').append(_dynamic_div);

                // Initialize Select2 for the new item dropdown
                $('#row_' + i + ' .item-select').select2({
                    theme: 'bootstrap4',
                    tags: true,
                    placeholder: '----select item----',
                    allowClear: true
                });
            });

            $(document).on('click', '.btn_remove', function() {
                var button_id = $(this).attr("id");
                $('#row_' + button_id + '').remove();
            });
        });
    </script>
@endsection
