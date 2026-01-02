@extends('admin.master')

@section('heading', 'Extra Payments')
@section('title', 'extra payments')

@section('main-content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3 class="font-weight-bolder">Extra Payments</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="{{route('admin.extra_payment.create')}}" class="viewall"><i class="fas fa-plus"></i> Add Extra Payment</a>
                    </div>
                </div>

                @include('admin.includes.message')

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <form method="GET" action="{{ route('admin.extra_payment.index') }}">
                                <div class="input-group">
                                    <select name="month" class="form-control" onchange="this.form.submit()">
                                        <option value="">Select Month</option>
                                        @php
                                            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                                            $currentMonth = \Carbon\Carbon::now()->isoFormat('MMM');
                                        @endphp
                                        @foreach($months as $m)
                                            <option value="{{ $m }}" {{ ($month ?? $currentMonth) == $m ? 'selected' : '' }}>
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
                                <th>Member Name</th>
                                <th>Amount</th>
                                <th>Rent Reduction</th>
                                <th>Payment Date</th>
                                <th>Month</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $index => $payment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{ $payment->member->full_name ?? 'N/A' }}
                                    @if($payment->member && $payment->member->role_id == 2)
                                        <span class="badge badge-warning">Manager</span>
                                    @endif
                                </td>
                                <td>Tk. {{ number_format($payment->amount, 2) }}</td>
                                <td>Tk. {{ number_format($payment->rent_reduction, 2) }}</td>
                                <td>{{ $payment->payment_date ? date('d M Y', strtotime($payment->payment_date)) : 'N/A' }}</td>
                                <td>{{ $payment->month }}</td>
                                <td>{{ $payment->description ?? '-' }}</td>
                                <td style="width: 120px">
                                    <a href="{{ route('admin.extra_payment.edit', $payment->id) }}" class="btn btn-info btn-xs">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.extra_payment.delete', $payment->id) }}" 
                                       class="btn btn-danger btn-xs" 
                                       onclick="return confirm('Are you sure you want to delete this payment?')">
                                        <i class="fa fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No extra payments found for this month.</td>
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

