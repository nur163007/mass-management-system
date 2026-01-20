<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Month format conversion map
        $monthMap = [
            'Jan' => 'January',
            'Feb' => 'February',
            'Mar' => 'March',
            'Apr' => 'April',
            'May' => 'May',
            'Jun' => 'June',
            'Jul' => 'July',
            'Aug' => 'August',
            'Sep' => 'September',
            'Oct' => 'October',
            'Nov' => 'November',
            'Dec' => 'December'
        ];

        // Convert all abbreviated months to full form
        foreach ($monthMap as $abbr => $full) {
            DB::table('bills')
                ->where('month', $abbr)
                ->update(['month' => $full]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Month format reverse conversion map
        $reverseMap = [
            'January' => 'Jan',
            'February' => 'Feb',
            'March' => 'Mar',
            'April' => 'Apr',
            'May' => 'May',
            'June' => 'Jun',
            'July' => 'Jul',
            'August' => 'Aug',
            'September' => 'Sep',
            'October' => 'Oct',
            'November' => 'Nov',
            'December' => 'Dec'
        ];

        // Convert all full form months back to abbreviated
        foreach ($reverseMap as $full => $abbr) {
            DB::table('bills')
                ->where('month', $full)
                ->update(['month' => $abbr]);
        }
    }
};
