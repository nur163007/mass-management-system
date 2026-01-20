@extends('admin.master')

@section('heading', 'Bills Management')
@section('title', 'view bills')

@section('main-content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3 class="font-weight-bolder">All Bills</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="{{route('admin.bill.create')}}" class="viewall"><i class="fas fa-plus"></i> Add Bill</a>
                    </div>
                </div>

                @include('admin.includes.message')

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <form method="GET" action="{{ route('admin.bill.index') }}">
                                <div class="input-group">
                                    <select name="month" class="form-control" onchange="this.form.submit()">
                                        <option value="">Select Month</option>
                                        @foreach($months as $m)
                                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                                {{ $m }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>

                    <table id="all-category" class="table table-bordered table-hover">
                        <thead class="bg-olive">
                            <tr>
                                <th>SL NO</th>
                                <th>Bill Type</th>
                                <th>Total Amount</th>
                                <th>Per Person</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bills as $index => $bill)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if($bill->bill_type == 'water')
                                        <span class="badge badge-primary">Water Bill</span>
                                    @elseif($bill->bill_type == 'internet')
                                        <span class="badge badge-info">Internet Bill</span>
                                    @elseif($bill->bill_type == 'electricity')
                                        <span class="badge badge-warning">Electricity Bill</span>
                                    @elseif($bill->bill_type == 'gas')
                                        <span class="badge badge-danger">Gas Bill</span>
                                    @elseif($bill->bill_type == 'bua_moyla' || $bill->bill_type == 'bua' || $bill->bill_type == 'moyla')
                                        <span class="badge badge-secondary">Bua & Moyla Bill</span>
                                    @else
                                        <span class="badge badge-default">{{ ucfirst($bill->bill_type) }}</span>
                                    @endif
                                </td>
                                <td>Tk. {{ number_format($bill->total_amount, 2) }}</td>
                                <td>Tk. {{ number_format($bill->per_person_amount, 2) }}</td>
                                <td style="width: 150px">
                                    <a href="{{ route('admin.bill.view', $bill->id) }}" class="btn btn-success btn-xs" title="View Details">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.bill.edit', $bill->id) }}" class="btn btn-info btn-xs" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.bill.delete', $bill->id) }}" 
                                       class="btn btn-danger btn-xs" 
                                       onclick="return confirm('Are you sure you want to delete this bill?')"
                                       title="Delete">
                                        <i class="fa fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No bills found for this month.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom_js')
<script>
    $(function() {
        $("#all-category").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
        });
    });
</script>
@endsection

