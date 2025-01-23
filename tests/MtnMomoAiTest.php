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

    protected function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('mtn-momo-ai.subscription_key', 'your_actual_subscription_key_here');
        $this->app['config']->set('mtn-momo-ai.base_url', 'https://sandbox.momodeveloper.mtn.com');
        $this->app['config']->set('mtn-momo-ai.environment', 'sandbox');
        $this->app['config']->set('mtn-momo-ai.version', 'v1_0');
    }


    public function testRequestToPay()
    {
        $mockCollections = Mockery::mock(Collections::class);
        $mockCollections->shouldReceive('requestToPay')->once()->andReturn(['status' => 'SUCCESS']);
        $this->app->instance(Collections::class, $mockCollections);

        $result = MtnMomoAi::requestToPay('100', 'EUR', 'ext123', 'party123', 'Payment', 'Note');
        $this->assertEquals(['status' => 'SUCCESS'], $result);
    }

    public function testTransfer()
    {
        $mockDisbursements = Mockery::mock(Disbursements::class);
        $mockDisbursements->shouldReceive('transfer')->once()->andReturn(['status' => 'SUCCESS']);

        $this->app->instance(Disbursements::class, $mockDisbursements);

        $result = MtnMomoAi::transfer('100', 'EUR', 'ext123', 'party123', 'Payment', 'Note');
        $this->assertEquals(['status' => 'SUCCESS'], $result);
    }

    public function testRemit()
    {
        $mockRemittances = Mockery::mock(Remittances::class);
        $mockRemittances->shouldReceive('transfer')->once()->andReturn(['status' => 'SUCCESS']);

        $this->app->instance(Remittances::class, $mockRemittances);

        $result = MtnMomoAi::remit('100', 'EUR', 'ext123', 'party123', 'Payment', 'Note');
        $this->assertEquals(['status' => 'SUCCESS'], $result);
    }

    public function testAnalyzeTransaction()
    {
        $mockCollections = Mockery::mock(Collections::class);
        $mockCollections->shouldReceive('getTransactionStatus')->andReturn(['status' => 'SUCCESS']);
        $this->app->instance(Collections::class, $mockCollections);

        $mockLLM = Mockery::mock('AlvinCoded\MtnMomoAi\AI\Interfaces\LLMInterface');
        $mockLLM->shouldReceive('analyze')->once()->andReturn('Transaction analysis result');

        $mockFactory = Mockery::mock(LLMFactory::class, [['chatgpt' => [], 'claude' => [], 'gemini' => [], 'deepseek' => []]])->makePartial();
        $mockFactory->shouldReceive('create')->once()->andReturn($mockLLM);

        $this->app->instance(LLMFactory::class, $mockFactory);

        $result = MtnMomoAi::analyzeTransaction('transaction123');
        $this->assertEquals('Transaction analysis result', $result);
    }


    public function testDetectFraud()
    {
        $mockLLM = Mockery::mock('AlvinCoded\MtnMomoAi\AI\Interfaces\LLMInterface');
        $mockLLM->shouldReceive('detectFraud')->once()->andReturn(['fraud_score' => 0.2]);

        $mockFactory = Mockery::mock(LLMFactory::class);
        $mockFactory->shouldReceive('create')->once()->andReturn($mockLLM);

        $this->app->instance(LLMFactory::class, $mockFactory);

        $result = MtnMomoAi::detectFraud(['transaction_data']);
        $this->assertEquals(['fraud_score' => 0.2], $result);
    }

    public function testScheduleDisbursement()
    {
        $mockLLM = Mockery::mock('AlvinCoded\MtnMomoAi\AI\Interfaces\LLMInterface');
        $mockLLM->shouldReceive('suggestDisbursementTime')->once()->andReturn('2025-01-24 10:00:00');

        $mockFactory = Mockery::mock(LLMFactory::class);
        $mockFactory->shouldReceive('create')->once()->andReturn($mockLLM);

        $mockDisbursements = Mockery::mock(Disbursements::class);
        $mockDisbursements->shouldReceive('transfer')->once()->andReturn(['status' => 'PENDING']);

        $this->app->instance(LLMFactory::class, $mockFactory);
        $this->app->instance(Disbursements::class, $mockDisbursements);

        $result = MtnMomoAi::scheduleDisbursement(100, 'recipient123', 'EUR');
        $this->assertEquals(['status' => 'PENDING'], $result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }
}
