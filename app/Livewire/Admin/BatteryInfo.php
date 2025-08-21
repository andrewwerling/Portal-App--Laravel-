<?php

namespace App\Livewire\Admin;

use App\Models\BatteryInformation;
use Illuminate\Support\Facades\Log; // Import Log facade
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout; // Added

#[Layout('layouts.app')] // Added
class BatteryInfo extends Component
{
    use WithPagination;

    // Properties to mimic UserManagement
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;
    
    /**
     * Flag to track loading state
     * 
     * @var bool
     * 
     * - 2024-07-22 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    public $isLoading = false;
    
    /**
     * ID of the record being deleted
     * 
     * @var int|null
     * 
     * - 2024-07-22 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    public $deletingId = null;
    public $selectedUnitId = null;
    public $unitIds = [];
    public $chartData = [];
    
    /**
     * Listeners for Livewire events
     * 
     * @var array
     * 
     * - 2024-07-22 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    protected $listeners = [
        'batteryDataSubmitted' => 'refreshData',
        'unitSelected' => 'updateChartData' 
    ];

    public function mount()
    {
        $this->loadUnitIds();
        if (!empty($this->unitIds)) {
            $this->selectedUnitId = $this->unitIds[0]; // Select the first unit by default
            Log::debug('Mount: Selected Unit ID', ['unit_id' => $this->selectedUnitId]);
            $this->updateChartData();
        } else {
            Log::debug('Mount: No Unit IDs found.');
        }
    }

    public function loadUnitIds()
    {
        $this->unitIds = BatteryInformation::distinct()->orderBy('unit_id')->pluck('unit_id')->toArray();
        Log::debug('loadUnitIds: Unit IDs loaded', ['count' => count($this->unitIds), 'ids' => $this->unitIds]);
    }

    public function updatedSelectedUnitId($unitId)
    {
        Log::debug('updatedSelectedUnitId: Unit ID selected by user', ['unit_id' => $unitId]);
        $this->selectedUnitId = $unitId;
        $this->updateChartData();
    }

    public function updateChartData()
    {
        Log::debug('updateChartData: Called', ['selectedUnitId' => $this->selectedUnitId]);
        if ($this->selectedUnitId) {
            $this->chartData = BatteryInformation::where('unit_id', $this->selectedUnitId)
                                        ->orderBy('created_at', 'asc')
                                        ->get([
                                            'created_at', 
                                            'battery_percent', 
                                            'battery_voltage',
                                            'battery_current',
                                            'battery_power',
                                            'solar_voltage',
                                            'solar_current',
                                            'solar_power',
                                            'temperature_f',
                                            'humidity_percent'
                                        ])
                                        ->toArray();
            Log::debug('updateChartData: Data fetched', ['count' => count($this->chartData), 'data_sample' => array_slice($this->chartData, 0, 2)]);
            $this->dispatch('chartDataUpdated', $this->chartData);
        } else {
            Log::debug('updateChartData: No Selected Unit ID, clearing chart data.');
            $this->chartData = [];
            $this->dispatch('chartDataUpdated', $this->chartData); // Dispatch empty data to clear charts
        }
    }
    
    /**
     * Refresh the battery data after a new submission
     * 
     * @return void
     * 
     * - 2024-07-22 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    public function refreshData()
    {
        Log::debug('refreshData: Called');
        $this->isLoading = true;
        
        // This will trigger a re-render with fresh data
        $this->resetPage(); // Resets pagination
        $this->loadUnitIds(); // Reload Unit IDs in case new ones were added or some removed
        
        // If a unit ID was previously selected, try to maintain it or select the first available
        if ($this->selectedUnitId && !in_array($this->selectedUnitId, $this->unitIds)) {
            $this->selectedUnitId = !empty($this->unitIds) ? $this->unitIds[0] : null;
            Log::debug('refreshData: Previously selected Unit ID not found, re-selected', ['new_selected_id' => $this->selectedUnitId]);
        } elseif (empty($this->selectedUnitId) && !empty($this->unitIds)) {
            $this->selectedUnitId = $this->unitIds[0];
            Log::debug('refreshData: No Unit ID selected, selected first available', ['new_selected_id' => $this->selectedUnitId]);
        }
        
        $this->updateChartData(); // Update chart data based on current selection
        
        // Set loading to false after data is refreshed
        $this->isLoading = false;
        Log::debug('refreshData: Completed');
    }
    
    /**
     * Confirm deletion of a battery record
     * 
     * @param int $id
     * @return void
     * 
     * - 2024-07-22 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    public function confirmDelete($id)
    {
        Log::debug('confirmDelete: Called for ID', ['id' => $id]);
        $this->deletingId = $id;
        $this->dispatch('open-modal', 'confirm-battery-deletion');
    }
    
    /**
     * Delete a battery record
     * 
     * @return void
     * 
     * - 2024-07-22 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    public function deleteRecord()
    {
        Log::debug('deleteRecord: Called for ID', ['deletingId' => $this->deletingId]);
        if ($this->deletingId) {
            BatteryInformation::destroy($this->deletingId);
            Log::debug('deleteRecord: Record deleted', ['id' => $this->deletingId]);
            $this->deletingId = null;
            $this->dispatch('close-modal', 'confirm-battery-deletion');
            session()->flash('message', 'Battery record deleted successfully.');
            $this->refreshData(); // Refresh data after deletion
        }
    }

    public function confirmDeleteAllRecords($unitId)
    {
        Log::debug('confirmDeleteAllRecords: Called for Unit ID', ['unit_id' => $unitId]);
        $this->selectedUnitId = $unitId; // Store the unit_id for deletion
        $this->dispatch('open-modal', 'confirm-all-battery-deletion');
    }

    public function deleteAllRecords()
    {
        Log::debug('deleteAllRecords: Called for Unit ID', ['selectedUnitId' => $this->selectedUnitId]);
        if ($this->selectedUnitId) {
            BatteryInformation::where('unit_id', $this->selectedUnitId)->delete();
            Log::debug('deleteAllRecords: All records deleted for Unit ID', ['unit_id' => $this->selectedUnitId]);
            $this->dispatch('close-modal', 'confirm-all-battery-deletion');
            session()->flash('message', 'All records for unit ID ' . $this->selectedUnitId . ' deleted successfully.');
            $this->selectedUnitId = null; // Reset selected unit ID
            $this->loadUnitIds(); // Reload unit IDs as one might have been removed
            $this->refreshData(); // Refresh table data
            $this->updateChartData(); // Clear chart
        }
    }
    
    /**
     * Cancel deletion
     * 
     * @return void
     * 
     * - 2024-07-22 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    public function cancelDelete()
    {
        Log::debug('cancelDelete: Deletion cancelled', ['deletingId' => $this->deletingId]);
        $this->deletingId = null;
        $this->dispatch('close-modal', 'confirm-battery-deletion');
    }

    /**
     * Format relays data for display.
     *
     * @param mixed $relaysArray
     * @return string
     */
    public function formatRelaysData($relaysArray): string
    {
        if (empty($relaysArray) || !is_array($relaysArray)) {
            return 'N/A';
        }

        $formattedRelays = [];
        foreach ($relaysArray as $relay => $status) {
            $formattedRelays[] = htmlspecialchars($relay) . ': ' . htmlspecialchars($status);
        }
        return implode(', ', $formattedRelays);
    }
    
