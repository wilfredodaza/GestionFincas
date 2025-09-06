<div class="invoice-preview">
    <!-- Invoice -->
    <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-6">
        <div class="card invoice-preview-card p-sm-12 p-6">
            <div class="card-body invoice-preview-header rounded-4 p-6">
                <div class="header-invoice">
                    <table>
                        <tbody>
                            <tr>
                                <td class="text-left">
                                    <!-- <div class="d-flex svg-illustration align-items-center gap-2 mb-6">
                                        <span class="name-app"><?= isset(configInfo()['name_app']) ? configInfo()['name_app'] : 'Name' ?></span>
                                    </div> -->
                                </td>
                                <td class="text-rigth">
                                    <h3 class="mb-6"><?= "{$movement->type->name} # $movement->resolution" ?></h3>
                                    <div>
                                        <span><?= $movement->created_at ?></span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            
            <br>
            <div class="card-body py-6 px-0">
                <div class="d-flex justify-content-between flex-wrap">
                    <div class="w-100">
                        <table class="w-100 text-left table-customer">
                            <tbody>
                                <?php if(!empty($movement->provider)): ?>
                                    <tr>
                                        <td class="pe-4">Proveedor:</td>
                                        <td><?= $movement->provider->name ?></td>
                                        <td class="pe-4">Documento:</td>
                                        <td><?= "{$movement->provider->number}" ?></td>
                                        <td># Factura: </td>
                                        <td><?= $movement->number_bill ?></td>
                                    </tr>
                                <?php endif ?>
                                <tr>
                                    <td class="pe-4">Finca:</td>
                                    <td><?= $movement->farm->name ?></td>
                                    <td class="pe-4">Fecha <?= $movement->type->name ?>:</td>
                                    <td><?= $movement->date ?></td>
                                    <?php if(!empty($movement->seller)): ?>
                                        <td class="pe-4">Pagado por:</td>
                                        <td><?= $movement->seller ?></td>
                                    <?php endif ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            
            <br>
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Producto</th>
                        <?php if($movement->type->id == 2 && in_array($movement->state_id, [2, 3])): ?>
                            <th>Cant. Aprox.</th>
                        <?php endif ?>
                        <th>Cant.</th>
                        <th>Valor Unitario</th>
                        <th>Lote</th>
                        <th>Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($movement->details as $key => $detail): ?>
                    <tr>
                        <td><?= count($movement->details) - $key ?></td>
                        <td><?= "{$detail->name}" ?></td>
                        <?php if($movement->type->id == 2 && in_array($movement->state_id, [2, 3])): ?>
                            <td><?= "{$detail->approximate_amount}" ?></td>
                        <?php endif ?>
                        <td><?= "{$detail->quantity}" ?></td>
                        <td><?= number_format($detail->value, '2', '.', ',') ?></td>
                        <td><?= $detail->lot_name ?></td>
                        <td><?= number_format(($detail->value * $detail->quantity), '2', '.', ',') ?></td>
                    </tr>
                    <?php endforeach ?>
                    <tr>
                        <td colspan="5" class="text-rigth">Total: </td>
                        <td><?= number_format(($movement->value), '2', '.', ',') ?></td>
                    </tr>
                </tbody>
            </table>

            

            <hr class="mt-0 mb-6">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-12">
                        <span class="fw-medium text-heading">Notas:</span>
                        <?= $movement->note ?>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <!-- /Invoice -->
</div>