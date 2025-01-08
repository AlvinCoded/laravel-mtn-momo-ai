<?php

namespace AlvinCoded\MtnMomoAi\Tests;

use Orchestra\Testbench\TestCase;
use AlvinCoded\MtnMomoAi\MtnMomoAiServiceProvider;
use AlvinCoded\MtnMomoAi\Facades\MtnMomoAi;
use AlvinCoded\MtnMomoAi\AI\LLMFactory;
use AlvinCoded\MtnMomoAi\Endpoints\Collections;
use AlvinCoded\MtnMomoAi\Endpoints\Disbursements;
use AlvinCoded\MtnMomoAi\Endpoints\Remittances;
use Mockery;

class MtnMomoAiTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [MtnMomoAiServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'MtnMomoAi' => MtnMomoAi::class,
        ];
    }

    public function testAnalyzeTransaction()
    {
        $mockLLM = Mockery::mock('AlvinCoded\MtnMomoAi\AI\Interfaces\LLMInterface');
        $mockLLM->shouldReceive('analyze')->once()->andReturn('Transaction analysis result');

        $mockFactory = Mockery::mock(LLMFactory::class);
        $mockFactory->shouldReceive('create')->once()->andReturn($mockLLM);

        $this->app->instance(LLMFactory::class, $mockFactory);

        $result = MtnMomoAi::analyzeTransaction(['transaction_data']);
        $this->assertEquals('Transaction analysis result', $result);
    }

    public function testRequestToPay()
    {
        $mockCollections = Mockery::mock(Collections::class);
        $mockCollections->shouldReceive('requestToPay')->once()->andReturn(['status' => 'success']);

        $this->app->instance(Collections::class, $mockCollections);

        $result = MtnMomoAi::requestToPay(100, 'EUR', 'ext123', 'party123', 'Payment', 'Note');
        $this->assertEquals(['status' => 'success'], $result);
    }

    public function testTransfer()
    {
        $mockDisbursements = Mockery::mock(Disbursements::class);
        $mockDisbursements->shouldReceive('transfer')->once()->andReturn(['status' => 'success']);

        $this->app->instance(Disbursements::class, $mockDisbursements);

        $result = MtnMomoAi::transfer(100, 'EUR', 'ext123', 'party123', 'Payment', 'Note');
        $this->assertEquals(['status' => 'success'], $result);
    }

    public function testRemit()
    {
        $mockRemittances = Mockery::mock(Remittances::class);
        $mockRemittances->shouldReceive('transfer')->once()->andReturn(['status' => 'success']);

        $this->app->instance(Remittances::class, $mockRemittances);

        $result = MtnMomoAi::remit(100, 'EUR', 'ext123', 'party123', 'Payment', 'Note');
        $this->assertEquals(['status' => 'success'], $result);
    }

    // More tests coming soon...

    protected function tearDown(): void
    {
        Mockery::close();
    }
}
