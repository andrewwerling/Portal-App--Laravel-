{{--
// In BatteryInfo.php render() method
return view('livewire.admin.battery-info', [
    'batteryData' => $batteryData,
    'unitIds' => $this->unitIds,
    'selectedUnitId' => $this->selectedUnitId, // Made available here
    'chartData' => $this->chartData          // And here
]);
--}}
<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-zinc-800 dark:text-zinc-200 leading-tight">
            {{ __('Battery Information') }}<span class="ml-2 text-sm text-gray-500 dark:text-gray-400">This page displays battery information submitted by equipment through the /battery-inform endpoint.</span>
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pb-16">
        <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-zinc-900 dark:text-zinc-100">
                {{-- <h3 class="text-lg font-semibold mb-4">Battery Information</h3> --}}

                <!-- Include Chart.js -->
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/moment@^2"></script>
                <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@^1"></script>

                @if (session('message'))
                    <div class="mb-4 p-4 bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-300 rounded-lg">
                        {{ session('message') }}
                    </div>
                @endif
                
                {{-- <div class="mb-6">
                    <p class="text-sm text-zinc-600 dark:text-zinc-300">
                        This page displays battery information submitted by equipment through the /battery-inform endpoint.
                    </p>
                    
                </div> --}}

                <!-- Unit ID Selector and Delete All Button -->
                <div class="my-4 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="w-full md:w-auto">
                        <label for="unit_id_selector" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Select Unit ID for Graphs:</label>
                        <select id="unit_id_selector" wire:model.live="selectedUnitId" 
                                class="mt-1 block w-full md:w-auto p-2.5 border-zinc-300 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-500 dark:text-zinc-300 focus:border-indigo-500 dark:focus:border-zinc-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="">-- Select Unit ID --</option>
                            @foreach($unitIds as $unitId)
                                <option value="{{ $unitId }}">{{ $unitId }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if($selectedUnitId)
                        <div class="w-full md:w-auto mt-2 md:mt-0">
                            <label for="unit_id_selector" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Delete all data for a Unit ID</label>
                            <button wire:click="confirmDeleteAllRecords('{{ $selectedUnitId }}')" 
                                    class="w-full mt-1 md:w-auto p-2.5 bg-red-600 hover:bg-red-700 text-white dark:bg-red-500 dark:hover:bg-red-600 rounded-md shadow-sm flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                Delete All Records for {{ $selectedUnitId }}
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Graphs Section -->
                <div class="my-8 grid grid-cols-1 md:grid-cols-2 gap-6" wire:key="charts-wrapper-{{ $this->getId() }}">
                    <div>
                        <h4 class="text-md font-semibold mb-2">Battery Percentage Over Time</h4>
                        <canvas id="batteryPercentChart"></canvas>
                    </div>
                    <div>
                        <h4 class="text-md font-semibold mb-2">Battery Voltage Over Time</h4>
                        <canvas id="batteryVoltageChart"></canvas>
                    </div>
                    <div>
                        <h4 class="text-md font-semibold mb-2">Battery Current Over Time</h4>
                        <canvas id="batteryCurrentChart"></canvas>
                    </div>
                    <div>
                        <h4 class="text-md font-semibold mb-2">Battery Power Over Time</h4>
                        <canvas id="batteryPowerChart"></canvas>
                    </div>
                    <div>
                        <h4 class="text-md font-semibold mb-2">Solar Voltage Over Time</h4>
                        <canvas id="solarVoltageChart"></canvas>
                    </div>
                     <div>
                        <h4 class="text-md font-semibold mb-2">Solar Current Over Time</h4>
                        <canvas id="solarCurrentChart"></canvas>
                    </div>
                     <div>
                        <h4 class="text-md font-semibold mb-2">Solar Power Over Time</h4>
                        <canvas id="solarPowerChart"></canvas>
                    </div>
                    <div>
                        <h4 class="text-md font-semibold mb-2">Temperature Over Time</h4>
                        <canvas id="temperatureChart"></canvas>
                    </div>
                    <div>
                        <h4 class="text-md font-semibold mb-2">Humidity Over Time</h4>
                        <canvas id="humidityChart"></canvas>
                    </div>
                </div>
                
                <!-- Controls: Search and Per Page -->
                <div class="my-4 flex flex-col md:flex-row justify-end items-center gap-2">
                    <div class="flex items-center space-x-2">
                        <select wire:model.live="perPage" class="p-2.5 border-zinc-300 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-500 dark:text-zinc-300 focus:border-indigo-500 dark:focus:border-zinc-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="10">10 per page</option>
                            <option value="25">25 per page</option>
                            <option value="50">50 per page</option>
                            <option value="100">100 per page</option>
                        </select>
                    </div>
                </div>


                <!-- Battery data table -->
                <div class="mt-8" wire:key="battery-table-wrapper-{{ $this->getId() }}">
                    <h3 class="text-lg font-semibold mb-4">Recent Battery Reports</h3>
                    
                    @if(count($batteryData) > 0)
                        <div class="overflow-x-auto bg-white dark:bg-zinc-800 rounded-lg shadow">
                            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                                <thead class="bg-zinc-50 dark:bg-zinc-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('unit_id')">
                                            Unit ID
                                            @if ($sortField === 'unit_id') <span>{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span> @endif
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('ip_address')">
                                            IP Address
                                            @if ($sortField === 'ip_address') <span>{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span> @endif
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('battery_percent')">
                                            Battery %
                                            @if ($sortField === 'battery_percent') <span>{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span> @endif
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('battery_voltage')">
                                            Batt Volt
                                            @if ($sortField === 'battery_voltage') <span>{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span> @endif
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Batt Curr</th> {{-- Not typically sorted --}}
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Batt Pow</th> {{-- Not typically sorted --}}
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('solar_voltage')">
                                            Sol Volt
                                            @if ($sortField === 'solar_voltage') <span>{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span> @endif
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Sol Curr</th> {{-- Not typically sorted --}}
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Sol Pow</th> {{-- Not typically sorted --}}
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('temperature_f')">
                                            Temp °F
                                            @if ($sortField === 'temperature_f') <span>{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span> @endif
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('humidity_percent')">
                                            Humidity %
                                            @if ($sortField === 'humidity_percent') <span>{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span> @endif
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Relays</th> {{-- Not typically sorted --}}
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('created_at')">
                                            Timestamp
                                            @if ($sortField === 'created_at') <span>{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span> @endif
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                                    @forelse($batteryData as $data)  {{-- Changed from @foreach to @forelse for consistency --}} 
                                        <tr wire:key="battery-row-{{ $data->id }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">{{ $data->unit_id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">{{ $data->ip_address ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">{{ $data->battery_percent }}%</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">{{ $data->battery_voltage ?? 'N/A' }} V</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">{{ $data->battery_current ?? 'N/A' }} A</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">{{ $data->battery_power ?? 'N/A' }} W</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">{{ $data->solar_voltage ?? 'N/A' }} V</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">{{ $data->solar_current ?? 'N/A' }} A</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">{{ $data->solar_power ?? 'N/A' }} W</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">{{ $data->temperature_f ?? 'N/A' }} °F</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">{{ $data->humidity_percent ?? 'N/A' }}%</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                                {{ $this->formatRelaysData($data->relays) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">{{ \Carbon\Carbon::parse($data->created_at)->format('D, M j, Y g:i A') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                                <button wire:click="confirmDelete({{ $data->id }})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty {{-- Added for @forelse --}}
                                        <tr>
                                            <td colspan="14" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                                No battery data found matching your search criteria.
                                            </td>
                                        </tr>
                                    @endforelse {{-- Changed from @endforeach --}}
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $batteryData->links() }}
                        </div>
                    @else {{-- This else corresponds to the outer if(count($batteryData) > 0) which is now handled by @forelse empty state --}}
                        <div class="bg-zinc-50 dark:bg-zinc-700 p-4 rounded-lg text-center">
                            <p class="text-zinc-600 dark:text-zinc-300">No battery data available yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <x-modal name="confirm-battery-deletion" focusable maxWidth="md">
        <form wire:submit.prevent="deleteRecord" class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Delete Battery Record
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Are you sure you want to delete this battery record? This action cannot be undone.
            </p>

            <div class="mt-6 flex justify-end">
                <x-secondary-button wire:click="cancelDelete" wire:loading.attr="disabled">
                    Cancel
                </x-secondary-button>

                <x-danger-button type="submit" wire:loading.attr="disabled">
                    Delete Record
                </x-danger-button>
            </div>
        </form>
    </x-modal>

    <!-- Delete All Records Confirmation Modal -->
    <x-modal name="confirm-all-battery-deletion" focusable maxWidth="md">
        <form wire:submit.prevent="deleteAllRecords" class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Delete All Battery Records for Unit ID: {{ $selectedUnitId }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Are you sure you want to delete ALL battery records for Unit ID <strong>{{ $selectedUnitId }}</strong>? This action cannot be undone and will remove all historical data for this unit.
            </p>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')" wire:loading.attr="disabled">
                    Cancel
                </x-secondary-button>

                <x-danger-button type="submit" wire:loading.attr="disabled">
                    Delete All Records
                </x-danger-button>
            </div>
        </form>
    </x-modal>
    
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        let chartInstances = {};

        function createOrUpdateChart(chartId, label, data, labels, yAxisLabel, borderColor, backgroundColor) {
            const canvas = document.getElementById(chartId);
            if (!canvas) {
                console.warn('Canvas element not found for chartId:', chartId);
                return;
            }
            const ctx = canvas.getContext('2d');
            
            if (chartInstances[chartId]) {
                chartInstances[chartId].destroy();
            }
            chartInstances[chartId] = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: label,
                        data: data,
                        borderColor: borderColor,
                        backgroundColor: backgroundColor,
                        fill: false,
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'hour', 
                                tooltipFormat: 'MMM D, YYYY, h:mm A',
                                displayFormats: {
                                    hour: 'MMM D, hA'
                                }
                            },
                            title: {
                                display: true,
                                text: 'Timestamp'
                            }
                        },
                        y: {
                            beginAtZero: false,
                            title: {
                                display: true,
                                text: yAxisLabel
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        },
                        legend: {
                            display: true
                        }
                    }
                }
            });
        }

        function clearAllCharts() {
            ['batteryPercentChart', 'batteryVoltageChart', 'batteryCurrentChart', 'batteryPowerChart', 'solarVoltageChart', 'solarCurrentChart', 'solarPowerChart', 'temperatureChart', 'humidityChart'].forEach(chartId => {
                const canvas = document.getElementById(chartId);
                if (!canvas) return;
                if (chartInstances[chartId]) {
                    chartInstances[chartId].destroy();
                }
                const ctx = canvas.getContext('2d');
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                ctx.textAlign = 'center';
                ctx.fillStyle = document.body.classList.contains('dark') ? '#cbd5e1' : '#4b5563';
                ctx.fillText('No data to display or unit not selected.', canvas.width / 2, canvas.height / 2);
            });
        }

        function processChartData(chartData) {
            console.log('Processing chart data:', chartData);
            if (chartData && Array.isArray(chartData) && chartData.length > 0) {
                const labels = chartData.map(item => item.created_at);
                
                createOrUpdateChart('batteryPercentChart', 'Battery %', chartData.map(item => item.battery_percent), labels, 'Percentage (%)', 'rgba(75, 192, 192, 1)', 'rgba(75, 192, 192, 0.2)');
                createOrUpdateChart('batteryVoltageChart', 'Battery Voltage', chartData.map(item => item.battery_voltage), labels, 'Voltage (V)', 'rgba(255, 99, 132, 1)', 'rgba(255, 99, 132, 0.2)');
                createOrUpdateChart('batteryCurrentChart', 'Battery Current', chartData.map(item => item.battery_current), labels, 'Current (A)', 'rgba(54, 162, 235, 1)', 'rgba(54, 162, 235, 0.2)');
                createOrUpdateChart('batteryPowerChart', 'Battery Power', chartData.map(item => item.battery_power), labels, 'Power (W)', 'rgba(255, 206, 86, 1)', 'rgba(255, 206, 86, 0.2)');
                createOrUpdateChart('solarVoltageChart', 'Solar Voltage', chartData.map(item => item.solar_voltage), labels, 'Voltage (V)', 'rgba(153, 102, 255, 1)', 'rgba(153, 102, 255, 0.2)');
                createOrUpdateChart('solarCurrentChart', 'Solar Current', chartData.map(item => item.solar_current), labels, 'Current (A)', 'rgba(255, 159, 64, 1)', 'rgba(255, 159, 64, 0.2)');
                createOrUpdateChart('solarPowerChart', 'Solar Power', chartData.map(item => item.solar_power), labels, 'Power (W)', 'rgba(75, 192, 75, 1)', 'rgba(75, 192, 75, 0.2)');
                createOrUpdateChart('temperatureChart', 'Temperature', chartData.map(item => item.temperature_f), labels, 'Temperature (°F)', 'rgba(192, 75, 75, 1)', 'rgba(192, 75, 75, 0.2)');
                createOrUpdateChart('humidityChart', 'Humidity', chartData.map(item => item.humidity_percent), labels, 'Humidity (%)', 'rgba(75, 75, 192, 1)', 'rgba(75, 75, 192, 0.2)');
            } else {
                console.log('No chart data to process or data is invalid. Clearing charts.');
                clearAllCharts();
            }
        }

        Livewire.on('chartDataUpdated', event => {
            // Prefer event.detail if available (common for custom browser events)
            // Fallback to event[0] for older Livewire or direct array dispatches
            // Finally, fallback to event itself if neither of the above yields data.
            const receivedData = event.detail || (Array.isArray(event) ? event[0] : event) || event;
            console.log('chartDataUpdated event received with:', receivedData);
            processChartData(receivedData);
        });
        
        // Initial chart data load 
        const initialSelectedUnitId = @json($selectedUnitId);
        const initialChartData = @json($chartData);
        console.log('Initial selectedUnitId from $selectedUnitId:', initialSelectedUnitId);
        console.log('Initial chartData from $chartData:', initialChartData);

        if (initialSelectedUnitId && initialChartData && initialChartData.length > 0) {
            // Directly process the data already fetched by Livewire's mount()
            processChartData(initialChartData);
        } else {
            // Clear charts if no unit is selected or no data on load
            console.log('Initial load: No unit selected or no chart data. Clearing charts.');
            clearAllCharts(); 
        }

    });
</script>
@endpush
