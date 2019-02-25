<?php

namespace Edhub\CMS\Containers\Common\Values;


final class PaginationPresenter
{
    /**
     * @var int
     */
    private $perPage;
    /**
     * @var int
     */
    private $page;
    /**
     * @var int
     */
    private $total;

    public function __construct(int $perPage, int $page, int $total)
    {
        $this->perPage = $perPage;
        $this->page = ($page <= 0) ? 1 : $page;
        $this->total = $total;
    }

    public function display(): array
    {
        return [
            'per_page'     => $this->perPage(),
            'current_page' => $this->page(),
            'last_page'    => $this->numberOfPages(),
            'next_page'    => $this->nextPage(),
            'prev_page'    => $this->prevPage(),
        ];
    }

    public function perPage(): int
    {
        return $this->perPage;
    }

    public function page(): int
    {
        return $this->page;
    }

    public function total(): int
    {
        return $this->total;
    }

    public function numberOfPages(): int
    {
        if (empty($this->perPage)) {
            return 0;
        }

        return ceil($this->total / $this->perPage);
    }

    public function nextPage(): ?int
    {
        return ($this->page < $this->numberOfPages()) ? $this->page + 1 : null;
    }

    public function prevPage(): ?int
    {
        return ($this->page - 1) ?: null;
    }
}