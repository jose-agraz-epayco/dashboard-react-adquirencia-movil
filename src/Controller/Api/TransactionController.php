<?php

namespace App\Controller\Api;

use App\Dto\TransactionTableDto;
use Requests;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/transaction")
 */
class TransactionController extends BaseController
{
  /**
   * @Route("/", name="api_transaction_index", methods={"GET"})
   */
  public function index(Request $request)
  {
    $filters = $request->query->all();
    $transactionTable = new TransactionTableDto();
    $transactionTable->setTransactionInitialDate(
      isset($filters['fromDate']) ? $filters['fromDate'] : null
    );
    $transactionTable->setTransactionEndDate(isset($filters['toDate']) ? $filters['toDate'] : null);
    $transactionTable->setLimit(isset($filters['limit']) ? $filters['limit'] : 15);
    $transactionTable->setPage(isset($filters['page']) ? $filters['page'] : 1);

    $errors = $this->validator->validate($transactionTable);
    if (count($errors) > 0) {
      return $this->validatorErrorResponse($errors);
    }

    $data = [
      'pagination' => [
        'page' => $transactionTable->getPerPage(),
        'limit' => $transactionTable->getLimit(),
      ],
      'filter' => [],
    ];

    $transactions = $this->apify->consult('transaction', Requests::POST, $data);

    dd($transactionTable);
  }

  /**
   * @Route("/detail/{id}", name="api_transaction_detail", methods={"GET"})
   */
  public function show(int $id)
  {
    $data = [
      'filter' => [
        'referencePayco' => $id,
      ],
    ];

    $transaction = $this->apify->consult('transaction/detail', Requests::POST, $data);

    if (isset($transaction['success']) && $transaction['success'] === true) {
      $message = isset($transaction['textResponse'])
        ? $transaction['textResponse']
        : 'Transaction detail';
      return $this->jsonResponse(true, $transaction['data'], $message);
    }

    $message = isset($transaction['textResponse'])
      ? $transaction['textResponse']
      : 'Error apify consult';
    return $this->jsonResponse(false, [], $message, 400);
  }
}
