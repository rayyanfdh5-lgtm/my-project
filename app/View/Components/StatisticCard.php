<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StatisticCard extends Component
{
    public $totalMasuk;

    public $totalKeluar;

    public $totalValue;

    public $jumlahJenisBarang;

    public $userCount;

    public $supplierCount;

    public $categoryCount;

    public $borrowableItemsCount;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($totalMasuk, $totalKeluar, $totalValue, $jumlahJenisBarang, $userCount, $supplierCount, $categoryCount, $borrowableItemsCount)
    {
        $this->totalMasuk = $totalMasuk;
        $this->totalKeluar = $totalKeluar;
        $this->totalValue = $totalValue;
        $this->jumlahJenisBarang = $jumlahJenisBarang;
        $this->userCount = $userCount;
        $this->supplierCount = $supplierCount;
        $this->categoryCount = $categoryCount;
        $this->borrowableItemsCount = $borrowableItemsCount;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('admin.components.libraries.statisticCard');
    }
}
