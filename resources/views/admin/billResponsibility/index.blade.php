@extends('admin.master')

@section('heading', 'Bill Type Responsibilities')
@section('title', 'bill-responsibility')

@section('main-content')
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="row">
                <div class="card-header col-md-6 col-6">
                    <h3 class="font-weight-bolder">Bill Type Responsibilities</h3>
                </div>
                <div class="card-header col-md-6 col-6 text-right">
                    <a href="{{route('admin.bill.index')}}" class="viewall"><i class="fas fa-file-invoice-dollar"></i> View Bills</a>
                </div>
            </div>
            @include('admin.includes.message')

            <div class="card-body">
                <!-- Add New Responsibility Form -->
                <div class="card card-primary mb-4">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-plus-circle"></i> Assign Bill Responsibility</h3>
                    </div>
                    <form action="{{route('admin.billResponsibility.store')}}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="bill_type">Bill Type <span class="text-danger">*</span></label>
                                    <select class="form-control" id="bill_type" name="bill_type" required>
                                        <option value="">--Select Bill Type--</option>
                                        @foreach($billTypes as $key => $label)
                                            @php
                                                $existing = $responsibilities->where('bill_type', $key)->first();
                                            @endphp
                                            @if(!$existing)
                                                <option value="{{$key}}">{{$label}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @if ($errors->has('bill_type'))
                                        <p class="text-danger">{{ $errors->first('bill_type') }}</p>
                                    @endif
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="member_id">Responsible Member <span class="text-danger">*</span></label>
                                    <select class="form-control" id="member_id" name="member_id" required>
                                        <option value="">--Select Member--</option>
                                        @foreach($members as $member)
                                            <option value="{{$member->id}}">{{$member->full_name}} @if($member->role_id == 2) (Manager) @endif</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('member_id'))
                                        <p class="text-danger">{{ $errors->first('member_id') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Assign Responsibility</button>
                        </div>
                    </form>
                </div>

                <!-- Existing Responsibilities Table -->
                <div class="table-responsive">
                    <table id="all-category" class="table table-bordered table-hover">
                        <thead class="bg-olive">
                            <tr>
                                <th>SL NO</th>
                                <th>Bill Type</th>
                                <th>Responsible Member</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($responsibilities as $responsibility)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $billTypes[$responsibility->bill_type] ?? $responsibility->bill_type }}</strong></td>
                                    <td>
                                        <strong>{{ $responsibility->member->full_name }}</strong>
                                        @if($responsibility->member->role_id == 2)
                                            <span class="badge badge-info">Manager</span>
                                        @endif
                                    </td>
                                    <td style="width: 200px;">
                                        <button type="button" class="btn btn-warning btn-xs edit-responsibility" 
                                                data-id="{{ $responsibility->id }}"
                                                data-member-id="{{ $responsibility->member_id }}"
                                                data-bill-type="{{ $responsibility->bill_type }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <a href="{{route('admin.billResponsibility.destroy', $responsibility->id)}}" 
                                           class="btn btn-danger btn-xs"
                                           onclick="return confirm('Are you sure you want to remove this responsibility?')">
                                            <i class="fas fa-trash"></i> Remove
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No responsibilities assigned yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Edit Bill Responsibility</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="" method="POST" id="editForm">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_bill_type">Bill Type</label>
                        <input type="text" class="form-control" id="edit_bill_type" readonly>
                    </div>
                    <div class="form-group">
                        <label for="edit_member_id">Responsible Member <span class="text-danger">*</span></label>
                        <select class="form-control" id="edit_member_id" name="member_id" required>
                            <option value="">--Select Member--</option>
                            @foreach($members as $member)
                                <option value="{{$member->id}}">{{$member->full_name}} @if($member->role_id == 2) (Manager) @endif</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script>
    $(document).ready(function() {
        $("#all-category").DataTable();

        // Handle edit button click
        $('.edit-responsibility').on('click', function() {
            var id = $(this).data('id');
            var memberId = $(this).data('member-id');
            var billType = $(this).data('bill-type');
            
            $('#editForm').attr('action', '{{ url("admin/bill-responsibility") }}/' + id);
            $('#edit_bill_type').val($(this).closest('tr').find('td:eq(1)').text().trim());
            $('#edit_member_id').val(memberId);
            $('#editModal').modal('show');
        });
    });
</script>
@endsection
