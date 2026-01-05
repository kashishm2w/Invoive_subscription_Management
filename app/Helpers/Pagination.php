<?php
namespace App\Helpers;
// Pagination class handles page calculation and link generation
class Pagination
{
    // Current page number 
    public int $currentPage;

    // Total number of items
    public int $totalItems;

    // Number of items to show per page
    public int $itemsPerPage;

    // Total number of pages calculated
    public int $totalPages;

    
    // Constructor initializes pagination values
   
    public function __construct(
        int $totalItems,
        int $itemsPerPage = 10,
        int $currentPage = 1
    ) {
        // Store total items
        $this->totalItems = $totalItems;

        // Store items per page
        $this->itemsPerPage = $itemsPerPage;

        // Calculate total pages 
        $this->totalPages = max(1, ceil($totalItems / $itemsPerPage));

        // Ensure current page stays within valid range
        $this->currentPage = max(1, min($currentPage, $this->totalPages));
    }

     //Calculate SQL OFFSET value

    public function offset(): int
    {
        return ($this->currentPage - 1) * $this->itemsPerPage;
    }

    
    // Generate pagination HTML links
    
    public function pageLinks(array $queryParams = []): string
    {
        $html = '';

        // Closure to build page URLs dynamically
        $buildLink = function ($page) use ($queryParams) {
            // Keep existing filters and update page number
            $params = $queryParams;
            $params['page'] = $page;

            // Build query string
            return '?' . http_build_query($params);
        };

        // Show previous link if not on first page
        if ($this->currentPage > 1) {
            $html .= "<a href='" . $buildLink($this->currentPage - 1) . "'>&laquo; Previous</a> ";
        }

        // Generate numbered page links
        for ($i = 1; $i <= $this->totalPages; $i++) {
            // Highlight current page
            $active = ($i == $this->currentPage) ? 'active' : '';
            $html .= "<a href='" . $buildLink($i) . "' class='{$active}'>$i</a> ";
        }

        // Show next link if not on last page
        if ($this->currentPage < $this->totalPages) {
            $html .= "<a href='" . $buildLink($this->currentPage + 1) . "'>Next &raquo;</a>";
        }

        return $html;
    }
}
