@extends('admin.master')

@section('heading', 'Assign Member to Room')
@section('title', 'Assign Member to Room | MMS')

@section('custom_css')
    <style>
        :root {
            --ra-radius: 16px;
            --ra-transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Hero Banner */
        .ra-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%);
            border-radius: var(--ra-radius);
            color: #ffffff;
            padding: 24px 28px;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.22);
        }

        .ra-hero::after {
            content: '';
            position: absolute;
            top: -50px;
            right: -40px;
            width: 220px;
            height: 220px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.24) 0%, rgba(16, 185, 129, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .ra-hero-title {
            font-size: 1.45rem;
            font-weight: 700;
            margin-bottom: 4px;
            letter-spacing: -0.3px;
        }

        .ra-hero-sub {
            font-size: 0.88rem;
            color: #94a3b8;
            margin-bottom: 0;
        }

        .ra-hero-btn {
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 0.84rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: var(--ra-transition);
        }

        .ra-hero-btn:hover {
            background: rgba(255, 255, 255, 0.22);
            color: #ffffff;
            transform: translateY(-2px);
            text-decoration: none;
        }

        /* Panels */
        .ra-panel {
            background: #ffffff;
            border-radius: var(--ra-radius);
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.05);
            overflow: hidden;
            margin-bottom: 24px;
        }

        .ra-panel-head {
            padding: 18px 24px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .ra-panel-title {
            font-size: 1.02rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .ra-panel-body {
            padding: 26px 24px;
        }

        /* Form Controls */
        .ra-label {
            font-size: 0.82rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 7px;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .ra-input {
            border-radius: 10px !important;
            border: 1px solid #cbd5e1 !important;
            font-size: 0.92rem;
            height: 44px;
            padding: 8px 14px;
            color: #0f172a;
            background-color: #ffffff;
            transition: var(--ra-transition);
        }

        .ra-input:focus {
            border-color: #10b981 !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.14) !important;
            outline: none;
        }

        /* Select2 Bootstrap4 custom look */
        .select2-container--bootstrap4 .select2-selection--single {
            height: 44px !important;
            border-radius: 10px !important;
            border: 1px solid #cbd5e1 !important;
            display: flex !important;
            align-items: center !important;
            padding: 0 10px !important;
            transition: var(--ra-transition);
        }

        .select2-container--bootstrap4.select2-container--focus .select2-selection--single,
        .select2-container--bootstrap4.select2-container--open .select2-selection--single {
            border-color: #10b981 !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.14) !important;
        }

        .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            line-height: 42px !important;
            color: #0f172a !important;
            font-size: 0.92rem;
            padding-left: 4px !important;
        }

        .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
            height: 42px !important;
            right: 10px !important;
        }

        .ra-panel-footer {
            padding: 18px 24px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
        }

        .ra-submit-btn {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
            border: none;
            padding: 10px 24px;
            border-radius: 10px;
            font-size: 0.92rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            transition: var(--ra-transition);
        }

        .ra-submit-btn:hover {
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(16, 185, 129, 0.42);
        }

        .ra-cancel-btn {
            background: #ffffff;
            color: #475569;
            border: 1px solid #cbd5e1;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: var(--ra-transition);
        }

        .ra-cancel-btn:hover {
            background: #f1f5f9;
            color: #0f172a;
            text-decoration: none;
        }

        /* Summary & Preview Side Cards */
        .ra-mini-stat {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .ra-mini-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
        }

        .ra-room-preview {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfeff 100%);
            border: 1px solid #bbf7d0;
            border-radius: 14px;
            padding: 18px;
            display: none;
            transition: var(--ra-transition);
        }

        .ra-room-preview.active {
            display: block;
        }

        .ra-preview-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px dashed #cbd5e1;
            font-size: 0.88rem;
        }

        .ra-preview-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .ra-assigned-wrap {
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1px dashed #cbd5e1;
        }

        .ra-assigned-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 8px 12px;
            margin-bottom: 8px;
            font-size: 0.85rem;
        }

        .ra-assigned-item:last-child {
            margin-bottom: 0;
        }
    </style>
@endsection

