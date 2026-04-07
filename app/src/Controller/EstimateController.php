<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\EstimateCalculator;
use App\Dto\EstimateRequest;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EstimateController extends AbstractController
{
    private EstimateCalculator $calculator;
    private ValidatorInterface $validator;

    public function __construct(EstimateCalculator $calculator, ValidatorInterface $validator)
    {
        $this->calculator = $calculator;
        $this->validator = $validator;
    }

    #[Route('/api/estimate', name: 'api_estimate', methods: ['POST'])]
    public function calculate(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return $this->json(['error' => 'Invalid JSON'], 400);
        }

        $estimateRequest = new EstimateRequest();
        $estimateRequest->works = $data['works'] ?? [];
        $estimateRequest->materials = $data['materials'] ?? [];
        $estimateRequest->adjustments = $data['adjustments'] ?? [];

        $errors = $this->validator->validate($estimateRequest);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getPropertyPath() . ': ' . $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }

        try {
            $result = $this->calculator->calculate($data);
            return $this->json($result);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }
}
