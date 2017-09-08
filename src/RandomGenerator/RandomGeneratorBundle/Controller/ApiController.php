<?php

namespace RandomGenerator\RandomGeneratorBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use RandomGenerator\RandomGeneratorBundle\Model\RandomNumberModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations;

class ApiController extends FOSRestController
{
    /**
     * @Annotations\Post("/generate")
     *
     * @param Request $request the request object
     *
     * @return JsonResponse
     */
    public function postGenerateAction(Request $request)
    {
        $min = RandomNumberModel::DEFAULT_MIN_VALUE;
        $max = RandomNumberModel::DEFAULT_MAX_VALUE;

        $postParams = $request->request->all();

        if (isset($postParams['min']) && is_numeric($postParams['min'])) {
            $min = $postParams['min'];
        }

        if (isset($postParams['max']) && is_numeric($postParams['max'])) {
            $max = $postParams['max'];
        }

        /** @var RandomNumberModel $randomNumberModel */
        $randomNumberModel = $this->container->get('random_generator.model.random_number');

        $id = $randomNumberModel->generateRandomNumber($min, $max);

        if (!$id) {
            return $this->failedResponse('Problem with number generating');
        }

        return $this->successResponse([
            'randomNumberId' => $id
        ]);
    }

    /**
     * @Annotations\Get("/retrieve/{id}", requirements={"id" = "\d+"})
     *
     * @return JsonResponse
     */
    public function getRetrieveAction($id)
    {
        /** @var RandomNumberModel $randomNumberModel */
        $randomNumberModel = $this->container->get('random_generator.model.random_number');

        $randomNumber = $randomNumberModel->getRandomNumber($id);

        if (!$randomNumber) {
            return $this->failedResponse('There is no number with this ID');
        }

        return $this->successResponse([
            'randomNumber' => $randomNumber->getValue()
        ]);
    }

    /**
     * @param $data mixed
     *
     * @return JsonResponse
     */
    protected function successResponse($data = array())
    {
        return new JsonResponse(array('success' => true, 'data' => $data));
    }

    /**
     * @param $message string
     *
     * @return JsonResponse
     */
    protected function failedResponse($message = '')
    {
        $key = 'message';

        if (is_array($message))
        {
            $key = 'messages';
        }

        $response = new JsonResponse(array('success' => false, $key => $message));

        //$response->setStatusCode('400');

        return $response;
    }
}
