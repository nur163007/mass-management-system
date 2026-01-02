<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Member;
use App\Models\RoomMember;
use App\Services\RoomRentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    protected $roomRentService;

    public function __construct(RoomRentService $roomRentService)
    {
        $this->roomRentService = $roomRentService;
    }

    /**
     * View all rooms
     */
    public function index()
    {
        $rooms = Room::with(['roomMembers.member'])->get();
        return view('admin.room.index', compact('rooms'));
    }

    /**
     * Show form to assign member to room
     */
    public function assignForm()
    {
        $rooms = Room::all();
        // Get Manager (2) and User (3) members only, exclude Super Admin (1)
        $members = Member::whereIn('role_id', [2, 3])
            ->where('status', 1)
            ->whereNull('current_room_id')
            ->get();
        return view('admin.room.assign', compact('rooms', 'members'));
    }

    /**
     * Assign member to room
     */
    public function assignMember(Request $request)
    {
        $this->validate($request, [
            'member_id' => 'required|exists:members,id',
            'room_id' => 'required|exists:rooms,id',
            'advance_amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
        ]);

        try {
            $this->roomRentService->assignMemberToRoom(
                $request->member_id,
                $request->room_id,
                $request->advance_amount,
                $request->payment_date
            );

            return redirect()->route('admin.room.index')
                ->with('success', 'Member assigned to room successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove member from room (with refund)
     */
    public function removeMember(Request $request, $memberId)
    {
        $this->validate($request, [
            'refund_amount' => 'required|numeric|min:0',
            'refund_date' => 'required|date',
        ]);

        try {
            $this->roomRentService->removeMemberFromRoom(
                $memberId,
                $request->refund_amount,
                $request->refund_date
            );

            return redirect()->route('admin.room.index')
                ->with('success', 'Member removed from room and refund processed.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * View room details
     */
    public function show($id)
    {
        $room = Room::with(['roomMembers.member'])->findOrFail($id);
        return view('admin.room.show', compact('room'));
    }

    /**
     * Initialize default rooms (one-time setup)
     */
    public function initializeRooms()
    {
        try {
            $this->roomRentService->initializeDefaultRooms();
            return redirect()->route('admin.room.index')
                ->with('success', 'Default rooms initialized successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}

