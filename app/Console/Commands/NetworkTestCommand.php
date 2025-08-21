<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Dapphp\Radius\Radius;

class NetworkTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'network:test {host : Target hostname or IP address}
                            {port : Target port number}
                            {--type=udp : Test type (udp or radius)}
                            {--username= : Username for RADIUS authentication}
                            {--password= : Password for RADIUS authentication}
                            {--secret= : RADIUS shared secret}
                            {--timeout=5 : Connection timeout in seconds}
                            {--nas-id=FestivalWiFi : NAS-Identifier for RADIUS}
                            {--debug : Enable debug output}
                            {--data=ping : Data to send for UDP test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test network connectivity (UDP or RADIUS)';

    /**
     * Execute the console command.
     * 
     * - 2023-05-22 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    public function handle(): int
    {
        $type = strtolower($this->option('type'));
        $host = $this->argument('host');
        $port = (int)$this->argument('port');
        $timeout = (int)$this->option('timeout');
        
        if ($type === 'udp') {
            return $this->testUdp($host, $port, $timeout);
        } elseif ($type === 'radius') {
            $username = $this->option('username');
            $password = $this->option('password');
            $secret = $this->option('secret');
            
            if (empty($username) || empty($password) || empty($secret)) {
                $this->error('RADIUS testing requires --username, --password, and --secret options');
                return 1;
            }
            
            return $this->testRadius($host, $port, $username, $password, $secret, $timeout);
        } else {
            $this->error("Unknown test type: {$type}. Supported types: udp, radius");
            return 1;
        }
    }
    
    /**
     * Test UDP connectivity to a host and port
     */
    protected function testUdp(string $host, int $port, int $timeout): int
    {
        $data = $this->option('data');
        
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
        
        // Send UDP packet
        $this->line("Sending UDP packet: '{$data}'");
        $result = @socket_sendto($socket, $data, strlen($data), 0, $host, $port);
        
        if ($result === false) {
            $errorCode = socket_last_error($socket);
            $errorMsg = socket_strerror($errorCode);
            $this->error("Failed to send UDP packet: {$errorMsg} (Error code: {$errorCode})");
            socket_close($socket);
            return 1;
        }
        
        $this->line("UDP packet sent successfully ({$result} bytes)");
        
        // Wait for response
        $this->line("Waiting for response (timeout: {$timeout}s)...");
        $response = '';
        $from = '';
        $port = 0;
        
        $bytesReceived = @socket_recvfrom($socket, $response, 4096, 0, $from, $port);
        
        if ($bytesReceived === false) {
            $errorCode = socket_last_error($socket);
            if ($errorCode === 11 || $errorCode === 35) { // EAGAIN or EWOULDBLOCK
                $this->line("No response received (timed out after {$timeout}s)");
                $this->line("This is normal for many UDP services that don't respond to test packets.");
                $this->line("The port may still be open and accepting traffic.");
            } else {
                $this->error("Error receiving response: " . socket_strerror($errorCode));
            }
        } else {
            $this->info("Received response from {$from}:{$port} ({$bytesReceived} bytes)");
            $this->line("Response data: " . bin2hex($response));
        }
        
        socket_close($socket);
        $this->line("UDP test completed");
        return 0;
    }
    
    /**
     * Test RADIUS authentication to a host and port
     */
    protected function testRadius(string $host, int $port, string $username, string $password, string $secret, int $timeout): int
    {
        $nasId = $this->option('nas-id');
        $debug = $this->option('debug');
        
        $this->info("Testing RADIUS authentication to {$host}:{$port}...");
        
        try {
            // Create a new RADIUS client
            $radius = new Radius();
            
            // Configure the client - use the server method with host:port format
            // since setPort() doesn't exist
            $radius->setServer("{$host}:{$port}")      // RADIUS server hostname/IP and port
                  ->setSecret($secret)                 // RADIUS shared secret
                  ->setNasIpAddress(gethostbyname(gethostname())); // Auto-detect IP
            
            // Set NAS-Identifier attribute manually
            $radius->setAttribute(32, $nasId);         // 32 is the attribute ID for NAS-Identifier
            
            // Set timeout
            $radius->setTimeout($timeout);             // Timeout in seconds
            
            // Debug output is handled by the library internally
            // We'll just output more information in our command if debug is enabled
            if ($debug) {
                $this->line("RADIUS client configuration:");
                $this->line("  Server: {$host}:{$port}");
                $this->line("  Secret: " . str_repeat('*', strlen($secret)));
                $this->line("  NAS-IP-Address: " . gethostbyname(gethostname()));
                $this->line("  NAS-Identifier: {$nasId}");
                $this->line("  Timeout: {$timeout} seconds");
            }
            
            $this->line("Sending RADIUS Access-Request for user: {$username}");
            
            // Authenticate using PAP (Password Authentication Protocol)
            $authenticated = $radius->accessRequest($username, $password);
            
            if ($authenticated) {
                $this->info("RADIUS authentication successful (Access-Accept)");
                
                // The library doesn't have a getAttributes method, so we can't display attributes
                // We'll just show that authentication was successful
                if ($debug) {
                    $this->line("Authentication successful, but attribute details are not available from the library.");
                }
            } else {
                $this->warn("RADIUS authentication rejected (Access-Reject)");
                
                // Display the error message if available
                $errorMessage = $radius->getErrorMessage();
                if ($errorMessage) {
                    $this->line("Error message: {$errorMessage}");
                }
                
                if ($debug) {
                    $this->line("Authentication failed, but attribute details are not available from the library.");
                }
            }
            
            $this->line("RADIUS test completed");
            return 0;
            
        } catch (\Exception $e) {
            $this->error("RADIUS test failed: " . $e->getMessage());
            
            if ($debug) {
                $this->line("Exception details:");
                $this->line($e->getTraceAsString());
            }
            
            return 1;
        }
    }
}
