<?php

namespace App\Services;

use App\Models\Bill;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BillService
{
    /**
     * Get bill amount per person for a specific bill type
     */
    public function getBillPerPerson($billType, $month = null)
    {
        // Use full month format (F = January, February, etc.)
        $month = $month ?? Carbon::now()->format('F');
        
        // Month format conversion map (for backward compatibility)
        $monthMap = [
            'Jan' => 'January', 'Feb' => 'February', 'Mar' => 'March', 'Apr' => 'April',
            'May' => 'May', 'Jun' => 'June', 'Jul' => 'July', 'Aug' => 'August',
            'Sep' => 'September', 'Oct' => 'October', 'Nov' => 'November', 'Dec' => 'December'
        ];
        $monthFull = $monthMap[$month] ?? $month;
        
        $bill = Bill::where('bill_type', $billType)
            ->where(function($query) use ($month, $monthFull) {
                $query->where('month', $monthFull);
                // For backward compatibility, also check abbreviated form
                if ($monthFull != $month) {
                    $query->orWhere('month', $month);
                }
            })
            ->where('status', 1)
            ->first();
            
        if (!$bill) {
            return 0;
        }
        
        return $bill->per_person_amount;
    }

    /**
     * Get all bills for a month
     */
    public function getMonthlyBills($month = null)
    {
        // Use full month format (F = January, February, etc.)
        $month = $month ?? Carbon::now()->format('F');
        
        // Month format conversion map (for backward compatibility)
        $monthMap = [
            'Jan' => 'January', 'Feb' => 'February', 'Mar' => 'March', 'Apr' => 'April',
            'May' => 'May', 'Jun' => 'June', 'Jul' => 'July', 'Aug' => 'August',
            'Sep' => 'September', 'Oct' => 'October', 'Nov' => 'November', 'Dec' => 'December'
        ];
        $monthFull = $monthMap[$month] ?? $month;
        
        return Bill::where(function($query) use ($month, $monthFull) {
                $query->where('month', $monthFull);
                // For backward compatibility, also check abbreviated form
                if ($monthFull != $month) {
                    $query->orWhere('month', $month);
                }
            })
            ->where('status', 1)
            ->get();
    }

    /**
     * Get total bills per person for a month
     */
    public function getTotalBillsPerPerson($month = null)
    {
        // Use full month format (F = January, February, etc.)
        $month = $month ?? Carbon::now()->format('F');
        
        $bills = $this->getMonthlyBills($month);
        $total = 0;
        
        foreach ($bills as $bill) {
            $total += $bill->per_person_amount;
        }
        
        return $total;
    }

    /**
     * Create a bill
     */
    public function createBill($data)
    {
        // Handle gas bill special calculation
        if ($data['bill_type'] === Bill::TYPE_GAS) {
            $data['cylinder_cost'] = $data['cylinder_cost'] ?? 1500;
            $data['extra_gas_users'] = $data['extra_gas_users'] ?? [];
        }
        
        return Bill::create($data);
    }

    /**
     * Get default bill amounts per person
     */
    public function getDefaultBillAmounts()
    {
        return [
            Bill::TYPE_WATER => 145, // Per person (7 people)
            Bill::TYPE_INTERNET => 165, // Per person (6 people)
            Bill::TYPE_ELECTRICITY => 200, // Minimum per person (7 people)
            Bill::TYPE_BUA => 300, // Per person (7 people) - 600/2 = 300 per bua+moyla
            Bill::TYPE_MOYLA => 300, // Per person (7 people) - 600/2 = 300 per bua+moyla
        ];
    }
}

