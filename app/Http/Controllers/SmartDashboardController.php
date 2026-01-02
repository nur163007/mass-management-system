<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SmartAnalyticsService;
use App\Services\MemberBalanceService;
use App\Services\SmartNotificationService;
use Carbon\Carbon;

class SmartDashboardController extends Controller
{
    protected $analyticsService;
    protected $balanceService;
    protected $notificationService;

    public function __construct(
        SmartAnalyticsService $analyticsService,
        MemberBalanceService $balanceService,
        SmartNotificationService $notificationService
    ) {
        $this->analyticsService = $analyticsService;
        $this->balanceService = $balanceService;
        $this->notificationService = $notificationService;
    }

    /**
     * Get smart analytics API endpoint
     */
    public function getAnalytics(Request $request)
    {
        $month = $request->input('month', Carbon::now()->isoFormat('MMM'));
        $analytics = $this->analyticsService->getDashboardAnalytics($month);
        
        return response()->json($analytics);
    }

    /**
     * Get member balances
     */
    public function getMemberBalances(Request $request)
    {
        $month = $request->input('month', Carbon::now()->isoFormat('MMM'));
        $balances = $this->balanceService->getAllMembersBalance($month);
        $summary = $this->balanceService->getMembersSummary($month);
        
        return response()->json([
            'balances' => $balances,
            'summary' => $summary,
        ]);
    }

    /**
     * Get optimization suggestions
     */
    public function getSuggestions(Request $request)
    {
        $month = $request->input('month', Carbon::now()->isoFormat('MMM'));
        $suggestions = $this->analyticsService->getOptimizationSuggestions($month);
        
        return response()->json($suggestions);
    }

    /**
     * Get notifications
     */
    public function getNotifications()
    {
        $notifications = $this->notificationService->getAdminNotifications();
        
        return response()->json($notifications);
    }

    /**
     * Clear analytics cache
     */
    public function clearCache(Request $request)
    {
        $month = $request->input('month', Carbon::now()->isoFormat('MMM'));
        $this->analyticsService->clearCache($month);
        
        return response()->json(['message' => 'Cache cleared successfully']);
    }
}

