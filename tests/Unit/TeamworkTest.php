<?php

namespace Shield\Teamwork\Test\Unit;

use PHPUnit\Framework\Assert;
use Shield\Shield\Contracts\Service;
use Shield\Testing\TestCase;
use Shield\Teamwork\Teamwork;

/**
 * Class ServiceTest
 *
 * @package \Shield\Teamwork\Test\Unit
 */
class TeamworkTest extends TestCase
{
    /**
     * @var \Shield\Teamwork\Teamwork
     */
    protected $service;

    protected function setUp()
    {
        parent::setUp();

        $this->service = new Teamwork;
    }

    /** @test */
    public function it_is_a_service()
    {
        Assert::assertInstanceOf(Service::class, new Teamwork);
    }

    /** @test */
    public function it_can_verify_a_valid_request()
    {
        $token = 'teikoK33Y$$';
        $this->app['config']['shield.services.teamwork.options.token'] = $token;
        $content = 'XXX Code Only';
        $request = $this->request($content);
        $headers = [
            'X-Projects-Signature' => hash_hmac('sha256', $content, $token),
        ];
        $request->headers->add($headers);
        Assert::assertTrue($this->service->verify($request, collect($this->app['config']['shield.services.teamwork.options'])));

    }

    /** @test */
    public function it_will_not_verify_a_bad_request()
    {
        $content = 'XXX Code Only';
        $request = $this->request($content);
        $headers = [
            'X-Projects-Signature' => hash_hmac('sha256', $content, 'bad request'),
        ];
        $request->headers->add($headers);
        Assert::assertFalse($this->service->verify($request, collect($this->app['config']['shield.services.teamwork.options'])));

    }

    /** @test */
    public function it_has_correct_headers_required()
    {
        Assert::assertArraySubset([], $this->service->headers());
        Assert::assertArraySubset(['X-Projects-Signature'], $this->service->headers());

    }
}
