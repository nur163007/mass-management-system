@extends('admin.master')

@section('heading', 'All Expanses')
@section('title', 'view expanse')

@section('main-content') 
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3 class="font-weight-bolder">All Expanses</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="{{route('admin.add.expanse')}}" class="viewall"><i class="far fa-money-bill-alt"></i> Add Expanse</a>
                         <a href="{{route('admin.expanse.downloadPdf', ['month' => $month ?? ''])}}" class="viewall bg-cyan"><i class="far fa-file-pdf"></i> Download pdf</a>
                    </div>
                </div>

                @include('admin.includes.message')

                <div class="card-body">
                    <!-- Month Filter -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-5 col-sm-7 col-12">
                            <form method="GET" action="{{ route('admin.view.expanse') }}" id="monthFilterForm">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-olive text-white font-weight-bold">
                                            <i class="fas fa-calendar-alt mr-1"></i> Month
                                        </span>
                                    </div>
                                    <select name="month" id="monthFilter" class="form-control" onchange="document.getElementById('monthFilterForm').submit()">
                                        <option value="">All Months</option>
                                        @foreach($months as $m)
                                            <option value="{{ $m }}" {{ (isset($month) && $month == $m) ? 'selected' : '' }}>
                                                {{ $m }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if(!empty($month))
                                        <div class="input-group-append">
                                            <a href="{{ route('admin.view.expanse') }}" class="btn btn-outline-secondary" title="Reset filter to all months">
                                                <i class="fas fa-times"></i> Clear
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </form>
                        </div>
                        <div class="col-md-7 col-sm-5 col-12 text-md-right mt-2 mt-md-0">
                            @if(!empty($month))
                                <span class="badge badge-info p-2" style="font-size: 13px;">
                                    <i class="fas fa-filter"></i> Filtered by: <strong>{{ $month }}</strong>
                                </span>
                            @endif
                            <span class="badge badge-success p-2 ml-1" style="font-size: 13px;">
                                Total Amount: <strong>Tk. {{ number_format($totalAmount ?? 0, 2) }}</strong>
                            </span>
                        </div>
                    </div>
                    <table id="all-category" class="table table-bordered table-hover">
                        <thead class="bg-olive">
                            <tr>
                                <th style="white-space: nowrap;">SL NO</th>
                                <th style="white-space: nowrap;">Member's Name</th>
                                <th style="white-space: nowrap;">Total Amount</th>
                                <th style="white-space: nowrap;">Date</th>
                                <th style="white-space: nowrap;">Status</th>
                                <th style="white-space: nowrap;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                                                
                            {{-- show data using ajax --}}
                        @foreach ($bazars as $item)
                            <tr>
                                <td style="white-space: nowrap;">{{ $loop->iteration }}</td>
                                <td style="white-space: nowrap;">{{ $item->full_name }}</td>
                                <td style="white-space: nowrap;">Tk. {{ $item->total }}</td>
                                <td style="white-space: nowrap;">{{ $item->expanse_date }}</td>
                                <td style="white-space: nowrap;">
                                    @if($item->status == 1)
                                        <span class="badge badge-success">Approved</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                                <td style="width: 180px; white-space: nowrap;">
                                    <a href="{{route('details.expanse',$item->invoice_no)}}" class="btn btn-info btn-xs" title="View" style="border-radius: 5px; padding: 5px 10px;"> 
                                        <i class="fa fa-eye"></i> 
                                    </a>
                                    @if($item->status == 0)
                                        <button type="button" class="btn btn-success btn-xs approve-expanse-btn" data-id="{{ $item->id }}" title="Approve" style="border-radius: 5px; padding: 5px 10px; font-weight: 600; box-shadow: 0 2px 4px rgba(0,0,0,0.2); cursor: pointer; margin-left: 5px;">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    @endif
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
        $(document).ready(function() {
            $("#all-category").DataTable();

            //  SweetAlert2 Toast
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                icon: 'success',
                showConfirmbutton: false,
                timer: 3000
            });

            // Use event delegation for approve button
            $(document).on('click', '.approve-expanse-btn', function(e) {
                e.preventDefault();
                var id = $(this).attr('data-id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to approve this expanse?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, approve it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed || result.value === true) {
                        $.ajax({
                            url: "{{ url('admin/expanse/expanseStatus') }}/" + id + '/1',
                            type: 'GET',
                            dataType: 'json',
                            success: function(response) {
                                if (response && response.message == 'Success') {
                                    Toast.fire({
                                        type: 'success',
                                        title: 'Expanse successfully approved.',
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
