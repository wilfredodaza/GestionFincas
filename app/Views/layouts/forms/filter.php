<div class="col-lg-5 col-md-6 col-sm-12">
    <div
        class="offcanvas offcanvas-end"
        tabindex="-1"
        id="canvasFilter"
        aria-labelledby="canvasFilterLabel">
            <div class="offcanvas-header">
                <h5 id="canvasFilterLabel" class="offcanvas-title">Filtro</h5>
                <button
                    type="button"
                    class="btn-close text-reset"
                    data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body mx-0 flex-grow-0">
                <form action="" id="form-filter" onsubmit="sendFilter(event)">
                    <div class="row">

                        <?php foreach($data->form_filter as $input): ?>
                            <?php switch($input->type):
                                case 'hidden': ?>
                                    <input type="hidden" name="<?= $input->name ?>" id="<?= $input->name ?>" value="<?= $input->value ?>">
                                <?php break;
                                case 'select': ?>
                                    <div class="col-sm-12 mb-2">
                                        <div class="form-floating form-floating-outline">
                                            <select class="form-select <?= $input->required ? "required" : "" ?> <?= $input->allow_new ? "allow-new" : "" ?>" id="<?= $input->name ?>" name="<?= $input->name ?>" data-allow-clear="true">
                                                <option value="" selected>Seleccionar</option>
                                                <?php foreach($input->options as $option): ?>
                                                    <option value="<?= $option->id ?>" <?= $input->value == $option->id ? "selected" : "" ?>><?= "{$option->name}" ?></option>
                                                <?php endforeach ?>
                                            </select>
                                            <label for="<?= $input->name ?>"><?= $input->required ? "* " : "" ?><?= $input->title ?></label>
                                            <span class="form-floating-focused"></span>
                                        </div>
                                    </div>
                                <?php break;
                                case 'number': ?>
                                    <div class="col-sm-12 mb-2">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" onkeyup="onlyNumericKeypress(event)" class="form-control <?= $input->required ? "required" : "" ?>" name="<?= $input->name ?>" id="<?= $input->name ?>" placeholder="">
                                            <label for="<?= $input->name ?>"><?= $input->required ? "* " : "" ?><?= $input->title ?></label>
                                        </div>
                                    </div>
                                <?php break;
                                case 'date': ?>
                                    <div class="col-sm-12 mb-2">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control date-input <?= $input->required ? "required" : "" ?>" placeholder="YYYY-MM-DD" name="<?= $input->name ?>" id="<?= $input->name ?>" placeholder="">
                                            <label for="<?= $input->name ?>"><?= $input->required ? "* " : "" ?><?= $input->title ?></label>
                                        </div>
                                    </div>
                                <?php break;
                                case 'date_range': ?>
                                    <small class="mb-2 text-muted"><?= $input->title ?></small>
                                    <div class="col-sm-12 col-lg-6 col-md-12 mb-2">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control date-input <?= $input->required ? "required" : "" ?>" placeholder="YYYY-MM-DD" name="date_init" id="date_init" placeholder="">
                                            <label for="date_init"><?= $input->required ? "* " : "" ?>Fecha de inicio</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-lg-6 col-md-12 mb-2">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control date-input <?= $input->required ? "required" : "" ?>" placeholder="YYYY-MM-DD" name="date_end" id="date_end" placeholder="">
                                            <label for="date_end"><?= $input->required ? "* " : "" ?>Fecha de fin</label>
                                        </div>
                                    </div>
                                    <br>
                                <?php break;
                                default: ?>
                                    <div class="col-sm-12 mb-2">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control <?= $input->required ? "required" : "" ?>" name="<?= $input->name ?>" id="<?= $input->name ?>" placeholder="">
                                            <label for="<?= $input->name ?>"><?= $input->required ? "* " : "" ?><?= $input->title ?></label>
                                        </div>
                                    </div>
                                <?php break; ?>
                            <?php endswitch ?>
                        <?php endforeach ?>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary mb-2 d-grid w-100 waves-effect waves-light">Filtrar</button>
                    <button type="button" class="btn btn-danger mb-2 d-grid w-100 waves-effect waves-light btn-close-filter" data-bs-dismiss="offcanvas">Cerrar</button>
                </form>
            </div>
    </div>
</div>