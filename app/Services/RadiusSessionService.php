// app/Services/RadiusSessionService.php
namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RadiusSessionService
{
    public function createSession($deviceInfo)
    {
        DB::table('radacct')->insert([
            'username' => Auth::user()->email,
            'acctstarttime' => now(),
            'acctupdatetime' => now(),
            'nasipaddress' => request()->ip(),
            'callingstationid' => $deviceInfo['mac_address'] ?? null,
            'framedipaddress' => request()->ip(),
            'connectinfo_start' => json_encode($deviceInfo),
        ]);
    }

    public function updateSession($sessionId, $updateData)
    {
        DB::table('radacct')
            ->where('radacctid', $sessionId)
            ->update([
                'acctupdatetime' => now(),
                'acctinputoctets' => $updateData['input_bytes'] ?? 0,
                'acctoutputoctets' => $updateData['output_bytes'] ?? 0,
            ]);
    }
}