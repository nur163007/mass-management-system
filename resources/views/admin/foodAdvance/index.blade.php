@extends('admin.master')

@section('heading', 'Food Advance')
@section('title', 'food-advance')

@section('main-content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3 class="font-weight-bolder">Food Advance List</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="{{route('admin.foodAdvance.create')}}" class="viewall"><i class="fas fa-plus"></i> Add Food Advance</a>
                    </div>
                </div>

                @include('admin.includes.message')

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('admin.foodAdvance.index') }}" id="filterForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Filter by Month</label>
                                            <select name="month" class="form-control" onchange="document.getElementById('filterForm').submit()">
                                                <option value="">All Months</option>
                                                @foreach($months as $month)
                                                    <option value="{{ $month }}" {{ $selectedMonth == $month ? 'selected' : '' }}>
                                                        {{ $month }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Filter by Member</label>
                                            <select name="member_id" class="form-control" onchange="document.getElementById('filterForm').submit()">
                                                <option value="">All Members</option>
                                                @foreach($members as $member)
                                                    <option value="{{ $member->id }}" {{ $selectedMemberId == $member->id ? 'selected' : '' }}>
                                                        {{ $member->full_name }}
                                                        @if ($member->role_id == 2)
                                                            (Manager)
                                                        @endif
                                                    </option>
                                                @endforeach
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
                                <th style="white-space: nowrap;">SL NO</th>
                                <th style="white-space: nowrap;">Member's Name</th>
                                <th style="white-space: nowrap;">Amount</th>
                                <th style="white-space: nowrap;">Date</th>
                                <th style="white-space: nowrap;">Month</th>
                                <th style="white-space: nowrap;">Status</th>
                                <th style="white-space: nowrap;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                            @foreach ($advances as $item)
                                <tr>
                                    <td style="white-space: nowrap;">{{ $loop->iteration }}</td>
                                    <td style="white-space: nowrap;">{{ $item->full_name }}</td>
                                    <td style="white-space: nowrap;">Tk. {{ number_format($item->amount, 2) }}</td>
                                    <td style="white-space: nowrap;">{{ $item->date }}</td>
                                    <td style="white-space: nowrap;">{{ $item->month }}</td>
                                    <td style="white-space: nowrap;">
                                        @if($item->status == 1)
                                            <span class="badge badge-success">Approved</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td style="width: 120px; white-space: nowrap;">
                                        @if($item->status == 0)
                                            <button type="button" class="btn btn-success btn-xs approve-btn" data-id="{{ $item->id }}" title="Approve" style="border-radius: 5px; padding: 5px 10px; font-weight: 600; box-shadow: 0 2px 4px rgba(0,0,0,0.2); cursor: pointer;">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                            <a href="{{route('admin.foodAdvance.edit', $item->id)}}" class="btn btn-warning btn-xs" title="Edit" style="border-radius: 5px; padding: 5px 10px; margin-left: 5px;"> 
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                        @endif
                                        <a href="{{route('admin.foodAdvance.view', $item->id)}}" class="btn btn-info btn-xs" title="View" style="border-radius: 5px; padding: 5px 10px; margin-left: 5px;"> 
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
            showConfirmbutton: false,
            timer: 3000
        });

        $(document).ready(function() {
            $("#all-category").DataTable();

            // Use event delegation on document ready
            $(document).on('click', '.approve-btn', function(e) {
                e.preventDefault();
                var id = $(this).attr('data-id');
                var btn = $(this);

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to approve this food advance request?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, approve it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed || result.value === true) {
                        $.ajax({
                            url: "{{ url('admin/foodAdvance/approve') }}/" + id,
                            type: 'GET',
                            dataType: 'json',
                            success: function(response) {
                                if (response && response.message == 'Success') {
                                    Toast.fire({
                                        type: 'success',
                                        title: 'Food advance successfully approved.',
                                    });
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 1500);
                                } else {
                                    Toast.fire({
                                        type: 'error',
                                        title: (response && response.message) ? response.message : 'Something Error Found, Please try again.',
                                    });
                                }
                            },
                            error: function(xhr) {
                                Toast.fire({
                                    type: 'error',
                                    title: 'Something Error Found, Please try again.',
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection

