<?php

    namespace App\Services;

    use App\Repositories\BettingRepository;

    class BettingService
    {
    protected $bettingRepository;

    public function __construct(BettingRepository $bettingRepository)
    {
    $this->bettingRepository = $bettingRepository;
    }

    public function getBillers()
    {
    // Fetch billers from the repository or external API
    return $this->bettingRepository->fetchBillers();
    }

    public function validateCustomerId($billerId, $customerId)
    {
    // Validate customer ID via repository or external API
    return $this->bettingRepository->validateCustomer($billerId, $customerId);
    }

    public function purchaseBet(array $data)
    {
    // Perform purchase operation
    return $this->bettingRepository->purchaseBet($data);
    }
    }