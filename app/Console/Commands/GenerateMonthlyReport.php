<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SmartAnalyticsService;
use App\Services\MemberBalanceService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Storage;

class GenerateMonthlyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mess:generate-monthly-report {month?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate automated monthly report for mess management';

    protected $analyticsService;
    protected $balanceService;

    public function __construct(SmartAnalyticsService $analyticsService, MemberBalanceService $balanceService)
    {
        parent::__construct();
        $this->analyticsService = $analyticsService;
        $this->balanceService = $balanceService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $month = $this->argument('month') ?? Carbon::now()->subMonth()->isoFormat('MMM');
        
        $this->info("Generating monthly report for {$month}...");
        
        // Get analytics
        $analytics = $this->analyticsService->getDashboardAnalytics($month);
        $balances = $this->balanceService->getAllMembersBalance($month);
        $summary = $this->balanceService->getMembersSummary($month);
        $suggestions = $this->analyticsService->getOptimizationSuggestions($month);
        
        // Get detailed data
        $meals = DB::select("
            SELECT 
                m.full_name,
                SUM(meals.breakfast + meals.lunch + meals.dinner) as total_meals,
                meals.month
            FROM meals
            INNER JOIN members m ON meals.members_id = m.id
            WHERE meals.status = '1' AND meals.month = ?
            GROUP BY m.id, m.full_name, meals.month
        ", [$month]);
        
        $expenses = DB::select("
            SELECT 
                c.category_name,
                SUM(e.total_amount) as total_expense
            FROM expanses e
            INNER JOIN food_categories c ON e.category_id = c.id
            WHERE e.status = '1' AND e.month = ?
            GROUP BY c.id, c.category_name
        ", [$month]);
        
        // Generate PDF
        $pdf = PDF::loadView('admin.report.monthly_report_pdf', compact(
            'month',
            'analytics',
            'balances',
            'summary',
            'suggestions',
            'meals',
            'expenses'
        ));
        
        // Save to storage
        $filename = "monthly_report_{$month}_" . date('Y-m-d') . ".pdf";
        $path = storage_path("app/reports/{$filename}");
        
        // Ensure directory exists
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        
        $pdf->save($path);
        
        $this->info("Report generated successfully: {$filename}");
        $this->info("Location: {$path}");
        
        return 0;
    }
}

