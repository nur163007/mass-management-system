<?php

namespace App\Services;

use App\Models\Room;
use App\Models\RoomMember;
use App\Models\RoomAdvance;
use App\Models\MemberExtraPayment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoomRentService
{
    /**
     * Get monthly room rent for a member
     */
    public function getMemberRoomRent($memberId, $month = null)
    {
        $month = $month ?? Carbon::now()->isoFormat('MMM');
        $year = Carbon::now()->year;
        
        $roomMember = RoomMember::where('member_id', $memberId)
            ->where('status', 1)
            ->first();
            
        if (!$roomMember) {
            return [
                'monthly_rent' => 0,
                'advance_paid' => 0,
                'extra_payment_reduction' => 0,
                'final_rent' => 0,
                'room_name' => null,
            ];
        }
        
        // Get extra payments for rent reduction
        $extraPayments = MemberExtraPayment::where('member_id', $memberId)
            ->where('month', $month)
            ->where('status', 1)
            ->sum('rent_reduction');
        
        $finalRent = $roomMember->monthly_rent - $extraPayments;
        
        return [
            'monthly_rent' => $roomMember->monthly_rent,
            'advance_paid' => $roomMember->advance_paid,
            'extra_payment_reduction' => $extraPayments,
            'final_rent' => max(0, $finalRent), // Can't be negative
            'room_name' => $roomMember->room->room_name ?? null,
        ];
    }

    /**
     * Assign member to a room
     */
    public function assignMemberToRoom($memberId, $roomId, $advanceAmount, $paymentDate = null)
    {
        $room = Room::findOrFail($roomId);
        $paymentDate = $paymentDate ?? Carbon::now();
        
        // Check if room has capacity
        $currentOccupants = RoomMember::where('room_id', $roomId)
            ->where('status', 1)
            ->count();
            
        if ($currentOccupants >= $room->capacity) {
            throw new \Exception('Room is at full capacity');
        }
        
        // Determine monthly rent for this member
        $monthlyRent = 0;
        if ($room->room_name === 'Room 1' || $room->room_name === 'Room 2') {
            $monthlyRent = 6700 / 2; // Split between 2 members
        } elseif ($room->room_name === 'Room 3') {
            // First person pays more
            if ($currentOccupants == 0) {
                $monthlyRent = 4700; // First person
            } else {
                $monthlyRent = 3500; // Second person
            }
        } elseif ($room->room_type === 'dining') {
            $monthlyRent = 2900;
        }
        
        DB::beginTransaction();
        try {
            // Create room member record
            $roomMember = RoomMember::create([
                'room_id' => $roomId,
                'member_id' => $memberId,
                'advance_paid' => $advanceAmount,
                'monthly_rent' => $monthlyRent,
                'assigned_date' => $paymentDate,
                'status' => 1,
            ]);
            
            // Record room advance payment
            RoomAdvance::create([
                'member_id' => $memberId,
                'room_id' => $roomId,
                'amount' => $advanceAmount,
                'payment_date' => $paymentDate,
                'refunded' => 0,
            ]);
            
            // Update member's current room
            DB::table('members')
                ->where('id', $memberId)
                ->update(['current_room_id' => $roomId]);
            
            DB::commit();
            return $roomMember;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Remove member from room and process refund
     */
    public function removeMemberFromRoom($memberId, $refundAmount = null, $refundDate = null)
    {
        $refundDate = $refundDate ?? Carbon::now();
        
        $roomMember = RoomMember::where('member_id', $memberId)
            ->where('status', 1)
            ->first();
            
        if (!$roomMember) {
            throw new \Exception('Member is not assigned to any room');
        }
        
        $refundAmount = $refundAmount ?? $roomMember->advance_paid;
        
        DB::beginTransaction();
        try {
            // Update room member status
            $roomMember->update([
                'status' => 0,
                'left_date' => $refundDate,
            ]);
            
            // Record refund
            $roomAdvance = RoomAdvance::where('member_id', $memberId)
                ->where('room_id', $roomMember->room_id)
                ->where('refunded', 0)
                ->first();
                
            if ($roomAdvance) {
                $roomAdvance->update([
                    'refunded' => 1,
                    'refunded_date' => $refundDate,
                    'refunded_amount' => $refundAmount,
                ]);
            }
            
            // Remove current room from member
            DB::table('members')
                ->where('id', $memberId)
                ->update(['current_room_id' => null]);
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Initialize default rooms
     */
    public function initializeDefaultRooms()
    {
        $rooms = [
            ['room_name' => 'Room 1', 'room_type' => 'room', 'monthly_rent' => 6700, 'capacity' => 2, 'advance_amount_per_person_1' => 4700],
            ['room_name' => 'Room 2', 'room_type' => 'room', 'monthly_rent' => 6700, 'capacity' => 2, 'advance_amount_per_person_1' => 4700],
            ['room_name' => 'Room 3', 'room_type' => 'room', 'monthly_rent' => 5800, 'capacity' => 2, 'advance_amount_per_person_1' => 4700, 'advance_amount_per_person_2' => 3500],
            ['room_name' => 'Dining', 'room_type' => 'dining', 'monthly_rent' => 2900, 'capacity' => 1, 'advance_amount_per_person_1' => 3000],
        ];
        
        foreach ($rooms as $roomData) {
            Room::firstOrCreate(
                ['room_name' => $roomData['room_name']],
                $roomData
            );
        }
    }
}

