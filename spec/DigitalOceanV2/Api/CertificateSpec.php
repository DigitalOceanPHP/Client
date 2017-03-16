<?php

namespace spec\DigitalOceanV2\Api;

use DigitalOceanV2\Adapter\AdapterInterface;

class CertificateSpec extends \PhpSpec\ObjectBehavior
{
    function let(AdapterInterface $adapter)
    {
        $this->beConstructedWith($adapter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Api\Certificate');
    }

    function it_returns_an_array_of_size_entity($adapter)
    {
        $total = 3;
        $adapter
            ->get('https://api.digitalocean.com/v2/certificates')
            ->willReturn(json_encode([
                'certificates' => [
                    [], [], [],
                ],
                'links' => [],
                'meta' => [
                    'total' => $total,
                ],
            ]));

        $certificates = $this->getAll();
        $certificates->shouldBeArray();
        $certificates->shouldHaveCount($total);

        foreach ($certificates as $certificate) {
            $certificate->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Certificate');
        }

        $meta = $this->getMeta();
        $meta->shouldHaveType('DigitalOceanV2\Entity\Meta');
        $meta->total->shouldBe($total);
    }

    function it_returns_a_certificate_entity_by_its_id($adapter)
    {
        $adapter
            ->get('https://api.digitalocean.com/v2/certificates/123')
            ->willReturn(json_encode([
                'certificate' => [
                    'id' => '123',
                    'name' => 'web-cert-01',
                    'not_after' => '2017-02-22T00:23:00Z',
                    'sha1_fingerprint' => 'dfcc9f57d86bf58e321c2c6c31c7a971be244ac7',
                    'created_at' => '2017-02-08T16:02:37Z',
                ],
            ]));

        $this->getById(123)
             ->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Certificate');
    }

    function it_returns_the_created_certificate($adapter)
    {
        $data = [
            'name' => 'web-cert-01',
            'private_key' => '-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDBIZM....',
            'leaf_certificate' => '-----BEGIN CERTIFICATE-----\nMIIFFjCCA/6gAwIBAgISA0AznUJmXhu08/89ZuSPC/....',
            'certificate_chain' => '-----BEGIN CERTIFICATE-----\nMIIFFjCCA/6gAwIBAgISA0AznUJmXhu08/89ZuSPC/kRMA0GC....',
        ];

        $adapter
            ->post('https://api.digitalocean.com/v2/certificates', $data)
            ->willReturn(json_encode([
                'certificate' => [
                    'id' => '123',
                    'name' => 'web-cert-01',
                    'not_after' => '2017-02-22T00:23:00Z',
                    'sha1_fingerprint' => 'dfcc9f57d86bf58e321c2c6c31c7a971be244ac7',
                    'created_at' => '2017-02-08T16:02:37Z',
                ],
            ]));

        $this
            ->create($data['name'], $data['private_key'], $data['leaf_certificate'], $data['certificate_chain'])
            ->shouldReturnAnInstanceOf('DigitalOceanV2\Entity\Certificate');
    }

    function it_deletes_the_certificate_and_returns_nothing($adapter)
    {
        $adapter
            ->delete('https://api.digitalocean.com/v2/certificates/123')
            ->shouldBeCalled();

        $this->delete(123);
    }
}
