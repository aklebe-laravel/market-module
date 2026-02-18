@php
    use Modules\DataTable\app\Http\Livewire\DataTable\Base\BaseDataTable;
    use Modules\Market\app\Models\Product;

    /**
     * @var BaseDataTable $this
     * @var Product $item
     * @var string $name
     * @var mixed $value The image path
     * @var string $link make clickable link if exists
     * @var string $imageBoxCss image-box css
     **/

    $link = $item->getFrontendLink();
    $title = $item->name;
@endphp
@include('data-table::livewire.js-dt.tables.columns.image')

