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

use DigitalOceanV2\Entity\MonitoringAlert as MonitoringAlertEntity;
use DigitalOceanV2\Entity\MonitoringMetric as MonitoringMetricEntity;
use DigitalOceanV2\Exception\ExceptionInterface;

/**
 * @author Michael Shihjay Chen <shihjay2@gmail.com>
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class Monitoring extends AbstractApi
{
    /**
     * @throws ExceptionInterface
     *
     * @return MonitoringAlertEntity[]
     */
    public function getAlerts()
    {
        $alerts = $this->get('monitoring/alerts');

        return \array_map(function ($alert) {
            return new MonitoringAlertEntity($alert);
        }, $alerts->policies);
    }

    /**
     * @param string $alertUUID
     *
     * @throws ExceptionInterface
     *
     * @return MonitoringAlertEntity
     */
    public function getAlert(string $alertUUID)
    {
        $alert = $this->get(\sprintf('monitoring/alerts/%s', $alertUUID));

        return new MonitoringAlertEntity($alert);
    }

    /**
     * @param string $hostId
     * @param string $start
     * @param string $end
     * @param string $direction
     * @param string $interface
     *
     * @throws ExceptionInterface
     *
     * @return MonitoringMetricEntity
     */
    public function getDropletBandwidth(string $hostId, string $start, string $end, string $direction = 'inbound', string $interface = 'public')
    {
        $metric = $this->get(
            'monitoring/metrics/droplet/bandwidth',
            [
                'host_id' => $hostId,
                'start' => $start,
                'end' => $end,
                'direction' => $direction,
                'interface' => $interface,
            ]
        );

        return new MonitoringMetricEntity($metric);
    }

    /**
     * @param string $hostId
     * @param string $start
     * @param string $end
     *
     * @throws ExceptionInterface
     *
     * @return MonitoringMetricEntity
     */
    public function getDropletCpu(string $hostId, string $start, string $end)
    {
        $metric = $this->get(
            'monitoring/metrics/droplet/cpu',
            [
                'host_id' => $hostId,
                'start' => $start,
                'end' => $end,
            ]
        );

        return new MonitoringMetricEntity($metric);
    }

    /**
     * @param string $hostId
     * @param string $start
     * @param string $end
     *
     * @throws ExceptionInterface
     *
     * @return MonitoringMetricEntity
     */
    public function getDropletTotalMemory(string $hostId, string $start, string $end)
    {
        $metric = $this->get(
            'monitoring/metrics/droplet/memory_total',
            [
                'host_id' => $hostId,
                'start' => $start,
                'end' => $end,
            ]
        );

        return new MonitoringMetricEntity($metric);
    }

    /**
     * @param string $hostId
     * @param string $start
     * @param string $end
     *
     * @throws ExceptionInterface
     *
     * @return MonitoringMetricEntity
     */
    public function getDropletCachedMemory(string $hostId, string $start, string $end)
    {
        $metric = $this->get(
            'monitoring/metrics/droplet/memory_cached',
            [
                'host_id' => $hostId,
                'start' => $start,
                'end' => $end,
            ]
        );

        return new MonitoringMetricEntity($metric);
    }

    /**
     * @param string $hostId
     * @param string $start
     * @param string $end
     *
     * @throws ExceptionInterface
     *
     * @return MonitoringMetricEntity
     */
    public function getDropletFreeMemory(string $hostId, string $start, string $end)
    {
        $metric = $this->get(
            'monitoring/metrics/droplet/memory_free',
            [
                'host_id' => $hostId,
                'start' => $start,
                'end' => $end,
            ]
        );

        return new MonitoringMetricEntity($metric);
    }

    /**
     * @param string $hostId
     * @param string $start
     * @param string $end
     *
     * @throws ExceptionInterface
     *
     * @return MonitoringMetricEntity
     */
    public function getDropletAvailableMemory(string $hostId, string $start, string $end)
    {
        $metric = $this->get(
            'monitoring/metrics/droplet/memory_available',
            [
                'host_id' => $hostId,
                'start' => $start,
                'end' => $end,
            ]
        );

        return new MonitoringMetricEntity($metric);
    }

    /**
     * @param string $hostId
     * @param string $start
     * @param string $end
     *
     * @throws ExceptionInterface
     *
     * @return MonitoringMetricEntity
     */
    public function getDropletFilesystemFree(string $hostId, string $start, string $end)
    {
        $metric = $this->get(
            'monitoring/metrics/droplet/filesystem_free',
            [
                'host_id' => $hostId,
                'start' => $start,
                'end' => $end,
            ]
        );

        return new MonitoringMetricEntity($metric);
    }

    /**
     * @param string $hostId
     * @param string $start
     * @param string $end
     *
     * @throws ExceptionInterface
     *
     * @return MonitoringMetricEntity
     */
    public function getDropletFilesystemSize(string $hostId, string $start, string $end)
    {
        $metric = $this->get(
            'monitoring/metrics/droplet/filesystem_size',
            [
                'host_id' => $hostId,
                'start' => $start,
                'end' => $end,
            ]
        );

        return new MonitoringMetricEntity($metric);
    }

    /**
     * @param string $hostId
     * @param string $start
     * @param string $end
     *
     * @throws ExceptionInterface
     *
     * @return MonitoringMetricEntity
     */
    public function getDropletLoad1(string $hostId, string $start, string $end)
    {
        $metric = $this->get(
            'monitoring/metrics/droplet/load_1',
            [
                'host_id' => $hostId,
                'start' => $start,
                'end' => $end,
            ]
        );

        return new MonitoringMetricEntity($metric);
    }

    /**
     * @param string $hostId
     * @param string $start
     * @param string $end
     *
     * @throws ExceptionInterface
     *
     * @return MonitoringMetricEntity
     */
    public function getDropletLoad5(string $hostId, string $start, string $end)
    {
        $metric = $this->get(
            'monitoring/metrics/droplet/load_5',
            [
                'host_id' => $hostId,
                'start' => $start,
                'end' => $end,
            ]
        );

        return new MonitoringMetricEntity($metric);
    }

    /**
     * @param string $hostId
     * @param string $start
     * @param string $end
     *
     * @throws ExceptionInterface
     *
     * @return MonitoringMetricEntity
     */
    public function getDropletLoad15(string $hostId, string $start, string $end)
    {
        $metric = $this->get(
            'monitoring/metrics/droplet/load_15',
            [
                'host_id' => $hostId,
                'start' => $start,
                'end' => $end,
            ]
        );

        return new MonitoringMetricEntity($metric);
    }
}
