<style>
    .text-info {
        background-image: url(data:image/svg+xml;charset=utf-8;base64,PHN2ZyB4bWxucz0naHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmcnIHdpZHRoPSc4JyBoZWlnaHQ9JzgnPjxjaXJjbGUgY3g9JzEnIGN5PSc0JyByPScxJyBmaWxsPScjZDBkM2Q3JyAvPjwvc3ZnPg==);
        background-repeat: repeat-x;
        background-position: left 8.75pt;
        min-height: 28px;
        display: flex;
        justify-content: space-between;
        margin-bottom: 9px;
    }

    .text-info div {
        color: rgb(44, 44, 44);
        background-color: rgb(255, 255, 255) !important
    }

    .text-info span {
        display: flex;
        padding: 5px;
        /*background-color:#e4e7e9 !important;*/
        overflow: scroll;
        overflow: hidden;
        border: 1px solid #ababab;
        min-height: 32px;
    }

</style>

<div class="row">
    <div class="col-md-12">
        <div class="card dcat-box">
            <div class="box-header with-border" style="padding: .65rem 1rem">
                <h3 class="box-title" style="line-height:30px;">{{ $salesOrder->no }}
                    ∙ {{ $salesOrder->sell_to_customer_name }}
                    @foreach($salesLines->pluck('item_category_code')->unique() as $category)
                        <span class="label bg-default">{{ $category }}</span>
                    @endforeach
                </h3>

                <div class="pull-right">
                    <div class="btn-group pull-right btn-mini" style="margin-right: 5px">
                        <a href="{{ url('/admin/sales_orders') }}" class="btn btn-sm btn-primary ">
                            <i class="feather icon-list"></i><span class="d-none d-sm-inline"> List</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <div class="form-horizontal mt-1">
                    <div>
                        <div class="row" style="margin-bottom: 5px">
                            <div class="col-md-6 col-sm-12">
                                <div class="text-info">
                                    <div style="padding: 5px 0;padding-right: 7px;">Customer NO.</div>
                                    <div style="padding-left: 7px;width: 58.31%;">
                                        <span>{{ $salesOrder->bill_to_customer_no }}</span>
                                    </div>
                                </div>
                                <div class="text-info">
                                    <div style="padding: 5px 0;padding-right: 7px;">Customer Name</div>
                                    <div style="padding-left: 7px;width: 58.31%;">
                                        <span>{{ $salesOrder->bill_to_name }}</span>
                                    </div>
                                </div>
                                <div class="text-info">
                                    <div style="padding: 5px 0;padding-right: 7px;">Address</div>
                                    <div style="padding-left: 7px;width: 58.31%;">
                                        <span>{{ $salesOrder->sell_to_address }}</span>
                                    </div>
                                </div>
                                <div class="text-info">
                                    <div style="padding: 5px 0;padding-right: 7px;">Currency Code</div>
                                    <div style="padding-left: 7px;width: 58.31%;">
                                        <span>{{ $salesOrder->currency_code == '' ? 'CNY' :$salesOrder->currency_code }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="text-info">
                                    <div style="padding: 5px 0;padding-right: 7px;">Project No.</div>
                                    <div style="padding-left: 7px;width: 58.31%;">
                                        <span>{{ $salesOrder->project_no }}</span>
                                    </div>
                                </div>
                                <div class="text-info">
                                    <div style="padding: 5px 0;padding-right: 7px;">Project Name</div>
                                    <div style="padding-left: 7px;width: 58.31%;">
                                        <span>{{ $salesOrder->project_name }}</span>
                                    </div>
                                </div>
                                <div class="text-info">
                                    <div style="padding: 5px 0;padding-right: 7px;">Document Date</div>
                                    <div style="padding-left: 7px;width: 58.31%;">
                                        <span>{{ substr($salesOrder->document_date, 0, 10) }}</span>
                                    </div>
                                </div>
                                <div class="text-info">
                                    <div style="padding: 5px 0;padding-right: 7px;">Order Date</div>
                                    <div style="padding-left: 7px;width: 58.31%;">
                                        <span>{{ substr($salesOrder->order_date, 0, 10) }}</span>
                                    </div>
                                </div>
                                <div class="text-info">
                                    <div style="padding: 5px 0;padding-right: 7px;">External Document No.</div>
                                    <div style="padding-left: 7px;width: 58.31%;">
                                        <span>{{ $salesOrder->external_document_no }}</span>
                                    </div>
                                </div>
                                <div class="text-info">
                                    <div style="padding: 5px 0;padding-right: 7px;">Status</div>
                                    <div style="padding-left: 7px;width: 58.31%;">
                                        <span>{{ \App\Enums\SalesOrderStatus::getKey($salesOrder->status) }}</span>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="table-responsive table-wrapper complex-container table-middle mt-1 table-collapse ">
    <table class="table custom-data-table data-table" id="grid-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Type</th>
            <th>No.</th>
            <th>Variant Code</th>
            <th>Description</th>
            <th>Location Code</th>
            <th style="text-align: right">Quantity</th>
            <th>Unit of Measure Code</th>
            <th style="text-align: right">Unit Price Excl. VAT</th>
            <th style="text-align: right">Line Amount Excl. VAT</th>
            <th style="text-align: center">Quantity Shipped</th>
            <th style="text-align: center">Quantity Invoiced</th>
        </tr>
        </thead>

        <tbody>
        @foreach($salesLines as $key=>$salesLine)
            <tr {{ $currency = $salesOrder->currency_code == '' ? 'CNY' :$salesOrder->currency_code }}>
                <td>{{ ++$key }}</td>
                <td>{{ \App\Enums\SalesLineType::getKey($salesLine->type) }}</td>
                <td>{{ $salesLine->no }}</td>
                <td>{{ $salesLine->variant_code }}</td>
                <td>{{ $salesLine->description }}</td>
                <td>{{ $salesLine->location_code }}</td>
                <td style="text-align: right">{{ $salesLine->quantity ? floatval($salesLine->quantity) : '-' }}</td>
                <td>{{ $salesLine->unit_of_measure_code }}</td>
                <td style="text-align: right">{{ $salesLine->unit_price && $salesLine->unit_price != 0 ? getCurrencyIcon($currency).floatval($salesLine->unit_price) : '-' }}</td>
                <td style="text-align: right">{{ $salesLine->line_amount && $salesLine->line_amount != 0 ? getCurrencyIcon($currency).floatval($salesLine->line_amount) : '-' }}</td>

                @if($salesLine->quantity_shipped == $salesLine->quantity && $salesLine->quantity)
                    <td style="text-align: center;"><i class="fa fa-check text-success"></i></td>
                @else
                    <td style="text-align: center">{{ $salesLine->quantity_shipped ? floatval($salesLine->quantity_shipped).'/'.floatval($salesLine->quantity) : '-' }}</td>
                @endif

                @if($salesLine->quantity_invoiced == $salesLine->quantity && $salesLine->quantity)
                    <td style="text-align: center;"><i class="fa fa-check text-success"></i></td>
                @else
                    <td style="text-align: center">{{ $salesLine->quantity_invoiced ? floatval($salesLine->quantity_invoiced).'/'.floatval($salesLine->quantity) : '-' }}</td>
                @endif

            </tr>
        @endforeach

        <tr>
            <td style="text-align: right" colspan="10">{{ getCurrencyIcon($currency) . number_format($salesLines->sum('line_amount'), 2) }}</td>
            <td colspan="2"></td>
        </tr>
        </tbody>
    </table>
</div>