    /**
     * Render the battery information component.
     *
     * @return \Illuminate\View\View
     * 
     * - 2024-07-22 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    public function render()
    {
        Log::debug('render: Called', [
            'search' => $this->search,
            'sortField' => $this->sortField,
            'sortDirection' => $this->sortDirection,
            'perPage' => $this->perPage,
            'selectedUnitId' => $this->selectedUnitId
        ]);
        // Fetch battery information from the database with pagination
        $query = BatteryInformation::query();

        // Apply search - REMOVED as per user request to remove search bar
        // if (!empty($this->search)) {
        //     // Basic search - adjust fields as needed
        //     $query->where(function($q) {
        //         $q->where('unit_id', 'like', '%' . $this->search . '%')
        //           ->orWhere('ip_address', 'like', '%' . $this->search . '%');
        //     });
        // }

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $batteryData = $query->paginate($this->perPage);
        Log::debug('render: Battery data paginated', ['count' => $batteryData->count()]);
       
        if ($this->isLoading) {
            Log::debug('render: isLoading is true');
            // Potentially show a loading indicator or return minimal data
        }

        return view('livewire.admin.battery-info', [
            'batteryData' => $batteryData,
            'unitIds' => $this->unitIds,
            'selectedUnitId' => $this->selectedUnitId,
            'chartData' => $this->chartData 
        ]); // Removed ->layout('layouts.app')
    }

    // Method to handle sorting (mimicking UserManagement)
    public function sortBy($field)
    {
        Log::debug('sortBy: Called', ['field' => $field, 'currentSortField' => $this->sortField, 'currentSortDirection' => $this->sortDirection]);
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
        $this->resetPage(); // Reset to page 1 when sorting changes
        Log::debug('sortBy: New sort state', ['sortField' => $this->sortField, 'sortDirection' => $this->sortDirection]);
    }
}

