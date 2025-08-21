<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;

    // User being edited
    public $editingUser = null;
    public $isEditing = false;

    // User being deleted
    public $deletingUser = null;
    public $isDeleting = false;
    public $adminPassword = '';

    // Bulk delete password
    public $bulkDeletePassword = '';

    // Form fields
    public $first_name = '';
    public $last_name = '';
    public $email = '';
    public $phone = '';
    public $account_level = '';

    // Bulk action properties
    public $selectedUsers = [];
    public $selectAll = false;
    public $bulkAction = '';
    public $bulkAccountLevel = '';

    // Add these properties for advanced search
    public $advancedSearch = false;
    public $exclusionTerms = [];
    public $exactPhrases = [];
    public $anyTerms = [];
    public $allTerms = [];

    // Add these properties for column visibility
    public $visibleColumns = [
        'id' => false,
        'name' => true, // Combined first_name and last_name
        'email' => true,
        'phone' => true,
        'birthday' => false,
        'gender' => false,
        'bio' => false,
        'social_media' => false,
        'occupation' => false,
        'mailing_address' => false,
        'billing_address' => false,
        'email_verified_at' => true,
        'account_level' => false,
        'provider' => false,
        'provider_avatar' => false,
        'created_at' => true,
        'updated_at' => false,
    ];

    // Columns that should be excluded from search
    private $excludedFromSearch = [
        'remember_token',
        'provider_id',
        'provider_token',
        'provider_refresh_token',
        'password'
    ];

    /**
     * Livewire listeners
     * 
     * @var array
     */
    protected $listeners = [
        'confirmUserDeletion' => 'confirmUserDeletion',
    ];

    /**
     * Confirm user deletion by setting the deletingUser property
     * 
     * @param int $userId
     * @return void
     * 
     * - 2024-07-16 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    public function confirmUserDeletion($userId)
    {
        $this->deletingUser = User::find($userId);
        $this->adminPassword = '';

        if ($this->deletingUser) {
            $this->isDeleting = true;
            $this->dispatch('open-modal', 'delete-user-modal');
        }
    }

    /**
     * Delete the user
     * 
     * @return void
     * 
     * - 2024-07-16 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    public function deleteUser()
    {
        // Validate admin password
        $this->validate([
            'adminPassword' => ['required', 'string', 'current_password'],
        ]);

        if ($this->deletingUser) {
            $this->deletingUser->delete();
            $this->dispatch('close-modal', 'delete-user-modal');
            session()->flash('message', 'User deleted successfully.');
            $this->resetDeleteForm();
        }
    }

    /**
     * Reset the delete form
     * 
     * @return void
     * 
     * - 2024-07-16 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    public function resetDeleteForm()
    {
        $this->deletingUser = null;
        $this->isDeleting = false;
        $this->adminPassword = '';
    }

    /**
     * Apply bulk action to selected users
     * 
     * @return void
     * 
     * - 2024-07-17 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    public function applyBulkAction()
    {
        if (empty($this->selectedUsers)) {
            return;
        }

        if ($this->bulkAction === 'delete') {
            // Show confirmation modal for bulk delete
            $this->bulkDeletePassword = '';
            $this->dispatch('open-modal', 'bulk-delete-modal');
        } elseif ($this->bulkAction === 'account_level' && !empty($this->bulkAccountLevel)) {
            // Update account level for selected users
            User::whereIn('id', $this->selectedUsers)
                ->update(['account_level' => $this->bulkAccountLevel]);
            
            session()->flash('message', count($this->selectedUsers) . ' users updated successfully.');
            $this->resetBulkActions();
        }
    }

    /**
     * Process bulk deletion after confirmation
     * 
     * @return void
     * 
     * - 2024-07-17 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    public function bulkDeleteConfirmed()
    {
        // Validate admin password
        $this->validate([
            'bulkDeletePassword' => ['required', 'string', 'current_password'],
        ]);

        // Delete the selected users
        $deletedCount = User::whereIn('id', $this->selectedUsers)->delete();

        // Reset selections and show success message
        $this->resetBulkActions();
        $this->bulkDeletePassword = '';
        $this->dispatch('close-modal', 'bulk-delete-modal');
        session()->flash('message', $deletedCount . ' users deleted successfully.');
    }

    /**
     * Reset bulk action selections
     * 
     * @return void
     * 
     * - 2024-07-17 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    public function resetBulkActions()
    {
        $this->selectedUsers = [];
        $this->selectAll = false;
        $this->bulkAction = '';
        $this->bulkAccountLevel = '';
    }

    /**
     * Toggle advanced search mode
     * 
     * - 2024-07-11 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    public function toggleAdvancedSearch()
    {
        $this->advancedSearch = !$this->advancedSearch;
        $this->resetPage();
    }

    /**
     * Parse search query for advanced search operators
     * 
     * - 2024-07-11 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    public function parseSearchQuery()
    {
        // Reset arrays
        $this->exclusionTerms = [];
        $this->exactPhrases = [];
        $this->anyTerms = [];
        $this->allTerms = [];
        
        $query = $this->search;
        
        // Extract exact phrases in one pass with better regex
        preg_match_all('/"([^"]+)"/', $query, $exactMatches);
        if (!empty($exactMatches[1])) {
            $this->exactPhrases = $exactMatches[1];
            $query = preg_replace('/"[^"]+"/', '', $query);
        }
        
        // Extract exclusion terms more efficiently
        preg_match_all('/-(\S+)/', $query, $exclusionMatches);
        if (!empty($exclusionMatches[1])) {
            $this->exclusionTerms = $exclusionMatches[1];
            $query = preg_replace('/-\S+/', '', $query);
        }
        
        // Process remaining terms with better filtering
        $this->allTerms = array_values(array_filter(explode(' ', trim($query)), function($term) {
            return !empty(trim($term));
        }));
    }

    /**
     * Get the filtered users query with advanced search capabilities
     * 
     * @return \Illuminate\Database\Eloquent\Builder
     * 
     * - 2024-07-11 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    private function getFilteredUsers()
    {
        // Parse search query if not empty
        if (!empty(trim($this->search))) {
            $this->parseSearchQuery();
        }
        
        return User::where(function ($query) {
            // Skip processing if search is empty
            if (empty(trim($this->search))) {
                return;
            }
            
            // Check for direct gender matches first (most efficient)
            $searchTerm = strtolower(trim($this->search));
            $genderTerms = ['male', 'female', 'non-binary', 'other', 'prefer-not-to-say'];
            
            if (in_array($searchTerm, $genderTerms)) {
                $query->where('gender', $searchTerm);
                return;
            }
            
            // Use indexed fields first for better performance
            if (!$this->advancedSearch && empty($this->exactPhrases) && empty($this->exclusionTerms)) {
                $query->where(function($q) {
                    $searchTerm = $this->search;
                    
                    // Search indexed fields first
                    $q->where('id', 'like', '%' . $searchTerm . '%')
                      ->orWhere('email', 'like', '%' . $searchTerm . '%')
                      ->orWhere('first_name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('account_level', 'like', '%' . $searchTerm . '%');
                    
                    // Then search non-indexed fields
                    $q->orWhere('phone', 'like', '%' . $searchTerm . '%')
                      ->orWhere('gender', 'like', '%' . $searchTerm . '%')
                      ->orWhere('bio', 'like', '%' . $searchTerm . '%')
                      ->orWhere('occupation', 'like', '%' . $searchTerm . '%')
                      ->orWhere('provider', 'like', '%' . $searchTerm . '%');
                    
                    // Handle JSON fields safely
                    $this->searchJsonFields($q, $searchTerm);
                });
                
                // Handle date searches more efficiently
                $this->handleDateSearch($query, trim($this->search));
                
                return;
            }
            
            // Advanced search implementation
            $query->where(function($advQuery) {
                // Check for gender terms in exact phrases
                foreach ($this->exactPhrases as $key => $phrase) {
                    $lowerPhrase = strtolower($phrase);
                    $genderTerms = ['male', 'female', 'non-binary', 'other', 'prefer-not-to-say'];
                    
                    if (in_array($lowerPhrase, $genderTerms)) {
                        $advQuery->orWhere('gender', $lowerPhrase);
                        // Remove from exact phrases to avoid duplicate processing
                        unset($this->exactPhrases[$key]);
                        continue;
                    }
                    
                    $advQuery->orWhere(function($q) use ($phrase) {
                        // Search across all text fields for exact phrase
                        $q->where('first_name', 'like', '%' . $phrase . '%')
                          ->orWhere('last_name', 'like', '%' . $phrase . '%')
                          ->orWhere('email', 'like', '%' . $phrase . '%')
                          ->orWhere('phone', 'like', '%' . $phrase . '%')
                          ->orWhere('bio', 'like', '%' . $phrase . '%')
                          ->orWhere('occupation', 'like', '%' . $phrase . '%')
                          ->orWhere('provider', 'like', '%' . $phrase . '%');
                        
                        // Handle JSON fields safely
                        $this->searchJsonFields($q, $phrase);
                    });
                }
                
                // Check for gender terms in regular terms
                foreach ($this->allTerms as $key => $term) {
                    $lowerTerm = strtolower($term);
                    $genderTerms = ['male', 'female', 'non-binary', 'other', 'prefer-not-to-say'];
                    
                    if (in_array($lowerTerm, $genderTerms)) {
                        $advQuery->orWhere('gender', $lowerTerm);
                        // Remove from all terms to avoid duplicate processing
                        unset($this->allTerms[$key]);
                        continue;
                    }
                    
                    $advQuery->where(function($q) use ($term) {
                        // Search across all text fields
                        $q->where('id', 'like', '%' . $term . '%')
                          ->orWhere('first_name', 'like', '%' . $term . '%')
                          ->orWhere('last_name', 'like', '%' . $term . '%')
                          ->orWhere('email', 'like', '%' . $term . '%')
                          ->orWhere('phone', 'like', '%' . $term . '%')
                          ->orWhere('bio', 'like', '%' . $term . '%')
                          ->orWhere('occupation', 'like', '%' . $term . '%')
                          ->orWhere('account_level', 'like', '%' . $term . '%')
                          ->orWhere('provider', 'like', '%' . $term . '%');
                        
                        // Handle JSON fields safely
                        $this->searchJsonFields($q, $term);
                    });
                }
            });
            
            // Handle exclusion terms (must not match)
            foreach ($this->exclusionTerms as $term) {
                // Check if excluding a gender
                $lowerTerm = strtolower($term);
                $genderTerms = ['male', 'female', 'non-binary', 'other', 'prefer-not-to-say'];
                
                if (in_array($lowerTerm, $genderTerms)) {
                    $query->where('gender', '!=', $lowerTerm);
                    continue;
                }
                
                $query->where(function($q) use ($term) {
                    $q->where('first_name', 'not like', '%' . $term . '%')
                      ->where('last_name', 'not like', '%' . $term . '%')
                      ->where('email', 'not like', '%' . $term . '%')
                      ->where('phone', 'not like', '%' . $term . '%')
                      ->where('bio', 'not like', '%' . $term . '%')
                      ->where('occupation', 'not like', '%' . $term . '%')
                      ->where('account_level', 'not like', '%' . $term . '%')
                      ->where('provider', 'not like', '%' . $term . '%');
                    
                    // Handle JSON fields safely
                    $this->searchJsonFields($q, $term, true);
                });
            }
        })
        ->orderBy($this->sortField, $this->sortDirection);
    }

    /**
     * Handle date search with better performance, including year-only searches
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $searchTerm
     * 
     * - 2024-07-16 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    private function handleDateSearch($query, $searchTerm)
    {
        // Check if search term is just a 4-digit year
        $isYearSearch = preg_match('/^(19|20)\d{2}$/', $searchTerm);
        
        if ($isYearSearch) {
            $year = $searchTerm;
            // Search by year only using index-friendly queries
            $query->orWhere(function($q) use ($year) {
                $q->whereRaw('YEAR(birthday) = ?', [$year])
                  ->orWhereRaw('YEAR(created_at) = ?', [$year])
                  ->orWhereRaw('YEAR(updated_at) = ?', [$year])
                  ->orWhereRaw('YEAR(email_verified_at) = ?', [$year]);
            });
            return;
        }
        
        // Try to parse the search term as a date
        $parsedDate = $this->tryParseDate($searchTerm);
        
        if ($parsedDate) {
            // Format for SQL date comparison
            $formattedDate = $parsedDate->format('Y-m-d');
            $month = $parsedDate->format('m');
            
            // Check if the search term is just a month name
            $isMonthSearch = in_array(strtolower($searchTerm), [
                'january', 'february', 'march', 'april', 'may', 'june', 
                'july', 'august', 'september', 'october', 'november', 'december',
                'jan', 'feb', 'mar', 'apr', 'may', 'jun', 
                'jul', 'aug', 'sep', 'oct', 'nov', 'dec'
            ]);
            
            if ($isMonthSearch) {
                // Search by month only using index-friendly queries
                $query->orWhere(function($q) use ($month) {
                    $q->whereRaw('MONTH(birthday) = ?', [$month])
                      ->orWhereRaw('MONTH(created_at) = ?', [$month])
                      ->orWhereRaw('MONTH(updated_at) = ?', [$month])
                      ->orWhereRaw('MONTH(email_verified_at) = ?', [$month]);
                });
            } else {
                // Search by exact date using index-friendly queries
                $query->orWhere(function($q) use ($formattedDate) {
                    $q->whereDate('birthday', $formattedDate)
                      ->orWhereDate('created_at', $formattedDate)
                      ->orWhereDate('updated_at', $formattedDate)
                      ->orWhereDate('email_verified_at', $formattedDate);
                });
            }
        }
    }

    /**
     * Toggle column visibility
     * 
     * @param string $column
     * 
     * - 2024-07-11 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    public function toggleColumn($column)
    {
        if (isset($this->visibleColumns[$column])) {
            $this->visibleColumns[$column] = !$this->visibleColumns[$column];
        }
    }

    /**
     * Reset column visibility to default
     * 
     * - 2024-07-16 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    public function resetColumnVisibility()
    {
        $this->visibleColumns = [
            'id' => false,
            'name' => true, // Combined first_name and last_name
            'email' => true,
            'phone' => true,
            'birthday' => false,
            'gender' => false,
            'bio' => false,
            'social_media' => false,
            'occupation' => false,
            'mailing_address' => false,
            'billing_address' => false,
            'email_verified_at' => true,
            'account_level' => false,
            'provider' => false,
            'provider_avatar' => false,
            'created_at' => true,
            'updated_at' => false,
        ];
    }

    /**
     * Try to parse a string as a date with improved format detection
     * 
     * @param string $dateString
     * @return \Carbon\Carbon|null
     * 
     * - 2024-07-16 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    private function tryParseDate($dateString)
    {
        // Return null for empty strings
        if (empty(trim($dateString))) {
            return null;
        }
        
        // Handle special cases for month names
        $monthNames = [
            'january' => '01', 'february' => '02', 'march' => '03', 
            'april' => '04', 'may' => '05', 'june' => '06',
            'july' => '07', 'august' => '08', 'september' => '09', 
            'october' => '10', 'november' => '11', 'december' => '12',
            'jan' => '01', 'feb' => '02', 'mar' => '03', 
            'apr' => '04', 'may' => '05', 'jun' => '06',
            'jul' => '07', 'aug' => '08', 'sep' => '09', 
            'oct' => '10', 'nov' => '11', 'dec' => '12'
        ];
        
        $lowerDateString = strtolower($dateString);
        
        // If it's just a month name, return first day of current year's month
        if (array_key_exists($lowerDateString, $monthNames)) {
            $month = $monthNames[$lowerDateString];
            $year = date('Y');
            return \Carbon\Carbon::createFromFormat('Y-m-d', "{$year}-{$month}-01");
        }
        
        // If it's just a 4-digit year, return first day of that year
        if (preg_match('/^(19|20)\d{2}$/', $dateString)) {
            return \Carbon\Carbon::createFromFormat('Y-m-d', "{$dateString}-01-01");
        }
        
        // Try to parse using Carbon's flexible parser
        try {
            return \Carbon\Carbon::parse($dateString);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Format JSON data for display
     * 
     * @param mixed $jsonData
     * @return string
     * 
     * - 2024-07-11 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    public function formatJsonData($jsonData)
    {
        if (empty($jsonData)) {
            return 'N/A';
        }
        
        $data = is_string($jsonData) ? json_decode($jsonData, true) : $jsonData;
        
        if (!is_array($data)) {
            return 'N/A';
        }
        
        $result = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value);
            }
            $result[] = ucfirst($key) . ': ' . $value;
        }
        
        return implode(', ', $result);
    }

    /**
     * Safely search JSON fields with proper escaping
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $searchTerm
     * 
     * - 2024-07-16 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    private function searchJsonFields($query, $searchTerm, $exclude = false)
    {
        // Skip JSON search if the term contains quotes or other JSON special characters
        if (preg_match('/["\\\\]/', $searchTerm)) {
            // For terms with special characters, use LIKE on the raw JSON instead
            // This is less efficient but safer
            $query->orWhereRaw('LOWER(social_media) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
                  ->orWhereRaw('LOWER(mailing_address) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
                  ->orWhereRaw('LOWER(billing_address) LIKE ?', ['%' . strtolower($searchTerm) . '%']);
        } else {
            // For safe terms, use JSON_CONTAINS which is more accurate for JSON data
            $jsonSearchTerm = '%' . $searchTerm . '%';
            $query->orWhereRaw('JSON_CONTAINS(LOWER(social_media), JSON_QUOTE(LOWER(?)))', [$jsonSearchTerm])
                  ->orWhereRaw('JSON_CONTAINS(LOWER(mailing_address), JSON_QUOTE(LOWER(?)))', [$jsonSearchTerm])
                  ->orWhereRaw('JSON_CONTAINS(LOWER(billing_address), JSON_QUOTE(LOWER(?)))', [$jsonSearchTerm]);
        }
    }

    /**
     * Check if a user has any social media links
     * 
     * @param array|null $socialMedia
     * @return bool
     * 
     * - 2024-07-16 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    public function hasSocialMedia($socialMedia)
    {
        if (!$socialMedia || !is_array($socialMedia)) {
            return false;
        }
        
        return !empty(array_filter($socialMedia, function($value) {
            return !empty($value);
        }));
    }

    /**
     * Set up the user editing form
     * 
     * @param int $userId
     * @return void
     * 
     * - 2024-07-17 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    public function editUser($userId)
    {
        $this->editingUser = User::find($userId);
        
        if ($this->editingUser) {
            $this->isEditing = true;
            $this->first_name = $this->editingUser->first_name;
            $this->last_name = $this->editingUser->last_name;
            $this->email = $this->editingUser->email;
            $this->phone = $this->editingUser->phone ?? '';
            $this->account_level = $this->editingUser->account_level ?? '';
            
            $this->dispatch('open-modal', 'edit-user-modal');
        }
    }

    /**
     * Update the user
     * 
     * @return void
     * 
     * - 2024-07-17 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    public function updateUser()
    {
        $this->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $this->editingUser->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'account_level' => ['nullable', 'string'],
        ]);

        if ($this->editingUser) {
            $this->editingUser->update([
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'phone' => $this->phone,
                'account_level' => $this->account_level,
            ]);
            
            $this->dispatch('close-modal', 'edit-user-modal');
            session()->flash('message', 'User updated successfully.');
            $this->resetForm();
        }
    }

    /**
     * Reset the form
     * 
     * @return void
     * 
     * - 2024-07-17 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    public function resetForm()
    {
        $this->editingUser = null;
        $this->isEditing = false;
        $this->first_name = '';
        $this->last_name = '';
        $this->email = '';
        $this->phone = '';
        $this->account_level = '';
        $this->dispatch('close-modal', 'edit-user-modal');
    }

    public function render()
    {
        $users = $this->getFilteredUsers()->paginate($this->perPage);
        
        return view('livewire.admin.user-management', [
            'users' => $users,
        ]);
    }
}
