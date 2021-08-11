<?php
declare(strict_types=1);

namespace App\Controller;

use App\API\Request\ParamConverter\PaginationParameters;
use App\API\Response\BadResponse;
use App\API\Response\CustomerListResponse;
use App\Services\CustomerService\CustomerServiceInterface;
use App\Services\CustomerService\Exception\CustomerNotFoundException;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/v1/customers")
 */
class CustomerController extends AbstractController
{
    private CustomerServiceInterface $customerService;

    public function __construct(CustomerServiceInterface $customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * @Rest\Get("/{id}", name="get_customer")
     */
    public function getCustomer(int $id): View
    {
        try {
            $customer = $this->customerService->getCustomerById($id);
            $view = View::create($customer, Response::HTTP_OK);
        } catch (CustomerNotFoundException $exception) {
            $view = View::create(
                new BadResponse($exception->getMessage()),
                Response::HTTP_NOT_FOUND
            );
        }

        return $view;
    }

    /**
     * @Rest\Get("/", name="get_customer_list")
     * @Rest\View(serializerGroups={"Default", "customers":{"api:customer:list"}})
     * @ParamConverter("paginationParameters", converter="app.pagination_query_converter")
     */
    public function getCustomerList(PaginationParameters $paginationParameters): View
    {
        $paginatedCustomers = $this->customerService->getCustomersPagination(
            $paginationParameters->getPage(),
            $paginationParameters->getLimit()
        );

        $view = View::create(
            CustomerListResponse::createFromPagination($paginatedCustomers),
            Response::HTTP_OK
        );

        return $view;
    }
}
