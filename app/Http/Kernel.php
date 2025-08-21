protected $middlewareAliases = [
    // ... other middleware
    'account.level' => \App\Http\Middleware\CheckAccountLevel::class,
];