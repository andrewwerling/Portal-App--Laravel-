<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestUdpConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'network:test-udp {host} {port} {--timeout=5} {--message=ping}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test UDP connectivity to a specified host and port';

    /**
     * Execute the console command.
     * 
     * Tests UDP connectivity by sending a packet to the specified host and port
     * and waiting for a response or error.
     * 
     * - 2023-05-22 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    public function handle(): int
    {
        $host = $this->argument('host');
        $port = (int) $this->argument('port');
        $timeout = (int) $this->option('timeout');
        $message = $this->option('message');

        $this->info("Testing UDP connection to {$host}:{$port}...");
        
        // Create UDP socket
        $socket = @socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        if ($socket === false) {
            $this->error("Failed to create socket: " . socket_strerror(socket_last_error()));
            return 1;
        }

        // Set socket timeout
        socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, ['sec' => $timeout, 'usec' => 0]);
        socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, ['sec' => $timeout, 'usec' => 0]);

        // Send test packet
        $this->info("Sending UDP packet: '{$message}'");
        $result = @socket_sendto($socket, $message, strlen($message), 0, $host, $port);
        
        if ($result === false) {
            $errorCode = socket_last_error($socket);
            $errorMsg = socket_strerror($errorCode);
            $this->error("Failed to send UDP packet: {$errorMsg} (Error code: {$errorCode})");
            socket_close($socket);
            return 1;
        }

        $this->info("UDP packet sent successfully ({$result} bytes)");
        
        // Try to receive a response (may not get one for UDP)
        $this->info("Waiting for response (timeout: {$timeout}s)...");
        $response = '';
        $from = '';
        $port = 0;
        
        $bytesReceived = @socket_recvfrom($socket, $response, 65536, 0, $from, $port);
        
        if ($bytesReceived === false) {
            $errorCode = socket_last_error($socket);
            // EAGAIN or EWOULDBLOCK indicates timeout which is normal for UDP
            if ($errorCode === 11 || $errorCode === 35) {
                $this->warn("No response received (timed out after {$timeout}s)");
                $this->line("This is normal for many UDP services that don't respond to test packets.");
                $this->line("The port may still be open and accepting traffic.");
            } else {
                $this->error("Error receiving response: " . socket_strerror($errorCode));
            }
        } else {
            $this->info("Received response from {$from}:{$port} ({$bytesReceived} bytes)");
            $this->line("Response: " . $response);
        }
        
        socket_close($socket);
        $this->info("UDP test completed");
        
        return 0;
    }
}