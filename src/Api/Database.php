<?php

declare(strict_types=1);

/*
 * This file is part of the DigitalOcean API library.
 *
 * (c) Antoine Kirk <contact@sbin.dk>
 * (c) Graham Campbell <hello@gjcampbell.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\Api;

use DigitalOceanV2\Entity\Database as DatabaseEntity;
use DigitalOceanV2\Entity\DatabaseBackup as DatabaseBackupEntity;
use DigitalOceanV2\Entity\DatabaseCluster as DatabaseClusterEntity;
use DigitalOceanV2\Entity\DatabasePool as DatabasePoolEntity;
use DigitalOceanV2\Entity\DatabaseReplica as DatabaseReplicaEntity;
use DigitalOceanV2\Entity\DatabaseRule as DatabaseRuleEntity;
use DigitalOceanV2\Entity\DatabaseUser as DatabaseUserEntity;
use DigitalOceanV2\Exception\ExceptionInterface;

/**
 * @author Filippo Fortino <filippofortino@gmail.com>
 */
class Database extends AbstractApi
{
    /**
     * @throws ExceptionInterface
     *
     * @return DatabaseClusterEntity[]
     */
    public function getAllClusters(?string $tag = null): array
    {
        $clusters = $this->get('databases', null === $tag ? [] : ['tag_name' => $tag]);

        return \array_map(function ($cluster) {
            return new DatabaseClusterEntity($cluster);
        }, $clusters->databases ?? []);
    }

    /**
     * @throws ExceptionInterface
     */
    public function getClusterById(string $id): DatabaseClusterEntity
    {
        $cluster = $this->get(\sprintf('databases/%s', $id));

        return new DatabaseClusterEntity($cluster->database);
    }

    /**
     * @throws ExceptionInterface
     */
    public function createCluster(string $name, string $engine, string $size, string $region, int $numNodes, ?string $version = null, array $tags = [], ?string $privateNetworkUuid = null): DatabaseClusterEntity
    {
        $cluster = $this->post('databases', [
            'name' => $name,
            'engine' => $engine,
            'size' => $size,
            'region' => $region,
            'num_nodes' => $numNodes,
            'version' => $version,
            'tags' => $tags,
            'private_network_uuid' => $privateNetworkUuid,
        ]);

        return new DatabaseClusterEntity($cluster->database);
    }

    /**
     * @throws ExceptionInterface
     */
    public function resize(string $clusterId, string $size, int $numNodes): void
    {
        $this->put(\sprintf('databases/%s/resize', $clusterId), [
            'size' => $size,
            'num_nodes' => $numNodes,
        ]);
    }

    /**
     * @throws ExceptionInterface
     */
    public function migrate(string $clusterId, string $region): void
    {
        $this->put(\sprintf('databases/%s/migrate', $clusterId), [
            'region' => $region,
        ]);
    }

    /**
     * @throws ExceptionInterface
     */
    public function remove(string $clusterId): void
    {
        $this->delete(\sprintf('databases/%s', $clusterId));
    }

    /**
     * @throws ExceptionInterface
     *
     * @return DatabaseRuleEntity[]
     */
    public function getFirewallRules(string $clusterId): array
    {
        $rules = $this->get(\sprintf('databases/%s/firewall', $clusterId));

        return \array_map(function ($rule) {
            return new DatabaseRuleEntity($rule);
        }, $rules->rules);
    }

    /**
     * @throws ExceptionInterface
     */
    public function updateFirewallRules(string $clusterId, array $rules): void
    {
        $this->put(\sprintf('databases/%s/firewall', $clusterId), [
            'rules' => $rules,
        ]);
    }

    /**
     * @throws ExceptionInterface
     */
    public function updateMaintenanceWindow(string $clusterId, string $day, string $hour): void
    {
        $this->put(\sprintf('databases/%s/maintenance', $clusterId), [
            'day' => $day,
            'hour' => $hour,
        ]);
    }

