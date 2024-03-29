<?php

namespace App\Controller\Api;

use App\Common\TextResponsesCommon;
use App\Dto\PasswordDto;
use App\Dto\PayPageDto;
use App\Dto\ProfileDto;
use App\Dto\PropertySiteDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/security")
 */
class SecurityController extends BaseController
{
  /**
   * @Route("/profile-data", name="api_security_profile_data", methods={"GET"})
   */
  public function getProfileData(Request $request)
  {
    $consult = $this->apify->consult('/client/edit', \Requests::POST, []);

    return $this->jsonResponse(
      $consult[TextResponsesCommon::SUCCESS],
      $consult[TextResponsesCommon::DATA],
      $consult[TextResponsesCommon::TEXT_RESPONSE]
    );
  }

  /**
   * @Route("/profile-data", name="api_set_secutiry_profile_data", methods={"POST"})
   */
  public function setProfileData(Request $request)
  {
    $content = json_decode($request->getContent());

    $propertyDto = new ProfileDto();
    $propertyDto->setNombreEmpresa($content->nombreEmpresa);
    $propertyDto->setDominio($content->dominio);

    $errors = $this->validator->validate($propertyDto);
    if (count($errors) > 0) {
      return $this->validatorErrorResponse($errors);
    }

    $data = [
      'companyName' => $content->nombreEmpresa,
      'domain' => $content->dominio,
      'mobilePhone' => $content->telefono,
      'cellPhone' => $content->celular,
      'indicativeCountry' => $content->indicativoPais,
      'indicativeCity' => $content->indicativoCiudad,
    ];
    $consult = $this->apify->consult('/client/update', \Requests::POST, $data);

    return $this->jsonResponse(
      $consult[TextResponsesCommon::SUCCESS],
      $consult[TextResponsesCommon::DATA],
      $consult[TextResponsesCommon::TEXT_RESPONSE]
    );
  }

  /**
   * @Route("/set-password", name="api_security_set_password", methods={"POST"})
   */
  public function setPassword(Request $request)
  {
    $content = json_decode($request->getContent());
    $user = $this->getUser();

    $propertyDto = new PasswordDto();
    $propertyDto->setOldPassword($content->password);
    $propertyDto->setNewPassword($content->newPassword);
    $propertyDto->setNewPasswordConfirmation($content->newPasswordConfirmation);

    $errors = $this->validator->validate($propertyDto);
    if (count($errors) > 0) {
      return $this->validatorErrorResponse($errors);
    }

    $data = [
      'email' => $user->getUsername(),
      'password' => $content->password,
      'passwordNew' => $content->newPassword,
      'passwordRepeat' => $content->newPasswordConfirmation,
    ];

    $consult = $this->apify->consult('/client/update/password', \Requests::POST, $data);

    return $this->jsonResponse(
      $consult[TextResponsesCommon::SUCCESS],
      $consult[TextResponsesCommon::DATA],
      $consult[TextResponsesCommon::TEXT_RESPONSE]
    );
  }
}
