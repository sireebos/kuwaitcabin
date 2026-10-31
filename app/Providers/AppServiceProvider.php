<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use App\Models\StockSession;
use App\Models\Property;
use App\Policies\StockSessionPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(StockSession::class, StockSessionPolicy::class);
        
        // Share property with all views for consistent navigation
        View::composer('*', function ($view) {
            // Only add property if not already set and user is authenticated
            if (!isset($view->getData()['property']) && auth()->check()) {
                $property = null;
                
                // Try from route first
                if (request()->route('property')) {
                    $property = request()->route('property');
                    if (is_numeric($property)) {
                        $property = Property::find($property);
                    }
                }
                
                // Try from session
                if (!$property && session('current_property_id')) {
                    $property = Property::find(session('current_property_id'));
                }
                
                // Get user's first property
                if (!$property) {
                    if (auth()->user()->isAdmin()) {
                        $property = Property::first();
                    } else {
                        $property = auth()->user()->property ?? Property::first();
                    }
                }
                
                if ($property) {
                    // Store in session for future requests
                    session(['current_property_id' => $property->id]);
                    $view->with('property', $property);
                }
            }
        });
    }
}