@section('main-content')
    <section class="content">
        <div class="container-fluid">

            <!-- Hero Header -->
            <div class="ra-hero">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div class="mb-3 mb-md-0">
                        <h3 class="ra-hero-title">
                            <i class="fas fa-door-open mr-2 text-success"></i> Assign Member to Room
                        </h3>
                        <p class="ra-hero-sub">
                            Allocate an unassigned member to a room and record their initial room advance deposit.
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('admin.room.index') }}" class="ra-hero-btn">
                            <i class="fas fa-arrow-left"></i> Back to Rooms
                        </a>
                    </div>
                </div>
            </div>

            @include('admin.includes.message')

            <div class="row">
                <!-- Main Form Column -->
                <div class="col-lg-8 col-12">
                    <div class="ra-panel">
                        <div class="ra-panel-head">
                            <h5 class="ra-panel-title">
                                <i class="fas fa-user-plus text-success"></i> Room Assignment Form
                            </h5>
                            <span class="badge badge-light border text-muted px-3 py-1" style="border-radius: 50px;">
                                {{ count($members) }} Unassigned Members
                            </span>
                        </div>

                        <form action="{{ route('admin.room.assign.store') }}" method="POST" id="form" enctype="multipart/form-data">
                            @csrf
                            <div class="ra-panel-body">
                                <div class="row">
                                    <!-- Room -->
                                    <div class="form-group col-md-6 mb-4">
                                        <label for="room_id" class="ra-label">
                                            <i class="fas fa-bed text-primary"></i> Select Room <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control select2bs4" id="room_id" name="room_id" required autocomplete="off">
                                            <option value="">-- Select Room --</option>
                                            @foreach ($rooms as $room)
                                                @php
                                                    $assignedList = $room->roomMembers->map(function ($rm) {
                                                        return [
                                                            'name' => $rm->member->full_name ?? 'Unknown',
                                                            'role_id' => $rm->member->role_id ?? 3,
                                                        ];
                                                    })->values();
                                                @endphp
                                                <option value="{{ $room->id }}"
                                                    data-name="{{ $room->room_name }}"
                                                    data-rent="{{ $room->monthly_rent }}"
                                                    data-capacity="{{ $room->capacity }}"
                                                    data-members='@json($assignedList)'>
                                                    {{ $room->room_name }} (Tk. {{ number_format($room->monthly_rent, 0) }} | {{ $assignedList->count() }}/{{ $room->capacity }} Occupied)
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('room_id'))
                                            <small class="text-danger font-weight-bold d-block mt-1">{{ $errors->first('room_id') }}</small>
                                        @endif
                                    </div>

                                    <!-- Member -->
                                    <div class="form-group col-md-6 mb-4">
                                        <label for="member_id" class="ra-label">
                                            <i class="fas fa-user text-info"></i> Select Member <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control select2bs4" id="member_id" name="member_id" required autocomplete="off">
                                            <option value="">-- Select Member --</option>
                                            @foreach ($members as $member)
                                                <option value="{{ $member->id }}">
                                                    {{ $member->full_name }}
                                                    @if ($member->role_id == 2)
                                                        (Manager)
                                                    @endif
                                                    — {{ $member->email }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('member_id'))
                                            <small class="text-danger font-weight-bold d-block mt-1">{{ $errors->first('member_id') }}</small>
                                        @endif
                                    </div>

                                    <!-- Advance Amount -->
                                    <div class="form-group col-md-6 mb-3">
                                        <label for="advance_amount" class="ra-label">
                                            <i class="fas fa-money-bill-wave text-success"></i> Advance Amount (Tk.) <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" class="form-control ra-input" id="advance_amount"
                                            name="advance_amount" placeholder="e.g. 3000" step="0.01" min="0"
                                            value="{{ old('advance_amount') }}" required autocomplete="off">
                                        @if ($errors->has('advance_amount'))
                                            <small class="text-danger font-weight-bold d-block mt-1">{{ $errors->first('advance_amount') }}</small>
                                        @endif
                                    </div>

                                    <!-- Payment Date -->
                                    <div class="form-group col-md-6 mb-3">
                                        <label for="payment_date" class="ra-label">
                                            <i class="fas fa-calendar-alt text-warning"></i> Payment Date <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control ra-input" id="payment_date"
                                            name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required autocomplete="off">
                                        @if ($errors->has('payment_date'))
                                            <small class="text-danger font-weight-bold d-block mt-1">{{ $errors->first('payment_date') }}</small>
                                        @endif
                                    </div>
                                </div>

                                @if (count($members) === 0)
                                    <div class="alert alert-info border-0 mt-2 mb-0" style="border-radius: 12px;">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        All active members are currently assigned to a room.
                                    </div>
                                @endif
                            </div>

                            <div class="ra-panel-footer">
                                <a href="{{ route('admin.room.index') }}" class="ra-cancel-btn">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                                <button type="submit" id="submitBtn" class="ra-submit-btn">
                                    <i class="fas fa-check-circle"></i> Assign Member
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Side Info & Live Room Preview -->
                <div class="col-lg-4 col-12">
                    <div class="ra-panel">
                        <div class="ra-panel-head">
                            <h5 class="ra-panel-title">
                                <i class="fas fa-info-circle text-primary"></i> Overview & Room Info
                            </h5>
                        </div>
                        <div class="ra-panel-body">
                            <div class="ra-mini-stat">
                                <div>
                                    <div class="text-muted font-weight-bold" style="font-size: 0.75rem; text-transform: uppercase;">Total Rooms</div>
                                    <div class="font-weight-bold text-dark" style="font-size: 1.35rem;">{{ count($rooms) }}</div>
                                </div>
                                <div class="ra-mini-icon" style="background: #eef2ff; color: #4f46e5;">
                                    <i class="fas fa-door-closed"></i>
                                </div>
                            </div>

                            <div class="ra-mini-stat mb-4">
                                <div>
                                    <div class="text-muted font-weight-bold" style="font-size: 0.75rem; text-transform: uppercase;">Unassigned Members</div>
                                    <div class="font-weight-bold text-success" style="font-size: 1.35rem;">{{ count($members) }}</div>
                                </div>
                                <div class="ra-mini-icon" style="background: #ecfdf5; color: #059669;">
                                    <i class="fas fa-user-clock"></i>
                                </div>
                            </div>

                            <!-- Live Selected Room Details -->
                            <div id="roomPreviewBox" class="ra-room-preview">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-door-open text-success mr-2"></i>
                                    <strong class="text-dark" id="previewRoomName">Selected Room</strong>
                                </div>
                                <div class="ra-preview-row">
                                    <span class="text-muted">Monthly Room Rent</span>
                                    <strong class="text-dark" id="previewRoomRent">Tk. 0.00</strong>
                                </div>
                                <div class="ra-preview-row">
                                    <span class="text-muted">Room Capacity</span>
                                    <strong class="text-dark" id="previewRoomCapacity">0 Persons</strong>
                                </div>
                                <div class="ra-preview-row">
                                    <span class="text-muted">Base Seat Rent</span>
                                    <strong class="text-success" id="previewSeatRent">Tk. 0.00</strong>
                                </div>

                                <div class="ra-assigned-wrap">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted font-weight-bold" style="font-size: 0.78rem; text-transform: uppercase;">
                                            <i class="fas fa-users text-primary mr-1"></i> Assigned Members
                                        </span>
                                        <span class="badge badge-light border text-dark" id="previewAssignedCount">0</span>
                                    </div>
                                    <div id="previewAssignedMembers"></div>
                                </div>
                            </div>

                            <div id="roomPlaceholder" class="text-center text-muted py-3 border rounded" style="border-style: dashed !important; border-radius: 12px !important;">
                                <i class="fas fa-hand-pointer fa-lg mb-2 d-block text-secondary"></i>
                                <small>Select a room to preview rent & capacity details.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection

