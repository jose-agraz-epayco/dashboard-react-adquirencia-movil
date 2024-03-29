<?php

namespace App\Controller\Api;

use App\Common\TextResponsesCommon;
use App\Dto\PayPageDto;
use App\Dto\PropertySiteDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/configuration")
 */
class ConfigurationController extends BaseController
{
  /**
   * @Route("/property-site", name="api_config_property_site", methods={"GET"})
   */
  public function getPropertySite(Request $request)
  {
    $consult = $this->apify->consult('configuration/information');

    return $this->jsonResponse(
      $consult[0][TextResponsesCommon::SUCCESS],
      $consult[0][TextResponsesCommon::DATA],
      $consult[0][TextResponsesCommon::TEXT_RESPONSE]
    );
  }

  /**
   * @Route("/property-site", name="api_set_config_property_site", methods={"POST"})
   */
  public function setPropertySite(Request $request)
  {
    $content = $request->getContent();

    $content = json_decode($content, true);

    $propertyDto = new PropertySiteDto();
    $propertyDto->setNombreEmpresa($content[TextResponsesCommon::NOMBRE_EMPRESA]);
    $propertyDto->setRazonSocial($content[TextResponsesCommon::RAZON_SOCIAL]);
    $propertyDto->setTelefono($content[TextResponsesCommon::TELEFONO]);
    $propertyDto->setCelular($content[TextResponsesCommon::CELULAR]);
    $propertyDto->setIndicativoCiudad($content[TextResponsesCommon::INDICATIVO_CIUDAD]);
    $propertyDto->setIndicativoPais($content[TextResponsesCommon::INDICATIVO_PAIS]);

    $errors = $this->validator->validate($propertyDto);
    if (count($errors) > 0) {
      return $this->validatorErrorResponse($errors);
    }

    $data = [
      TextResponsesCommon::NOMBRE_EMPRESA => $content[TextResponsesCommon::NOMBRE_EMPRESA],
      TextResponsesCommon::RAZON_SOCIAL => $content[TextResponsesCommon::RAZON_SOCIAL],
      TextResponsesCommon::TELEFONO => $content[TextResponsesCommon::TELEFONO],
      TextResponsesCommon::CELULAR => $content[TextResponsesCommon::CELULAR],
      TextResponsesCommon::INDICATIVO_PAIS => $content[TextResponsesCommon::INDICATIVO_PAIS],
      TextResponsesCommon::INDICATIVO_CIUDAD => $content[TextResponsesCommon::INDICATIVO_CIUDAD],
      'tipoTelefonoValue' => $content['tipoTelefonoValue'],
      'campoTelValue' => $content['campoTelValue'],
      'valueIndicativo' => $content['valueIndicativo'],
      'paises' => [],
    ];

    $consult = $this->apify->consult('/configuration/information', \Requests::POST, $data);

    return $this->jsonResponse(
      $consult[0][TextResponsesCommon::SUCCESS],
      $consult[0][TextResponsesCommon::DATA],
      $consult[0][TextResponsesCommon::TEXT_RESPONSE]
    );
  }

  /**
   * @Route("/pay-page", name="api_config_pay_page", methods={"GET"})
   */
  public function getPayPage(Request $request)
  {
    $consult = $this->apify->consult('configuration/pay-page');

    return $this->jsonResponse(
      $consult[0][TextResponsesCommon::SUCCESS],
      $consult[0][TextResponsesCommon::DATA],
      $consult[0][TextResponsesCommon::TEXT_RESPONSE]
    );
  }

  /**
   * @Route("/pay-page", name="api_set_config_pay_page", methods={"POST"})
   */
  public function setPayPage(Request $request)
  {
    $content = $request->getContent();
    $content = json_decode($content, true);

    $propertyDto = new PayPageDto();
    $propertyDto->setLogo($content['logo']);

    $errors = $this->validator->validate($propertyDto);
    if (count($errors) > 0) {
      return $this->validatorErrorResponse($errors);
    }

    $data = [
      'logo' => $content['logo'],
    ];

    $consult = $this->apify->consult('/configuration/pay-page', \Requests::POST, $data);

    return $this->jsonResponse(
      $consult[0][TextResponsesCommon::SUCCESS],
      $consult[0][TextResponsesCommon::DATA],
      $consult[0][TextResponsesCommon::TEXT_RESPONSE]
    );
  }

  /**
   * @Route("/options-gateway", name="api_config_options_gateway", methods={"GET"})
   */
  public function getOptionGateway(Request $request)
  {
    $consult = $this->apify->consult('configuration/options-gateway');

    return $this->jsonResponse(
      $consult[0][TextResponsesCommon::SUCCESS],
      $consult[0][TextResponsesCommon::DATA],
      $consult[0][TextResponsesCommon::TEXT_RESPONSE]
    );
  }

  /**
   * @Route("/options-gateway", name="api_set_config_options_gateway", methods={"POST"})
   */
  public function setOptionGateway(Request $request)
  {
    $content = $request->getContent();
    $content = json_decode($content, true);

    $data = [
      'urlConfirmacion' => $content['urlConfirmacion'],
      'urlRespuesta' => $content['urlRespuesta'],
      'idioma' => $content['idiomaPredeterminado'],
    ];

    $consult = $this->apify->consult('/configuration/options-gateway', \Requests::POST, $data);

    return $this->jsonResponse(
      $consult[0][TextResponsesCommon::SUCCESS],
      $consult[0][TextResponsesCommon::DATA],
      $consult[0][TextResponsesCommon::TEXT_RESPONSE]
    );
  }

  /**
   * @Route("/keys", name="api_config_keys", methods={"GET"})
   */
  public function getKeys(Request $request)
  {
    $consult = $this->apify->consult('configuration/keys');

    return $this->jsonResponse(
      $consult[0][TextResponsesCommon::SUCCESS],
      $consult[0][TextResponsesCommon::DATA],
      $consult[0][TextResponsesCommon::TEXT_RESPONSE]
    );
  }
}
