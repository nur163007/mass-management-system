@extends('admin.master')

@section('heading', 'Bills')
@section('title', 'bills')

@section('main-content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3 class="font-weight-bolder">All Bills</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="{{route('user.dashboard')}}" class="viewall"><i class="fas fa-home"></i> Dashboard</a>
                    </div>
                </div>

                @include('admin.includes.message')

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <form method="GET" action="{{ route('user.bill.index') }}">
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
                                <th>Bill Date</th>
                                <th>Month</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bills as $bill)
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
                                    @elseif($bill->bill_type == 'bua_moyla')
                                        <span class="badge badge-secondary">Bua & Moyla Bill</span>
                                    @else
                                        <span class="badge badge-default">{{ ucfirst($bill->bill_type) }}</span>
                                    @endif
                                </td>
                                <td>Tk. {{ number_format($bill->total_amount, 2) }}</td>
                                <td>Tk. {{ number_format($bill->per_person_amount, 2) }}</td>
                                <td>{{ $bill->bill_date ? date('d M Y', strtotime($bill->bill_date)) : 'N/A' }}</td>
                                <td>
                                    @php
                                        $monthMap = [
                                            'Jan' => 'January', 'Feb' => 'February', 'Mar' => 'March', 'Apr' => 'April',
                                            'May' => 'May', 'Jun' => 'June', 'Jul' => 'July', 'Aug' => 'August',
                                            'Sep' => 'September', 'Oct' => 'October', 'Nov' => 'November', 'Dec' => 'December'
                                        ];
                                        echo $monthMap[$bill->month] ?? $bill->month;
                                    @endphp
                                </td>
                                <td style="width: 100px">
                                    <a href="{{ route('user.bill.view', $bill->id) }}" class="btn btn-info btn-xs" title="View Details">
                                        <i class="fa fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No bills found for this month.</td>
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

