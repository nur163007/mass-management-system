@extends('admin.master')

@section('heading', 'Payment Details')
@section('title', 'view-payment-details')

@section('main-content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3 class="font-weight-bolder">Payment Details</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="{{route('admin.view.payment')}}" class="viewall"><i class="fas fa-list"></i> Payment List</a>
                    </div>
                </div>

                @include('admin.includes.message')

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 40%; background-color: #f4f4f4;">Member's Name</th>
                                    <td>{{ $payment->full_name }}</td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f4f4f4;">Payment Type</th>
                                    <td>
                                        @php
                                            $paymentTypes = [
                                                'food_advance' => 'Food Advance',
                                                'room_rent' => 'Room Rent',
                                                'room_advance' => 'Room Advance',
                                                'water' => 'Water Bill',
                                                'internet' => 'Internet Bill',
                                                'electricity' => 'Electricity Bill',
                                                'gas' => 'Gas Bill',
                                                'bua_moyla' => 'Bua & Moyla Bill',
                                            ];
                                            $typeName = $paymentTypes[$payment->payment_type] ?? ucfirst(str_replace('_', ' ', $payment->payment_type));
                                        @endphp
                                        {{ $typeName }}
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f4f4f4;">Payment Amount</th>
                                    <td>Tk. {{ number_format($payment->payment_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f4f4f4;">Date</th>
                                    <td>{{ date('M d, Y', strtotime($payment->date)) }}</td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f4f4f4;">Month</th>
                                    <td>{{ $payment->month }}</td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f4f4f4;">Status</th>
                                    <td>
                                        @if($payment->status == 1)
                                            <span class="badge badge-success">Paid</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($payment->notes)
                                <tr>
                                    <th style="background-color: #f4f4f4;">Notes</th>
                                    <td>{{ $payment->notes }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

