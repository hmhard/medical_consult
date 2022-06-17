<?php

namespace App\Twig;

use App\Repository\ConsultationRequestRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $consultationRequestRepository;
    public function __construct(ConsultationRequestRepository $consultationRequestRepository) {
        $this->consultationRequestRepository = $consultationRequestRepository;
    }
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('hasActiveRequest', [$this, 'hasActiveRequest']),
        ];
    }

    public function hasActiveRequest()
    {
        return $this->consultationRequestRepository->hasActiveRequest();
    }
}
