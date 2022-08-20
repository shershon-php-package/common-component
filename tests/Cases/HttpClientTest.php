<?php


namespace Shershon\CommonTest\Cases;

use Shershon\Common\Client\Traits\ClientTrait;

class HttpClientTest extends AbstractTestCase
{
    public function testJson()
    {
        $factory = $this->factory();
        $client  = $this->getMockForTrait(ClientTrait::class, [$factory]);
        $resp    = $client->get("http://www.baidu.com");
        $this->assertStringContainsString("html", $resp);
    }
}