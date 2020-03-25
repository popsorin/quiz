<?php


namespace Quiz\Service;


class PaginatorService
{
    /**
     * @var int
     */
    private $totalResults;

    /**
     * @var int
     */
    private $totalPages;

    /**
     * @var int
     */
    private $currentPage;

    /**
     * @var int
     */
    private $resultsPerPage;

    /**
     * PaginatorService constructor.
     * @param int $totalResults
     * @param int $currentPage
     * @param int $resultsPerPage
     */
    public function __construct(int $totalResults, int $currentPage = 1, int $resultsPerPage = 4)
    {
        $this->totalResults = $totalResults;
        $this->setCurrentPage($currentPage);
        $this->setResultsPerPage($resultsPerPage);
    }

    /**
     * @param int $currentPage
     * @return PaginatorService
     */
    public function setCurrentPage(int $currentPage): self
    {
        $this->currentPage = ($currentPage <= 0) ? 1 : $currentPage;
        return $this;
    }

    /**
     * @param int $resultsPerPage
     * @return PaginatorService
     */
    public function setResultsPerPage(int $resultsPerPage): self
    {
        $this->resultsPerPage = $resultsPerPage;
        $this->setTotalPages($resultsPerPage);

        return $this;
    }

    /**
     * @param int $resultsPerPage
     */
    private function setTotalPages(int $resultsPerPage): void
    {
        $this->totalPages = (int)($this->totalResults / $this->resultsPerPage);
    }

    /**
     * @return int
     */
    public function getTotalResults(): int
    {
        return $this->totalResults;
    }

    /**
     * @return int
     */
    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * @return int
     */
    public function getResultsPerPage(): int
    {
        return $this->resultsPerPage;
    }
}