    /**
     * @throws ExceptionInterface
     *
     * @return DatabaseBackupEntity[]
     */
    public function getBackups(string $clusterId): array
    {
        $backups = $this->get(\sprintf('databases/%s/backups', $clusterId));

        return \array_map(function ($backup) {
            return new DatabaseBackupEntity($backup);
        }, $backups->backups);
    }

    /**
     * @throws ExceptionInterface
     */
    public function createClusterFromBackup(string $name, array $backupRestore, string $engine, string $size, string $region, int $numNodes, ?string $version = null, array $tags = [], ?string $privateNetworkUuid = null): DatabaseClusterEntity
    {
        $database = $this->post('databases', [
            'name' => $name,
            'backup_restore' => $backupRestore,
            'engine' => $engine,
            'size' => $size,
            'region' => $region,
            'num_nodes' => $numNodes,
            'version' => $version,
            'tags' => $tags,
            'private_network_uuid' => $privateNetworkUuid,
        ]);

        return new DatabaseClusterEntity($database->database);
    }

    /**
     * @throws ExceptionInterface
     *
     * @return DatabaseReplicaEntity[]
     */
    public function getAllReplicas(string $clusterId): array
    {
        $replicas = $this->get(\sprintf('databases/%s/replicas', $clusterId));

        return \array_map(function ($replica) {
            return new DatabaseReplicaEntity($replica);
        }, $replicas->replicas);
    }

    /**
     * @throws ExceptionInterface
     */
    public function getReplicaByName(string $clusterId, string $name): DatabaseReplicaEntity
    {
        $replica = $this->get(\sprintf('databases/%s/replicas/%s', $clusterId, $name));

        return new DatabaseReplicaEntity($replica->replica);
    }

    /**
     * @throws ExceptionInterface
     */
    public function createReplica(string $clusterId, string $name, string $size, ?string $region = null, array $tags = [], ?string $privateNetworkUuid = null): DatabaseReplicaEntity
    {
        $replica = $this->post(\sprintf('databases/%s/replicas', $clusterId), [
            'name' => $name,
            'size' => $size,
            'region' => $region,
            'tags' => $tags,
            'private_network_uuid' => $privateNetworkUuid,
        ]);

        return new DatabaseReplicaEntity($replica->replica);
    }

    /**
     * @throws ExceptionInterface
     */
    public function removeReplica(string $clusterId, string $name): void
    {
        $this->delete(\sprintf('databases/%s/replicas/%s', $clusterId, $name));
    }

    /**
     * @throws ExceptionInterface
     *
     * @return DatabaseUserEntity[]
     */
    public function getAllUsers(string $clusterId): array
    {
        $users = $this->get(\sprintf('databases/%s/users', $clusterId));

        return \array_map(function ($user) {
            return new DatabaseUserEntity($user);
        }, $users->users);
    }

    /**
     * @throws ExceptionInterface
     */
    public function getUserByName(string $clusterId, string $name): DatabaseUserEntity
    {
        $user = $this->get(\sprintf('databases/%s/users/%s', $clusterId, $name));

        return new DatabaseUserEntity($user->user);
    }

    /**
     * @throws ExceptionInterface
     */
    public function createUser(string $clusterId, string $name, ?string $authPlugin = null): DatabaseUserEntity
    {
        $user = $this->post(\sprintf('databases/%s/users', $clusterId), [
            'name' => $name,
            'mysql_settings' => [
                'auth_plugin' => $authPlugin,
            ],
        ]);

        return new DatabaseUserEntity($user->user);
    }

    /**
     * @throws ExceptionInterface
     */
    public function updateUserMysqlAuthMethod(string $clusterId, string $username, string $authPlugin): DatabaseUserEntity
    {
        $user = $this->post(\sprintf('databases/%s/users/%s/reset_auth', $clusterId, $username), [
            'mysql_settings' => [
                'auth_plugin' => $authPlugin,
            ],
        ]);

        return new DatabaseUserEntity($user->user);
    }

