<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LoanProviderController;
use App\Http\Controllers\LoanPaymentController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportAIController;
use App\Http\Controllers\GeminiController;
use App\Http\Controllers\ExpenseReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;


Route::get('/test-lang', function() {
    return __('menu.dashboard');
});

Route::get('/locale', function () {
    return app()->getLocale();
});

// Locale route for switching language
Route::get('locale/{locale}', function ($locale) {
    
    // Check if the passed locale is available in our configuration
    if (in_array($locale, array_values(config('app.available_locales')))) {
        
         // If valid, store the locale in the session
         Session::put('locale', $locale);
    }
    // Redirect back to the previous page
    return redirect()->back();
})->name('change.language');



Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])
    ->name('profile.show');
Route::get('/home', function () {
    return redirect('/dashboard');
});
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index')->name('home');
Route::get('/dashboard/filter', [DashboardController::class, 'filter'])->name('dashboard.filter');



// Profile Page
Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
});




Route::get('/', function () {
    return redirect()->route('login');
});


Route::get('/gemini', function () {
    $prompt = request('q', 'হ্যালো Gemini!');
    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
    ])->post(
        'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=' . env('GEMINI_API_KEY'),
        [
            'contents' => [
                ['parts' => [['text' => $prompt]]]
            ]
        ]
    );

    $data = $response->json();
    $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'কোনো উত্তর পাওয়া যায়নি।';
    return response()->json(['answer' => $text]);
});
Route::get('/gemini', [GeminiController::class, 'ask']);
Route::get('/test-ai', function () {
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
        'Content-Type' => 'application/json',
    ])->post('https://api.openai.com/v1/chat/completions', [
        'model' => 'gpt-4o-mini', // চাইলে 'gpt-5' ও দিতে পারো
        'messages' => [
            ['role' => 'user', 'content' => 'Test message from Laravel!'],
        ],
    ]);

    return $response->json();
});



/* Route::prefix('admin')->name('admin.')->middleware(['auth','role:admin'])->group(function () {
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
}); */

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


/* Route::prefix('admin')->name('admin.')->middleware(['auth','role:admin'])->group(function () {
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
     

});
  */
  
Route::middleware(['auth','role:admin|staff'])->group(function () {
    Route::resource('product', ProductController::class); 
});  
Route::middleware(['web'])->group(function () {

    Route::get('/change-language/{lang}', function ($lang) {
        session(['locale' => $lang]);
        return back();
    })->name('change.language');

    Route::get('/test-session', function () {
        dd(session('locale'));
    });

    // তোমার সব dashboard routes এখানে থাকবে
});

Route::middleware('auth')->group(function () {
   
   Route::resource('loans', LoanController::class);
    Route::resource('loan-providers', LoanProviderController::class);
    Route::resource('loan-payments', LoanPaymentController::class);
    Route::get('/loan/installment/pay/{id}', [LoanPaymentController::class, 'paymentForm'])->name('loan.installment.form');
    Route::post('/loan/installment/pay/{id}', [LoanPaymentController::class, 'pay'])->name('loan.installment.pay');
        
    Route::prefix('admin')->name('admin.')->middleware(['auth','role:admin'])->group(function () {

        Route::resource('product', ProductController::class);
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
    });   


    Route::prefix('reports')->group(function () {
        Route::get('/daily', [ExpenseReportController::class, 'daily'])->name('reports.daily');
        Route::get('/monthly', [ExpenseReportController::class, 'monthly'])->name('reports.monthly');
        Route::get('/category', [ExpenseReportController::class, 'categoryWise'])->name('reports.category');
        Route::get('/product', [ExpenseReportController::class, 'productWise'])->name('reports.product');
        Route::get('/user', [ExpenseReportController::class, 'userWise'])->name('reports.user');
        Route::get('/income-expense', [ExpenseReportController::class, 'incomeExpenseReport'])->name('reports.incomeExpenseReport');
        Route::get('/custom', [ExpenseReportController::class, 'custom'])->name('reports.custom');
        Route::get('/top-categories', [ExpenseReportController::class, 'topCategories'])->name('reports.topCategories');
        Route::get('/reports/category', [ExpenseReportController::class, 'category'])->name('reports.category');
        // 📤 Export
        Route::get('/export/{type}/{report}', [ExpenseReportController::class, 'export'])->name('reports.export');
        Route::get('/datewise', [ExpenseReportController::class, 'datewiseReport'])->name('reports.datewise');    
    });
        
   
   


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/report-ai', [ReportAIController::class, 'index']);
    Route::post('/report-ai/analyze', [ReportAIController::class, 'analyze'])->name('report.ai');

    Route::get('expenses/get-products/{category}', [ExpenseController::class, 'getProductsByCategory']);
    Route::get('/expenses/get-products-by-category/{category}', [ExpenseController::class, 'getProductsByCategory']);
    Route::get('expenses/export/{type}', [ExpenseController::class, 'export'])->name('expenses.export');
    Route::resource('expenses', ExpenseController::class);
    Route::resource('income', IncomeController::class);

    Route::resource('expense-category', ExpenseCategoryController::class);



});

require __DIR__.'/auth.php'; 
