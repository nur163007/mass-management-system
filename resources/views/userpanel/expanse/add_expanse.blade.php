@extends('admin.master')

@section('heading', 'User add-expanse')
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
                        <a href="{{route('user.viewExpanse')}}" class="viewall"><i class="fas fa-list"></i> All Expanse</a>
                        <a href="{{route('user.pending.expanse')}}" class="viewall"><i class="far fa-money-bill-alt"></i> Pending Expanse</a>
                    </div>
            </div>
            @include('admin.includes.message')
            <div class="card-body">
                <form method="POST"  enctype="multipart/form-data" id="form">
                    @csrf
                       <div class="row">
                           <div class="form-group col-md-6">
                            <label for="cost_bearer">Expanse Cost Will Be Paid By <span class="text-danger">*</span></label>
                            <select id="cost_bearer" class="custom-select" name="cost_bearer" required>
                                <option value="">--select payment source--</option>
                                <option value="food_advance">Food Advance (Meal Collection)</option>
                                <option value="user">User Account (Will be deducted from my account)</option>
                            </select>
                            @if ($errors->has('cost_bearer'))
                                <p class="text-danger">{{ $errors->first('cost_bearer') }}</p>
                            @endif
                        </div>
                             
                           <div class="form-group col-md-6">
                            <label for="date">Date</label>
                            <input class="form-control" type="date" id="date" name="date">

                            @if ($errors->has('date'))
                                <p class="text-danger">{{ $errors->first('date') }}</p>
                            @endif
                        </div>
                           


                           <div class="col-md-12">
                            <div class="row">
                              <div class="col-md-12" id="voucher_details">
                                <div class="row" id="row_1">

                           <div class="form-group col-md-4">
                            <label for="item_name_id">Item Name</label>
                            <select id="item_name_id" class="custom-select select2bs4 item-select" name="item_name_id[]" autocomplete="off">
                                <option value="">----select item----</option>
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->item_name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('item_name_id'))
                                <p class="text-danger">{{ $errors->first('item_name_id') }}</p>
                            @endif
                        </div>

                        <div class="form-group col-md-4">
                            <label for="weight">Weight</label>
                            <input class="form-control" type="text" id="weight" name="weight[]"placeholder="Enter Weight">

                            @if ($errors->has('weight'))
                                <p class="text-danger">{{ $errors->first('weight') }}</p>
                            @endif
                        </div>
                           <div class="form-group col-md-3 col-11">
                               <label for="amount">Price</label>
                               <input class="form-control" type="number" id="amount" name="amount[]"placeholder="Enter Price">
   
                               @if ($errors->has('amount'))
                                   <p class="text-danger">{{ $errors->first('amount') }}</p>
                               @endif
                           </div>

                           <div class="col-md-1 col-1 text-center">
                            <div class="form-group pt-4">
                                <span style="font-size: 1.5em; color: Tomato;" id="addButton"> 
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
                                        originalItems[response.item.id] = response.item.item_name;
                                        originalItemIds.push(response.item.id);

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
                    url: "{{ route('user.store.expanse') }}",
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
                                window.location.href = "{{ route('user.viewExpanse') }}";
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

            var i = 1;

            $("#addButton").click(function(e) {
                e.preventDefault();
                i++;

                var itemsOptions = '<option value="">----select item----</option>';
                @foreach ($items as $item)
                    itemsOptions += '<option value="{{ $item->id }}">{{ $item->item_name }}</option>';
                @endforeach

                _dynamic_div = ` <div class="row" id="row_` + i + `">

                    <div class="form-group col-md-4">
                    <label for="item_name_id">Item Name</label>
                    <select class="custom-select select2bs4 item-select" name="item_name_id[]" autocomplete="off">
                                ` + itemsOptions +
                    `
                            </select>
                    @if ($errors->has('item_name_id'))
                        <p class="text-danger">{{ $errors->first('item_name_id') }}</p>
                    @endif
                    </div>

                    <div class="form-group col-md-4">
                    <label for="weight">Weight</label>
                    <input class="form-control" type="text" id="weight" name="weight[]"placeholder="Enter Weight" autocomplete="off">

                    @if ($errors->has('weight'))
                        <p class="text-danger">{{ $errors->first('weight') }}</p>
                    @endif
                    </div>
                    <div class="form-group col-md-3 col-11">
                        <label for="amount">Price</label>
                        <input class="form-control" type="number" id="amount" name="amount[]"placeholder="Enter Price" autocomplete="off">

                        @if ($errors->has('amount'))
                            <p class="text-danger">{{ $errors->first('amount') }}</p>
                        @endif
                    </div>

                    <div class="col-md-1 col-1">
                                                <div class="form-group pt-4">
                                                    <span style="font-size: 1.2em; color: red;" class="btn_remove" id="` + i + `"> 
                                                    <i class="far fa-trash-alt pt-3"></i>
                                                    </span>
                                                </div>
                                            </div>

                    </div>`;
                //console.log(_dynamic_div);
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
                //console.log(button_id);   
                $('#row_' + button_id + '').remove();
            });

        });



</script>
@endsection

