<div>
    @if ($purchases->isEmpty())
        <p class="text-sm text-gray-500">No purchases found.</p>
    @else
        <table class="w-full text-sm">
            <thead>
                <tr>
                    <th class="text-left p-2">Package</th>
                    <th class="text-left p-2">Amount</th>
                    <th class="text-left p-2">Purchased</th>
                    <th class="text-left p-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchases as $purchase)
                    <tr wire:key="{{ $purchase->id }}">
                        <td class="p-2">{{ $purchase->package_name }}</td>
                        <td class="p-2">${{ $purchase->amount }}</td>
                        <td class="p-2">{{ \Carbon\Carbon::parse($purchase->purchased_at)->format('M j, Y') }}</td>
                        <td class="p-2">{{ $purchase->payment_status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>