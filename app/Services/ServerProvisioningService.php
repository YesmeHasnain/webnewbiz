<?php

namespace App\Services;

use App\Models\Server;
use Illuminate\Support\Facades\Log;
use phpseclib3\Net\SSH2;
use phpseclib3\Crypt\PublicKeyLoader;

class ServerProvisioningService
{
    public function provisionServer(Server $server): array
    {
        try {
            $ssh = $this->connectSSH($server);
            if (!$ssh['success']) return $ssh;

            $connection = $ssh['data'];

            // Install CloudPanel
            $output = $connection->exec('curl -sS https://installer.cloudpanel.io/ce/v2/install.sh -o install.sh && echo "DOWNLOAD_OK"');
            if (strpos($output, 'DOWNLOAD_OK') === false) {
                return ['success' => false, 'message' => 'Failed to download CloudPanel installer'];
            }

            $connection->exec('chmod +x install.sh && sudo bash install.sh');

            $server->update(['status' => 'active']);

            return ['success' => true, 'message' => 'Server provisioned successfully'];
        } catch (\Exception $e) {
            Log::error("Server provisioning failed: {$e->getMessage()}");
            $server->update(['status' => 'error']);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function connectSSH(Server $server): array
    {
        if (!$server->ip_address) {
            return ['success' => false, 'message' => 'Server IP not available'];
        }

        try {
            $ssh = new SSH2($server->ip_address, $server->ssh_port);

            if ($server->ssh_private_key) {
                $key = PublicKeyLoader::load($server->ssh_private_key);
                if (!$ssh->login('root', $key)) {
                    return ['success' => false, 'message' => 'SSH key authentication failed'];
                }
            } else {
                return ['success' => false, 'message' => 'No SSH key configured'];
            }

            return ['success' => true, 'data' => $ssh];
        } catch (\Exception $e) {
            Log::error("SSH connection failed: {$e->getMessage()}");
            return ['success' => false, 'message' => "SSH connection failed: {$e->getMessage()}"];
        }
    }

    public function executeCommand(Server $server, string $command): array
    {
        $ssh = $this->connectSSH($server);
        if (!$ssh['success']) return $ssh;

        try {
            $output = $ssh['data']->exec($command);
            return ['success' => true, 'data' => ['output' => $output, 'exit_code' => $ssh['data']->getExitStatus()]];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function checkHealth(Server $server): array
    {
        $result = $this->executeCommand($server, "echo 'CPU:' $(top -bn1 | grep 'Cpu(s)' | awk '{print $2}') && echo 'MEM:' $(free | grep Mem | awk '{print ($3/$2) * 100}') && echo 'DISK:' $(df / | tail -1 | awk '{print $5}' | tr -d '%')");

        if (!$result['success']) return $result;

        $output = $result['data']['output'];
        preg_match('/CPU:\s*([\d.]+)/', $output, $cpu);
        preg_match('/MEM:\s*([\d.]+)/', $output, $mem);
        preg_match('/DISK:\s*([\d.]+)/', $output, $disk);

        $server->update([
            'cpu_usage' => $cpu[1] ?? 0,
            'memory_usage' => $mem[1] ?? 0,
            'disk_usage' => $disk[1] ?? 0,
            'last_health_check' => now(),
        ]);

        return ['success' => true, 'data' => [
            'cpu_usage' => $cpu[1] ?? 0,
            'memory_usage' => $mem[1] ?? 0,
            'disk_usage' => $disk[1] ?? 0,
        ]];
    }
}
