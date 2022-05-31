<?php

namespace App\Admin\Metrics;

use App\Enums\ProjectStatus;
use App\Enums\SalesOrderShippedStatus;
use App\Models\Project;
use App\Models\SalesOrder;
use Dcat\Admin\Admin;
use Dcat\Admin\Widgets\Metrics\Donut;

class SalesOrdersMetrics extends Donut
{
    protected $labels = [];
    protected $data = [];


    /**
     * 初始化卡片内容
     */
    protected function init()
    {
        parent::init();

        $this->labels = SalesOrderShippedStatus::getKeys();

        $status = SalesOrder::select('id', 'no', 'shipped_status')->get()->groupBy('shipped_status');
        foreach (SalesOrderShippedStatus::getValues() as $key => $value) {
            $this->data[$key] = isset($status[$value]) ? count($status[$value]) : 0;
        }

        $color = Admin::color();
        $colors = [$color->warning(), $color->primary(),  $color->success()];

        $this->title('Shipped Status');
        $this->subTitle('Until Now');
        $this->chartLabels($this->labels);
        // 设置图表颜色
        $this->chartColors($colors);
    }

    /**
     * 渲染模板
     *
     * @return string
     */
    public function render()
    {
        $this->fill();

        return parent::render();
    }

    /**
     * 写入数据.
     *
     * @return void
     */
    public function fill()
    {
        $this->withContent();
        // 图表数据
        $this->withChart($this->data);
    }

    /**
     * 设置图表数据.
     *
     * @param array $data
     *
     * @return $this
     */
    public function withChart(array $data)
    {
        return $this->chart([
            'series' => $data
        ]);
    }

    /**
     * 设置卡片头部内容.
     * @return $this
     */
    protected function withContent()
    {
        $style = 'margin-bottom: 4px';
        $labelWidth = 120;

        return $this->content(
            <<<HTML
<div class="d-flex pl-1 pr-1" style="{$style}">
    <div style="width: {$labelWidth}px">
        <i class="fa fa-circle text-warning"></i> {$this->labels[0]}
    </div>
    <div>{$this->data[0]}</div>
</div>
<div class="d-flex pl-1 pr-1" style="{$style}">
    <div style="width: {$labelWidth}px">
        <i class="fa fa-circle text-primary"></i> {$this->labels[1]}
    </div>
    <div>{$this->data[1]}</div>
</div>
<div class="d-flex pl-1 pr-1" style="{$style}">
    <div style="width: {$labelWidth}px">
        <i class="fa fa-circle text-success"></i> {$this->labels[2]}
    </div>
    <div>{$this->data[2]}</div>
</div>
HTML
        );
    }
}
