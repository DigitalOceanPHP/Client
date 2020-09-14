<?php

declare(strict_types=1);

/*
 * This file is part of the DigitalOceanV2 library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\Api;

use DigitalOceanV2\Entity\DatabaseCluster as DatabaseClusterEntity;
use DigitalOceanV2\Entity\DatabaseRule as DatabaseRuleEntity;
use DigitalOceanV2\Entity\DatabaseBackup as DatabaseBackupEntity;
use DigitalOceanV2\Entity\DatabaseReplica as DatabaseReplicaEntity;
use DigitalOceanV2\Entity\DatabaseUser as DatabaseUserEntity;
use DigitalOceanV2\Entity\Database as DatabaseEntity;
use DigitalOceanV2\Entity\DatabasePool as DatabasePoolEntity;
use DigitalOceanV2\Exception\ExceptionInterface;

/**
 * @author Filippo Fortino <filippofortino@gmail.com>
 */
class Database extends AbstractApi
{
    /**
     * @param string|null $tag
     *
     * @throws ExceptionInterface
     *
     * @return DatabaseClusterEntity[]
     */
    public function getAllClusters(?string $tag = null)
    {
        $databases = $this->get('databases', null === $tag ? [] : ['tag_name' => $tag]);

        return \array_map(function ($database) {
            return new DatabaseClusterEntity($database);
        }, $databases->databases);
    }

    /**
     * @param int $id
     *
     * @throws ExceptionInterface
     *
     * @return DatabaseClusterEntity
     */
    public function getClusterById(string $id)
    {
        $database = $this->get(\sprintf('databases/%s', $id));

        return new DatabaseClusterEntity($database->database);
    }

