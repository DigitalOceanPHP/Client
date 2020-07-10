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

use DigitalOceanV2\Entity\Certificate as CertificateEntity;
use DigitalOceanV2\Exception\ExceptionInterface;
use DigitalOceanV2\HttpClient\Util\JsonObject;

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

        return array_map(function ($certificates) {
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
    public function getById($id)
    {
        $certificate = $this->get(sprintf('certificates/%s', $id));

        return new CertificateEntity($certificate->certificate);
    }

    /**
     * @param string $name
     * @param string $privateKey
     * @param string $leafCertificate
     * @param string $certificateChain
     *
     * @throws ExceptionInterface
     *
     * @return CertificateEntity
     */
    public function create($name, $privateKey, $leafCertificate, $certificateChain)
    {
        $certificate = $this->post('certificates', [
            'name' => $name,
            'private_key' => $privateKey,
            'leaf_certificate' => $leafCertificate,
            'certificate_chain' => $certificateChain,
        ]);

        return new CertificateEntity($certificate->certificate);
    }

    /**
     * @param string $id
     *
     * @throws ExceptionInterface
     *
     * @return void
     */
    public function remove($id)
    {
        $this->delete(sprintf('certificates/%s', $id));
    }
}
