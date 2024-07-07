<?php

namespace App\Repositories\Product;

use App\Models\Product;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\DB;

class ProductRepository extends BaseRepository implements ProductInterface
{

    const VAT_TYPE_PERCENT = 1;
    const VAT_TYPE_FIAT = 2;

    public $aryVatType = [
        self::VAT_TYPE_PERCENT => '%',
        self::VAT_TYPE_FIAT => 'Vnđ'
    ];

    const WARRANTY_DAY = 1;
    const WARRANTY_MONTH = 2;
    const WARRANTY_YEAR = 3;
    public $warranty_type = [
        self::WARRANTY_DAY => 'Ngày',
        self::WARRANTY_MONTH => 'Tháng',
        self::WARRANTY_YEAR => 'Năm',
    ];

    const STATUS_SHOW = 1;
    const STATUS_HIDE = 0;


    public $status = [
        self::STATUS_SHOW => 'Hiện',
        self::STATUS_HIDE => 'Ẩn',
    ];
    const TYPICAL_SHOW = 1;
    const TYPICAL_HINE = 0;
    public $typical = [
        self::TYPICAL_SHOW => 'Hiện',
        self::TYPICAL_HINE => 'Ẩn',
    ];

    const NEW_SHOW = 1;
    const NEW_HINE = 0;
    public $new = [
        self::NEW_SHOW => 'Hiện',
        self::NEW_HINE => 'Ẩn',
    ];
    const APPROVAL_WAIT = 0;
    const APPROVAL_DONE = 1;
    const APPROVAL_CANCEL = 2;

    public $approval = [
        self::APPROVAL_WAIT => 'Chờ duyệt',
        self::APPROVAL_DONE => 'Duyệt',
        self::APPROVAL_CANCEL => 'Hủy duyệt',
    ];

    public function model()
    {
        // TODO: Implement model() method.
        return Product::class;
    }

    public function createWithStock($data, $stock, $discount = [])
    {
        return DB::transaction(function () use ($data, $stock, $discount) {
            $product = $this->create($data);
            $product->stocks()->createMany($stock);
            if ($discount) {
                $product->discounts()->createMany($discount);
            }
            return $product;
        });
    }

    public function updateProduct($product, $data)
    {
        $product = $this->edit($product, $data);
    }

}
