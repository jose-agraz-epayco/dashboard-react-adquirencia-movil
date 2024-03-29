<?php

namespace App\Controller\Api;

use App\Common\TextResponsesCommon;
use App\Dto\TransactionTableDto;
use App\Service\Apify;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Requests;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/api/transaction")
 */
class TransactionController extends BaseController
{
  /**
   * @var string
   */
  private $urlAppRest;
  /**
   * @var string
   */
  private $appRestEnv;

  public function __construct(
    string $urlAppRest,
    string $appRestEnv,
    Apify $apify,
    ValidatorInterface $validator,
    TranslatorInterface $translator
  ) {
    parent::__construct($apify, $validator, $translator);
    $this->urlAppRest = $urlAppRest;
    $this->appRestEnv = $appRestEnv;
  }

  /**
   * @Route( name="api_transaction_index", methods={"GET"})
   */
  public function index(Request $request)
  {
    $filters = $request->query->all();
    $transactionTable = $this->setDataToDto($filters);

    $errors = $this->validator->validate($transactionTable);
    if (count($errors) > 0) {
      return $this->validatorErrorResponse($errors);
    }

    $filters = $this->dtoToArray($transactionTable);
    $data = [
      TextResponsesCommon::PAGINATION => [
        'page' => $transactionTable->getPerPage(),
        TextResponsesCommon::LIMIT => $transactionTable->getLimit(),
      ],
      TextResponsesCommon::FILTER => $filters,
    ];

    $transactions = $this->apify->consult('transaction', Requests::POST, $data);

    if (
      isset($transactions[TextResponsesCommon::SUCCESS]) &&
      $transactions[TextResponsesCommon::SUCCESS] === true
    ) {
      $data = [
        'transactions' => $transactions[TextResponsesCommon::DATA][TextResponsesCommon::DATA],
        TextResponsesCommon::PAGINATION =>
          $transactions[TextResponsesCommon::DATA][TextResponsesCommon::PAGINATION],
        'aggregations' => $transactions[TextResponsesCommon::DATA]['aggregations'],
      ];
      return $this->jsonResponse(true, $data, $this->translator->trans('Successful query'));
    }

    return $this->jsonResponse(
      false,
      [],
      isset($transactions[TextResponsesCommon::TEXT_RESPONSE])
        ? $transactions[TextResponsesCommon::TEXT_RESPONSE]
        : 'Apify error',
      400
    );
  }

  /**
   * @Route("/detail/{id}", name="api_transaction_detail", methods={"GET"})
   */
  public function show(int $id)
  {
    $data = [
      TextResponsesCommon::FILTER => [
        TextResponsesCommon::REFERENCE_PAYCO => $id,
      ],
    ];

    $transaction = $this->apify->consult('transaction/detail', Requests::POST, $data);

    if (
      isset($transaction[TextResponsesCommon::SUCCESS]) &&
      $transaction[TextResponsesCommon::SUCCESS] === true
    ) {
      $message = isset($transaction[TextResponsesCommon::TEXT_RESPONSE])
        ? $transaction[TextResponsesCommon::TEXT_RESPONSE]
        : 'Transaction detail';
      return $this->jsonResponse(true, $transaction[TextResponsesCommon::DATA], $message);
    }

    $message = isset($transaction[TextResponsesCommon::TEXT_RESPONSE])
      ? $transaction[TextResponsesCommon::TEXT_RESPONSE]
      : 'Error apify consult';
    return $this->jsonResponse(false, [], $message, 400);
  }

  /**
   * @Route("/export.{_format}", requirements={"_format":"csv|xlsx"}, name="api_transaction_export", methods={"GET"})
   */
  public function export(Request $request)
  {
    $format = $request->attributes->get('_format');
    $filters = $request->query->all();
    $transactionTable = $this->setDataToDto($filters);

    $errors = $this->validator->validate($transactionTable);
    if (count($errors) > 0) {
      return $this->validatorErrorResponse($errors);
    }

    $filters = $this->dtoToArray($transactionTable);
    $data = [
      TextResponsesCommon::PAGINATION => [
        'page' => 1,
        TextResponsesCommon::LIMIT => $transactionTable->getLimit(),
      ],
      TextResponsesCommon::FILTER => $filters,
    ];

    $transactions = $this->apify->consult('transaction', Requests::POST, $data);

    if (
      isset($transactions[TextResponsesCommon::SUCCESS]) &&
      $transactions[TextResponsesCommon::SUCCESS] === true
    ) {
      $transactions = $transactions[TextResponsesCommon::DATA][TextResponsesCommon::DATA];
    }
    $data = $this->formatDataToExport($transactions);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->fromArray($data);

    if ($format === 'csv') {
      return $this->dataToCsv($spreadsheet);
    } else {
      return $this->dataToXlsx($spreadsheet);
    }
  }