    /**
     * @param string      $name
     * @param string      $engine
     * @param string      $size
     * @param string      $region
     * @param int         $numNodes
     * @param string|null $version
     * @param array       $tags
     * @param string|null $privateNetworkUuid
     *
     * @throws ExceptionInterface
     *
     * @return DatabaseClusterEntity
     */
    public function createCluster(string $name, string $engine, string $size, string $region, int $numNodes, string $version = null, array $tags = [], string $privateNetworkUuid = null)
    {
        $database = $this->post('databases', [
            'name' => $name,
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
     * @param string $id
     * @param string $size
     * @param int    $numNodes
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function resizeCluster(string $id, string $size, int $numNodes)
    {
        $this->put(\sprintf('databases/%s/resize', $id), [
            'size' => $size,
            'num_nodes' => $numNodes
        ]);
    }

    /**
     * @param string $id
     * @param string $region
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function migrateCluster(string $id, string $region)
    {
        $this->put(\sprintf('databases/%s/migrate', $id), [
            'region' => $region
        ]);
    }

    /**
     * @param string $clusterId
     * @param array $rules
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function updateFirewallRules(string $clusterId, array $rules)
    {
        $this->put(\sprintf('databases/%s/firewall', $clusterId), [
            'rules' => $rules
        ]);
    }

    /**
     * @param string $clusterId
     *
     * @throws ExceptionInterface
     *
     * @return DatabaseRuleEntity[]
     */
    public function getFirewallRules(string $clusterId)
    {
        $rules = $this->get(\sprintf('databases/%s/firewall', $clusterId));

        return \array_map(function ($rule) {
            return new DatabaseRuleEntity($rule);
        }, $rules->rules);
    }

    /**
     * @param string $clusterId
     * @param string $day
     * @param string $hour
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function updateMaintenanceWindow(string $clusterId, string $day, string $hour)
    {
        $this->put(\sprintf('databases/%s/maintenance', $clusterId), [
            'day' => $day,
            'hour' => $hour
        ]);
    }

    /**
     * @param string $clusterId
     *
     * @throws ExceptionInterface
     *
     * @return DatabaseBackupEntity[]
     */
    public function getBackups(string $clusterId)
    {
        $backups = $this->get(\sprintf('databases/%s/backups', $clusterId));

        return \array_map(function ($backup) {
            return new DatabaseBackupEntity($backup);
        }, $backups->backups);
    }

    /**
     * @param string      $name
     * @param array       $backupRestore
     * @param string      $engine
     * @param string      $size
     * @param string      $region
     * @param int         $numNodes
     * @param string|null $version
     * @param array       $tags
     * @param string|null $privateNetworkUuid
     *
     * @throws ExceptionInterface
     *
     * @return DatabaseClusterEntity
     */
    public function createClusterFromBackup(string $name, array $backupRestore, string $engine, string $size, string $region, int $numNodes, string $version = null, array $tags = [], string $privateNetworkUuid = null)
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
     * @param string $id
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function removeCluster(string $id)
    {
        $this->delete(\sprintf('databases/%s', $id));
    }

    /**
     * @param string      $clusterId
     * @param string      $name
     * @param string      $size
     * @param string|null $region
     * @param array       $tags
     * @param string|null $privateNetworkUuid
     *
     * @throws ExceptionInterface
     *
     * @return DatabaseReplicaEntity
     */
    public function createClusterReplica(string $clusterId, string $name, string $size, string $region = null, array $tags = [], string $privateNetworkUuid = null)
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
     * @param string $clusterId
     * @param string $name
     *
     * @throws ExceptionInterface
     *
     * @return DatabaseReplicaEntity
     */
    public function getClusterReplicaByName(string $clusterId, string $name)
    {
        $replica = $this->get(\sprintf('databases/%s/replicas/%s', $clusterId, $name));

        return new DatabaseReplicaEntity($replica->replica);
    }

    /**
     * @param string $clusterId
     * @param string $name
     *
     * @throws ExceptionInterface
     *
     * @return DatabaseReplicaEntity[]
     */
    public function getAllClusterReplicas(string $clusterId)
    {
        $replicas = $this->get(\sprintf('databases/%s/replicas', $clusterId));

        return \array_map(function ($replica) {
            return new DatabaseReplicaEntity($replica);
        }, $replicas->replicas);
    }

    /**
     * @param string $clusterId
     * @param string $name
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function removeClusterReplica(string $clusterId, string $name)
    {
        $this->delete(\sprintf('databases/%s/replicas/%s', $clusterId, $name));
    }

    /**
     * @param string      $clusterId
     * @param string      $name
     * @param string|null $authPlugin
     *
     * @throws ExceptionInterface
     *
     * @return DatabaseUserEntity
     */
    public function createUser(string $clusterId, string $name, string $authPlugin = null)
    {
        $user = $this->post(\sprintf('databases/%s/users', $clusterId), [
            'name' => $name,
            'mysql_settings' => [
                'auth_plugin' => $authPlugin
            ]
        ]);

        return new DatabaseUserEntity($user->user);
    }

    /**
     * @param string $clusterId
     * @param string $name
     *
     * @throws ExceptionInterface
     *
     * @return DatabaseUserEntity
     */
    public function getUserByName(string $clusterId, string $name)
    {
        $user = $this->get(\sprintf('databases/%s/users/%s', $clusterId, $name));

        return new DatabaseUserEntity($user->user);
    }

    /**
     * @param string $clusterId
     * @param string $username
     * @param string $authPlugin
     *
     * @throws ExceptionInterface
     *
     * @return DatabaseUserEntity
     */
    public function updateUserMysqlAuthMethod(string $clusterId, string $username, string $authPlugin)
    {
        $user = $this->post(\sprintf('databases/%s/users/%s/reset_auth', $clusterId, $username), [
            'mysql_settings' => [
                'auth_plugin' => $authPlugin
            ]
        ]);

        return new DatabaseUserEntity($user->user);
    }

    /**
     * @param string $clusterId
     * @param string $name
     *
     * @throws ExceptionInterface
     *
     * @return DatabaseUserEntity[]
     */
    public function getAllUsers(string $clusterId)
    {
        $users = $this->get(\sprintf('databases/%s/users', $clusterId));

        return \array_map(function ($user) {
            return new DatabaseUserEntity($user);
        }, $users->users);
    }

    /**
     * @param string $clusterId
     * @param string $name
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function removeUser(string $clusterId, string $name)
    {
        $this->delete(\sprintf('databases/%s/users/%s', $clusterId, $name));
    }

    /**
     * @param string $clusterId
     * @param string $name
     *
     * @throws ExceptionInterface
     *
     * @return DatabaseEntity
     */
    public function createDatabase(string $clusterId, string $name)
    {
        $database = $this->post(\sprintf('databases/%s/dbs', $clusterId), [
            'name' => $name
        ]);

        return new DatabaseEntity($database->db);
    }

    /**
     * @param string $clusterId
     * @param string $name
     *
     * @throws ExceptionInterface
     *
     * @return DatabaseEntity
     */
    public function getDatabaseByName(string $clusterId, string $name)
    {
        $database = $this->get(\sprintf('databases/%s/dbs/%s', $clusterId, $name));

        return new DatabaseEntity($database->db);
    }

    /**
     * @param string $clusterId
     *
     * @throws ExceptionInterface
     *
     * @return DatabaseUserEntity[]
     */
    public function getAllDatabases(string $clusterId)
    {
        $databases = $this->get(\sprintf('databases/%s/dbs', $clusterId));

        return \array_map(function ($database) {
            return new DatabaseEntity($database);
        }, $databases->dbs);
    }

    /**
     * @param string $clusterId
     * @param string $name
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function removeDatabase(string $clusterId, string $name)
    {
        $this->delete(\sprintf('databases/%s/dbs/%s', $clusterId, $name));
    }

    /**
     * @param string $clusterId
     * @param string $name
     * @param string $mode
     * @param int    $size
     * @param string $db
     * @param string $user
     *
     * @throws ExceptionInterface
     *
     * @return DatabasePoolEntity
     */
    public function createConnectionPool(string $clusterId, string $name, string $mode, int $size, string $db, string $user)
    {
        $pool = $this->post(sprintf('databases/%s/pools', $clusterId), [
            'name' => $name,
            'mode' => $mode,
            'size' => $size,
            'db' => $db,
            'user' => $user,
        ]);

        return new DatabasePoolEntity($pool->pool);
    }

    /**
     * @param string $clusterId
     *
     * @throws ExceptionInterface
     *
     * @return DatabasePoolEntity[]
     */
    public function getAllConnectionPools(string $clusterId)
    {
        $pools = $this->get(\sprintf('databases/%s/pools', $clusterId));

        return \array_map(function ($pool) {
            return new DatabasePoolEntity($pool);
        }, $pools->pools);
    }

    /**
     * @param string $clusterId
     * @param string $name
     *
     * @throws ExceptionInterface
     *
     * @return DatabasePoolEntity
     */
    public function getConnectionPoolByName(string $clusterId, string $name)
    {
        $pool = $this->get(\sprintf('databases/%s/pools/%s', $clusterId, $name));

        return new DatabasePoolEntity($pool->pool);
    }

    /**
     * @param string $clusterId
     * @param string $name
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function removeConnectionPool(string $clusterId, string $name)
    {
        $this->delete(\sprintf('databases/%s/pools/%s', $clusterId, $name));
    }

    /**
     * @param string $clusterId
     *
     * @throws ExceptionInterface
     *
     * @return object
     */
    public function getEvictionPolicy(string $clusterId)
    {
        $modes = $this->get(\sprintf('databases/%s/eviction_policy', $clusterId));

        return (object)['evictionPolicy' => $modes->eviction_policy];
    }

    /**
     * @param string $clusterId
     * @param string $evictionPolicy
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function updateEvictionPolicy(string $clusterId, $evictionPolicy)
    {
        $this->put(\sprintf('databases/%s/eviction_policy', $clusterId), [
            "eviction_policy" => $evictionPolicy
        ]);
    }
    
    /**
     * @param string $clusterId
     *
     * @throws ExceptionInterface
     *
     * @return object
     */
    public function getSqlModes(string $clusterId)
    {
        $modes = $this->get(\sprintf('databases/%s/sql_mode', $clusterId));

        return (object)['sqlMode' => $modes->sql_mode];
    }

    /**
     * @param string $clusterId
     * @param string $sqlMode
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function updateSqlModes(string $clusterId, $sqlMode)
    {
        $this->put(\sprintf('databases/%s/sql_mode', $clusterId), [
            "sql_mode" => $sqlMode
        ]);
    }
}