@section('custom_js')
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            icon: 'success',
            showConfirmButton: false,
            timer: 3000
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            $('.select2bs4').select2({
                theme: 'bootstrap4',
                width: '100%'
            });

            // Live room preview on room selection
            $('#room_id').on('change', function() {
                var $selected = $(this).find('option:selected');
                var rent = parseFloat($selected.data('rent'));
                var capacity = parseInt($selected.data('capacity'), 10);
                var name = $selected.data('name');

                if ($(this).val() && !isNaN(rent)) {
                    var seatRent = capacity > 0 ? (rent / capacity) : rent;
                    var assignedMembers = $selected.data('members') || [];
                    if (typeof assignedMembers === 'string') {
                        try { assignedMembers = JSON.parse(assignedMembers); } catch (e) { assignedMembers = []; }
                    }

                    $('#previewRoomName').text(name);
                    $('#previewRoomRent').text('Tk. ' + rent.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                    $('#previewRoomCapacity').text(capacity + ' Persons');
                    $('#previewSeatRent').text('Tk. ' + seatRent.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                    $('#previewAssignedCount').text(assignedMembers.length + ' / ' + capacity);

                    var $list = $('#previewAssignedMembers').empty();
                    if (assignedMembers.length > 0) {
                        assignedMembers.forEach(function(m) {
                            var badge = m.role_id == 2
                                ? '<span class="badge badge-warning px-2 py-1" style="border-radius: 6px; font-size: 0.72rem;">Manager</span>'
                                : '<span class="badge badge-info px-2 py-1" style="border-radius: 6px; font-size: 0.72rem;">Member</span>';
                            var $item = $('<div class="ra-assigned-item"></div>');
                            var $left = $('<span class="font-weight-bold text-dark"></span>').text(m.name);
                            $left.prepend('<i class="fas fa-user-check text-success mr-2"></i>');
                            $item.append($left).append(badge);
                            $list.append($item);
                        });
                    } else {
                        $list.html('<div class="text-muted small py-1"><i class="fas fa-info-circle mr-1"></i> No members assigned to this room yet.</div>');
                    }

                    $('#roomPlaceholder').hide();
                    $('#roomPreviewBox').addClass('active');
                } else {
                    $('#roomPreviewBox').removeClass('active');
                    $('#roomPlaceholder').show();
                }
            });

            $('#form').on("submit", function(event) {
                event.preventDefault();
                var form = $(this);
                var formData = new FormData(this);
                var $btn = $('#submitBtn');
                var originalHtml = $btn.html();

                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Assigning...');

                $.ajax({
                    url: form.attr('action'),
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    method: "POST",
                    success: function(response) {
                        Toast.fire({
                            type: 'success',
                            title: 'Member assigned to room successfully.'
                        });
                        setTimeout(function() {
                            window.location.href = '{{ route("admin.room.index") }}';
                        }, 1000);
                    },
                    error: function(xhr) {
                        $btn.prop('disabled', false).html(originalHtml);
                        var errorMsg = 'Something Error Found, Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            var errors = xhr.responseJSON.errors;
                            errorMsg = Object.values(errors).flat().join('<br>');
                        } else if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMsg = xhr.responseJSON.error;
                        }
                        Toast.fire({
                            type: 'error',
                            title: errorMsg
                        });
                    }
                });
            });
        });
    </script>
@endsection
