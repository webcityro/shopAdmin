<?php
namespace Storemaker\System\Libraries;

class Pagination {
	private $itemsPerPage;
	private $numberOfPages;
	private $limitStart;
	private $totalCount;
	private $currentPage;
	private $maxPagesShowed = 10;
	private $starterChange = 8;
	private $range = 3;

	public function __construct() {
		$this->currentPage = 1;///url::getPaginationNr();
	}

	public function setItemsPerPage($value) {
		$this->itemsPerPage = $value;
		$this->limitStart = ($this->currentPage * $this->itemsPerPage) - $this->itemsPerPage;
	}

	public function setTotalCount($count) {
		$this->totalCount = $count;
		$this->numberOfPages = ceil($this->totalCount / $this->itemsPerPage);
	}

	public function getCurrentPage() {
		return $this->currentPage;
	}

	public function getNumOfPages() {
		return $this->numberOfPages;
	}

	public function getTotalItems() {
		return $this->totalCount;
	}

	public function getLimit() {
		return $this->limitStart.', '.$this->itemsPerPage;
	}

	public function renderPages() {
		$displayPagination = '';

		if ($this->totalCount > $this->itemsPerPage) {
			$displayPagination = '<div class="pagination"><ul>';
			$displayPagination .= ($this->currentPage > 1) ? '<li><a href="#">Pagina precedenta</a></li>' : '';
			// $displayPagination .= ($this->currentPage > 1) ? '<li><a href="'.url::setPaginationNr($this->currentPage-1).'">Pagina precedenta</a></li>' : '';

			$this->currentPage = $this->getCurrentPage();
			$starter = $this->setStarter();
			$forLoopLimit = ($this->maxPagesShowed <= $this->numberOfPages) ? $this->maxPagesShowed : $this->numberOfPages;

			for ($x = $starter; ($x - $starter + 1) <= $forLoopLimit; $x++) {
				$displayPagination .= ($x == $this->currentPage) ? '<li class="onPage">'.$x.'</li>' : '<li><a href="#">'.$x.'</a></li>';
				// $displayPagination .= ($x == $this->currentPage) ? '<li class="onPage">'.$x.'</li>' : '<li><a href="'.url::setPaginationNr($x).'">'.$x.'</a></li>';
			}

			$displayPagination .= ($this->currentPage < ($this->numberOfPages)) ? '<li><a href="#">Pagina urmatoare</a></li>' : '';
			// $displayPagination .= ($this->currentPage < ($this->numberOfPages)) ? '<li><a href="'.url::setPaginationNr($this->currentPage+1).'">Pagina urmatoare</a></li>' : '';
			$displayPagination .= '</ul></div>';
		}
		return $displayPagination;
	}

	private function setStarter() {
		if ($this->currentPage < $this->starterChange || $this->numberOfPages <= $this->maxPagesShowed) {
			$starter = 1;
		} else if (($this->numberOfPages - $this->currentPage) < $this->maxPagesShowed) {
			$starter = ($this->numberOfPages - $this->maxPagesShowed) + 1;
		} else {
			$starter = ($this->numberOfPages > $this->maxPagesShowed) ? ($this->currentPage - $this->range) : 1;
		}
		return $starter;
	}
}