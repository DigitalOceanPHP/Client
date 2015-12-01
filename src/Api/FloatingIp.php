<?php
/*
* This file is part of the DigitalOceanV2 library.
*
* (c) Antoine Corcy <contact@sbin.dk>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace DigitalOceanV2\Api;

use DigitalOceanV2\Entity\Action as ActionEntity;
use DigitalOceanV2\Entity\FloatingIp as FloatingIpEntity;

/**
 * @author Cagatay Gürtürk <info@cagataygurturk.com>
 */
class FloatingIp extends AbstractApi
{
    /**
     * @return FloatingIpEntity[]
     */
    public function getAll()
    {
        $floatingIps = $this->adapter->get(sprintf('%s/floating_ips?per_page=%d', self::ENDPOINT, PHP_INT_MAX));
        $floatingIps = json_decode($floatingIps);

        $this->extractMeta($floatingIps);

        return array_map(
            function ($droplet) {
                return new FloatingIpEntity($droplet);
            }, $floatingIps->floating_ips
        );
    }

    /**
     * @param string $ip
     *
     * @throws \RuntimeException
     *
     * @return FloatingIpEntity
     */
    public function getByIp($ip)
    {
        $floatingIp = $this->adapter->get(sprintf('%s/floating_ips/%s', self::ENDPOINT, $ip));
        $floatingIp = json_decode($floatingIp);

        return new FloatingIpEntity($floatingIp->floating_ip);
    }

    /**
     * @param int    $droplet_id Droplet id
     * @param string $region_id  Region id
     *
     * @throws \RuntimeException
     *
     * @return FloatingIpEntity
     */
    public function create($droplet_id = null, $region_id = null)
    {
        if (null == $droplet_id && null == $region_id) {
            throw new \RuntimeException("Region id or Droplet id must be provided");
        }

        if (null != $droplet_id && null != $region_id) {
            throw new \RuntimeException("Provide region id or droplet id, not both");
        }


        $headers = array('Content-Type: application/json');

        $data = array();

        if ($droplet_id) {
            $data['droplet_id'] = $droplet_id;
        }

        if ($region_id) {
            $data['region'] = $region_id;
        }

        $content = json_encode($data);

        $floatingIp = $this->adapter->post(sprintf('%s/floating_ips', self::ENDPOINT), $headers, $content);
        $floatingIp = json_decode($floatingIp);

        return new FloatingIpEntity($floatingIp->floating_ip);
    }


    /**
     * @param int $ip
     *
     * @throws \RuntimeException
     */
    public function delete($ip)
    {
        $headers = array('Content-Type: application/x-www-form-urlencoded');
        $this->adapter->delete(sprintf('%s/floating_ips/%s', self::ENDPOINT, $ip), $headers);
    }

    /**
     * @param string $ip         IP
     * @param int    $droplet_id Droplet id
     *
     * @throws \RuntimeException
     *
     * @return FloatingIpEntity
     */
    public function assignToDroplet($ip, $droplet_id)
    {
        $headers = array('Content-Type: application/json');
        $data    = array(
            'type'       => 'assign',
            'droplet_id' => $droplet_id
        );

        $content = json_encode($data);

        $action = $this->adapter->post(sprintf('%s/floating_ips/%s/actions', self::ENDPOINT, $ip), $headers, $content);
        $action = json_decode($action);

        return new ActionEntity($action->action);
    }

    /**
     * @param string $ip         IP
     *
     * @throws \RuntimeException
     *
     * @return FloatingIpEntity
     */
    public function unAssignDroplet($ip)
    {
        $headers = array('Content-Type: application/json');
        $data    = array(
            'type'       => 'unassign'
        );

        $content = json_encode($data);

        $action = $this->adapter->post(sprintf('%s/floating_ips/%s/actions', self::ENDPOINT, $ip), $headers, $content);
        $action = json_decode($action);
    print_r($action);
        return new ActionEntity($action->action);
    }

}