<?php

declare(strict_types=1);

/*
 * This file is part of the DigitalOcean API library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\Api;

use DigitalOceanV2\Entity\Certificate as CertificateEntity;
use DigitalOceanV2\Exception\ExceptionInterface;

/**
 * @author Jacob Holmes <jwh315@cox.net>
 */
class Certificate extends AbstractApi
{
    /**
     * @throws ExceptionInterface
     *
     * @return CertificateEntity[]
     */
    public function getAll()
    {
        $certificates = $this->get('certificates');

        return \array_map(function ($certificates) {
            return new CertificateEntity($certificates);
        }, $certificates->certificates);
    }

    /**
     * @param string $id
     *
     * @throws ExceptionInterface
     *
     * @return CertificateEntity
     */
    public function getById(string $id)
    {
        $certificate = $this->get(\sprintf('certificates/%s', $id));

        return new CertificateEntity($certificate->certificate);
    }

    /**
     * @param string      $name
     * @param string      $privateKey
     * @param string      $leafCertificate
     * @param string|null $certificateChain
     *
     * @throws ExceptionInterface
     *
     * @return CertificateEntity
     */
    public function create(string $name, string $privateKey, string $leafCertificate, ?string $certificateChain = null)
    {
        $params = [
            'type' => 'custom',
            'name' => $name,
            'private_key' => $privateKey,
            'leaf_certificate' => $leafCertificate,
        ];

        if (null !== $certificateChain) {
            $params['certificate_chain'] = $certificateChain;
        }

        $certificate = $this->post('certificates', $params);

        return new CertificateEntity($certificate->certificate);
    }

    /**
     * @param string   $name
     * @param string[] $dnsNames
     *
     * @throws ExceptionInterface
     *
     * @return CertificateEntity
     */
    public function createLetsEncrypt(string $name, array $dnsNames)
    {
        $params = [
            'type' => 'lets_encrypt',
            'name' => $name,
            'dns_names' => $dnsNames,
        ];

        $certificate = $this->post('certificates', $params);

        return new CertificateEntity($certificate->certificate);
    }

    /**
     * @param string $id
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function remove(string $id): void
    {
        $this->delete(\sprintf('certificates/%s', $id));
    }
}