    /**
     * @throws ExceptionInterface
     */
    public function removeUser(string $clusterId, string $name): void
    {
        $this->delete(\sprintf('databases/%s/users/%s', $clusterId, $name));
    }

    /**
     * @throws ExceptionInterface
     *
     * @return DatabaseEntity[]
     */
    public function getAllDatabases(string $clusterId): array
    {
        $databases = $this->get(\sprintf('databases/%s/dbs', $clusterId));

        return \array_map(function ($database) {
            return new DatabaseEntity($database);
        }, $databases->dbs);
    }

    /**
     * @throws ExceptionInterface
     */
    public function getDatabaseByName(string $clusterId, string $name): DatabaseEntity
    {
        $database = $this->get(\sprintf('databases/%s/dbs/%s', $clusterId, $name));

        return new DatabaseEntity($database->db);
    }

    /**
     * @throws ExceptionInterface
     */
    public function createDatabase(string $clusterId, string $name): DatabaseEntity
    {
        $database = $this->post(\sprintf('databases/%s/dbs', $clusterId), [
            'name' => $name,
        ]);

        return new DatabaseEntity($database->db);
    }

    /**
     * @throws ExceptionInterface
     */
    public function removeDatabase(string $clusterId, string $name): void
    {
        $this->delete(\sprintf('databases/%s/dbs/%s', $clusterId, $name));
    }

    /**
     * @throws ExceptionInterface
     *
     * @return DatabasePoolEntity[]
     */
    public function getAllConnectionPools(string $clusterId): array
    {
        $pools = $this->get(\sprintf('databases/%s/pools', $clusterId));

        return \array_map(function ($pool) {
            return new DatabasePoolEntity($pool);
        }, $pools->pools);
    }

    /**
     * @throws ExceptionInterface
     */
    public function getConnectionPoolByName(string $clusterId, string $name): DatabasePoolEntity
    {
        $pool = $this->get(\sprintf('databases/%s/pools/%s', $clusterId, $name));

        return new DatabasePoolEntity($pool->pool);
    }

    /**
     * @throws ExceptionInterface
     */
    public function createConnectionPool(string $clusterId, string $name, string $mode, int $size, string $db, string $user): DatabasePoolEntity
    {
        $pool = $this->post(\sprintf('databases/%s/pools', $clusterId), [
            'name' => $name,
            'mode' => $mode,
            'size' => $size,
            'db' => $db,
            'user' => $user,
        ]);

        return new DatabasePoolEntity($pool->pool);
    }

    /**
     * @throws ExceptionInterface
     */
    public function removeConnectionPool(string $clusterId, string $name): void
    {
        $this->delete(\sprintf('databases/%s/pools/%s', $clusterId, $name));
    }

    /**
     * @throws ExceptionInterface
     */
    public function getEvictionPolicy(string $clusterId): object
    {
        $modes = $this->get(\sprintf('databases/%s/eviction_policy', $clusterId));

        return (object) ['evictionPolicy' => $modes->eviction_policy];
    }

    /**
     * @throws ExceptionInterface
     */
    public function updateEvictionPolicy(string $clusterId, string $evictionPolicy): void
    {
        $this->put(\sprintf('databases/%s/eviction_policy', $clusterId), [
            'eviction_policy' => $evictionPolicy,
        ]);
    }

    /**
     * @throws ExceptionInterface
     */
    public function getSqlMode(string $clusterId): object
    {
        $mode = $this->get(\sprintf('databases/%s/sql_mode', $clusterId));

        return (object) ['sqlMode' => $mode->sql_mode];
    }

    /**
     * @throws ExceptionInterface
     */
    public function updateSqlModes(string $clusterId, string $sqlMode): void
    {
        $this->put(\sprintf('databases/%s/sql_mode', $clusterId), [
            'sql_mode' => $sqlMode,
        ]);
    }
}