  /**
   * @Route("/send/email/{id}/{email}", name="api_transaction_send_email", methods={"GET"})
   */
  public function sendEmail(int $id, string $email)
  {
    $url = sprintf(
      '%semail/transaccion?%s',
      $this->urlAppRest . $this->appRestEnv,
      http_build_query([
        'transaction_id' => $id,
        'email_adicional' => $email,
      ])
    );
    $sendEmailResponse = Requests::get($url);

    $bodyResponse = json_decode($sendEmailResponse->body, true);

    if (isset($bodyResponse['success']) && $bodyResponse['success'] == 'ok') {
      $message = 'Confirmacion enviada';
      return $this->jsonResponse(true, [], $message);
    }

    $message = 'Error';
    return $this->jsonResponse(true, [], $message, 400);
  }

  /**
   * @Route("/receipt/{id}", name="api_transaction_receipt", methods={"GET"})
   */
  public function receipt(int $id)
  {
    $transaction = $this->transactionDetail($id);

    if (!$transaction || $transaction['success'] === false) {
      $message = isset($transaction['textResponse'])
        ? $transaction['textResponse']
        : 'Error al consultar transacción';
      return $this->jsonResponse(false, [], $message, 400);
    }

    return $this->render('transaction/transactionDetail.html.twig', [
      'tr' => $transaction['data'],
      'user' => $this->getUser(),
    ]);
  }

  private function transactionDetail(int $id)
  {
    $data = [
      'filter' => [
        TextResponsesCommon::REFERENCE_PAYCO => $id,
      ],
    ];

    return $this->apify->consult('transaction/detail', Requests::POST, $data);
  }

  private function setDataToDto(array $filters): TransactionTableDto
  {
    $transactionTable = new TransactionTableDto();
    $transactionTable->setTransactionInitialDate(
      isset($filters['fromDate']) ? $filters['fromDate'] : null
    );
    $transactionTable->setTransactionEndDate(isset($filters['toDate']) ? $filters['toDate'] : null);
    $transactionTable->setLimit(
      isset($filters[TextResponsesCommon::LIMIT]) ? $filters[TextResponsesCommon::LIMIT] : 15
    );
    $transactionTable->setPage(isset($filters['page']) ? $filters['page'] : 1);
    $transactionTable->setStatusId(isset($filters['statusId']) ? (int) $filters['statusId'] : null);
    $transactionTable->setSearch(isset($filters['search']) ? $filters['search'] : null);
    $transactionTable->setPaymentMethodId(
      isset($filters[TextResponsesCommon::PAYMENT_METHOD])
        ? $filters[TextResponsesCommon::PAYMENT_METHOD]
        : null
    );
    $transactionTable->setEnviromentId(
      isset($filters['environment']) ? (int) $filters['environment'] : null
    );

    return $transactionTable;
  }

  private function dataToCsv(Spreadsheet $spreadsheet)
  {
    $writer = new Csv($spreadsheet);
    $writer->setEnclosure('');
    $writer->setDelimiter(';');

    // Crear archivo temporal en el sistema
    $fileName = sprintf('%s/reporte_transactions_%s.csv', sys_get_temp_dir(), time());

    // Guardar el archivo de csv en el directorio temporal del sistema
    $writer->save($fileName);

    // Retornar excel como descarga y eliminar archivo
    return $this->file($fileName)->deleteFileAfterSend();
  }

  private function dataToXlsx(Spreadsheet $spreadsheet)
  {
    $writer = new Xlsx($spreadsheet);

    // Crear archivo temporal en el sistema
    $fileName = sprintf('%s/reporte_transactions_%s.xlsx', sys_get_temp_dir(), time());

    // Guardar el archivo de excel en el directorio temporal del sistema
    $writer->save($fileName);

    // Retornar excel como descarga y eliminar archivo
    return $this->file($fileName)->deleteFileAfterSend();
  }

  private function formatDataToExport(array $transactions)
  {
    $data = [];
    foreach ($transactions as $transaction) {
      $row = [
        'ref_payco' => $transaction[TextResponsesCommon::REFERENCE_PAYCO],
        'factura' => $transaction['referenceClient'],
        'fecha' => $transaction['transactionDate'],
        'valor' => $transaction['amount'],
        'iva' => $transaction['iva'],
        'moneda' => $transaction['currency'],
        'descripcion' => $transaction['description'],
        'franquicia' => $transaction[TextResponsesCommon::PAYMENT_METHOD],
        'banco' => $transaction['bank'],
        'tarjeta' => $transaction['card'],
        'estado' => $transaction['status'],
        'respuesta' => $transaction['response'],
        'recibo' => $transaction['receipt'],
        'autorizacion' => $transaction['authorization'],
        'trmDia' => $transaction['trmdia'],
        'tipoDocUser' => $transaction['docType'],
        'cedula' => $transaction['document'],
        'nombres' => $transaction['names'],
        'apellidos' => $transaction['lastnames'],
      ];
      array_push($data, $row);
    }

    array_unshift($data, array_keys($data[0]));
    return $data;
  }
}
