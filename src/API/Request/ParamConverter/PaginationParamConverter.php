<?php
declare(strict_types=1);

namespace App\API\Request\ParamConverter;

use App\Exception\ValidationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PaginationParamConverter implements ParamConverterInterface
{
    private const DEFAULT_PAGE = 1;

    private const DEFAULT_LIMIT = 10;

    public const NAME = 'app.pagination_query_converter';
    
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $page = $request->query->getInt('page', self::DEFAULT_PAGE);
        $limit = $request->query->getInt('limit', self::DEFAULT_LIMIT);
        
        $paginationParameters = new PaginationParameters($page, $limit);
        
        $this->validate($paginationParameters);

        $request->attributes->set($configuration->getName(), $paginationParameters);

        return true;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return $configuration->getClass() === PaginationParameters::class
            && $configuration->getConverter() === self::NAME;
    }

    private function validate(PaginationParameters $paginationParameters): void
    {
        /** @var ConstraintViolationList $errors */
        $errors = $this->validator->validate($paginationParameters);

        if ($errors->count() > 0) {
            $details = [];

            /** @var ConstraintViolationInterface $error */
            foreach ($errors as $error) {
                $details[$error->getPropertyPath()] = $error->getMessage();
            }

            $exception = new ValidationException('Invalid query string params');
            $exception->setValidationErrors($details);

            throw $exception;
        }
    }
}
