<?php
declare(strict_types=1);

namespace App\Tests\Unit\API\Request;

use App\API\Request\ParamConverter\PaginationParamConverter;
use App\API\Request\ParamConverter\PaginationParameters;
use App\Exception\ValidationException;
use PHPUnit\Framework\TestCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;

class PaginationParamConverterTest extends TestCase
{
    private PaginationParamConverter $paginationParamConverter;

    protected function setUp(): void
    {
        $this->paginationParamConverter = new PaginationParamConverter(
            Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator()
        );
    }

    /**
     * @dataProvider getConfigurationSupportProvider
     */
    public function testSupport(ParamConverter $configuration, bool $expectedResult): void
    {
        $result = $this->paginationParamConverter->supports($configuration);

        $this->assertEquals($expectedResult, $result);
    }

    public function getConfigurationSupportProvider(): array
    {
        return [
            [
                $this->createConfiguration(
                    PaginationParameters::class,
                    PaginationParamConverter::NAME
                ),
                true
            ],
            [
                $this->createConfiguration(PaginationParameters::class, 'wrong_name'),
                false
            ],
            [
                $this->createConfiguration('wrong_class', PaginationParamConverter::NAME),
                false
            ],
            [
                $this->createConfiguration('wrong_class', 'wrong_name'),
                false
            ]
        ];
    }

    /**
     * @dataProvider getTestApplyExceptionProvider
     */
    public function testApplyException(Request $request): void
    {
        $configuration = $this->createConfiguration(
            PaginationParameters::class,
            PaginationParamConverter::NAME
        );

        $this->expectException(ValidationException::class);
        $this->paginationParamConverter->apply($request, $configuration);
    }

    public function getTestApplyExceptionProvider(): array
    {
        return [
            [
                new Request(['page' => 'wrong_value', 'limit' => 'wrong_value'])
            ],
            [
                new Request(['page' => 1, 'limit' => 'wrong_value'])
            ],
            [
                new Request(['page' => 'wrong_value', 'limit' => 10])
            ]
        ];
    }

    public function testApplySuccessful(): void
    {
        $configuration = $this->createConfiguration(
            PaginationParameters::class,
            PaginationParamConverter::NAME
        );

        $configuration->setName($attributeName ='paginationParameters');

        $request = new Request(['page' => $page =  2, 'limit' => $limit = 20]);

        $this->paginationParamConverter->apply($request, $configuration);

        /** @var PaginationParameters $injectedPaginationParams */
        $injectedPaginationParams = $request->attributes->get($attributeName);

        $this->assertNotNull($injectedPaginationParams);
        $this->assertEquals($page, $injectedPaginationParams->getPage());
        $this->assertEquals($limit, $injectedPaginationParams->getLimit());
    }

    private function createConfiguration(string $class, string $converterName): ParamConverter
    {
        $configuration = new ParamConverter();
        $configuration->setClass($class);
        $configuration->setConverter($converterName);

        return $configuration;
    }


}