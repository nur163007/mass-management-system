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
                         <a href="{{route('admin.expanse.downloadPdf')}}" class="viewall bg-cyan"><i class="far fa-file-pdf"></i> Download pdf</a>
                    </div>
                </div>

                @include('admin.includes.message')

                <div class="card-body">
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
