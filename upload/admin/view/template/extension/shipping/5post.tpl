<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <a href="<?php echo $orders; ?>" target="_blank" id="orders" data-toggle="tooltip" class="btn btn-info">К заказам</a>
                <a href="<?php echo $import; ?>" target="_blank" id="import-city" data-toggle="tooltip" title="Импорт местоположений" class="btn btn-success"><i class="fa fa-upload"></i></a>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="Отмена" class="btn btn-default"><i class="fa fa-reply"></i></a>

                <!-- <button type="submit" form="form-5post" data-toggle="tooltip" title="Сохранить" class="btn btn-primary"><i class="fa fa-save"></i></button> -->

            </div>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>

                <li>/</li>

                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($success) { ?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-5post" class="form-horizontal">
                    <input type="submit" value="Сохранить">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab-main" data-toggle="tab">Настройки</a></li>
                        <?php if ($rateType_list){ ?>

                        <li><a href="#tab-warehouses" data-toggle="tab">Склады</a></li>
                        <li><a href="#tab-calc" data-toggle="tab">Калькуляция</a></li>
                        <li><a href="#tab-status" data-toggle="tab">Обратная связь(Статус)</a></li>
                        <?php } ?>
                        <li><a href="#tab-faq" data-toggle="tab">FAQ</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-main">
                            <div class="alert alert-warning" style="color:black;" role="alert">Перед тем, как начать настройку модуля, рекомендуем ознакомиться с информацией на вкладке FAQ</div>
                            <div class="alert alert-info" align="center" role="alert">Основные</div>
                            <div class="form-group required">
                                <div class="sh-flex">
                                    <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="Ключ выдается для доступа к сервису 5post. Чтобы получить ключ, необходимо обратиться в 5post. Если обнаружились проблемы с ключом, также необходимо обратиться в 5post.">Api key:</span></label>
                                    <div class="col-sm-4">
                                        <input type="text" name="shipping_5post_client_id" value="<?php echo $shipping_5post_client_id; ?>" class="form-control">
                                        <?php if ($error_client_id) { ?>
                                        <div class="text-danger"><?php echo $error_client_id; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-test">Тестовый режим:</label>
                                <div class="col-sm-4">
                                    <div class="checkbox">
                                        <label> <?php if ($shipping_5post_test) { ?>
                                            <input type="checkbox" id="input-test" name="shipping_5post_test" checked="checked" />
                                            <?php } else { ?>
                                            <input type="checkbox" id="input-test" name="shipping_5post_test" />
                                            <?php } ?> </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group required">
                                <div class="sh-flex">
                                    <label class="col-sm-4 control-label">Наименование доставки:</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="shipping_5post_name_pvz" value="<?php echo $shipping_5post_name_pvz; ?>" class="form-control">
                                        <?php if ($error_name_pvz) { ?>
                                        <div class="text-danger"><?php echo $error_name_pvz; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-status-module">Статус модуля:</label>
                                <div class="col-sm-3">
                                <input name="5post_status" type="hidden" value="<?= $shipping_5post_status ?>">
                                    <select name="shipping_5post_status" id="input-status-module" class="form-control">
                                        <?php if ($shipping_5post_status) { ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php } else { ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="sh-flex">
                                    <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="Определяет порядок  отображения служб доставки в оформлении заказа (0 - наиболее приоритетный).">Сортировка:</span></label>
                                    <div class="col-sm-3">
                                        <input type="number" min="0" name="shipping_5post_sort_order" id="sort-order" value="<?php echo $shipping_5post_sort_order; ?>" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-info" align="center" role="alert">Общие</div>
                            <div class="form-group">
                                <div class="sh-flex">
                                    <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="Увеличивает срок доставки на указанное количество дней. Используется для учета срока на комплектацию и отправку заказа на склад 5Post.">Увеличить срок доставки:</span></label>
                                    <div class="col-sm-3">
                                        <input type="number" min="1" name="shipping_5post_increase" id="increase" value="<?php echo $shipping_5post_increase; ?>" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-orderShipping-module"><span data-toggle="tooltip" title="Указывает, когда добавить кнопку оформления заявки на доставку  5Post в административной части: всегда либо только в том случае, если заказ оформлен с выбором доставки 5Post. Это актуально, если на сайте используете несколько служб доставки.">Отображать кнопку заявки в заказах:</span></label>
                                <div class="col-sm-3">
                                    <select name="shipping_5post_display_orders" id="input-orderShipping-module" class="form-control">
                                        <?php if ($shipping_5post_display_orders) { ?>
                                        <option value="1" selected="selected">Всегда</option>
                                        <option value="0">Доставка модуля</option>
                                        <?php } else { ?>
                                        <option value="1">Всегда</option>
                                        <option value="0" selected="selected">Доставка модуля</option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="sh-flex">
                                    <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="Передаётся клиенту в смс, как отправитель заказа. Если поле не передавать, то для нотификации используется название, указанное при регистрации в системе 5post(сокращенное наименование).">Бренд отправителя:</span></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="shipping_5post_brand" value="<?php echo $shipping_5post_brand; ?>" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="sh-flex">
                                    <label class="col-sm-4 control-label">Способ обработки невостребованных заказов:</label>
                                    <div class="col-sm-3">
                                        <select name="shipping_5post_undeliverableOption" id="input-undeliverableOption-module" class="form-control">
                                         <?php if($shipping_5post_undeliverableOption=='RETURN'){ ?>

                                            <option value="RETURN" selected="selected">Возврат на склад партнера</option>
                                            <option value="UTILIZATION">Утилизация</option>
                                            <?php } else { ?>
                                            <option value="RETURN">Возврат на склад партнера</option>
                                            <option value="UTILIZATION" selected="selected">Утилизация</option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-info" align="center" role="alert">Настройки товара</div>
                            <div class="alert alert-warning" style="color:black;" role="alert">
                                Эта группа настроек отвечает за экспорт свойств товара.
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-nds"><span data-toggle="tooltip" title="В товары будут подставлены значения НДС, указанные в торговом каталоге, иначе будут заменено на НДС по умолчанию">Использовать данные об НДС из каталога :</span></label>
                                <div class="col-sm-4">
                                    <div class="checkbox">
                                        <label> <?php if ($shipping_5post_nds) { ?>
                                            <input type="checkbox" id="input-nds" name="shipping_5post_nds" checked="checked" />
                                            <?php } else { ?>
                                            <input type="checkbox" id="input-nds" name="shipping_5post_nds" />
                                            <?php } ?> </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-5post-tax">Ставка НДС по умолчанию:</label>
                                <div class="col-sm-3">
                                    <select name="shipping_5post_tax" id="input-5post-tax" class="form-control">
                                        <?php foreach ($tax_list as $tax) { ?>
                                        <?php if ($tax['value'] == $shipping_5post_tax) { ?>
                                        <option value="<?php echo $tax['value']; ?>" selected="selected"><?php echo $tax['name']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $tax['value']; ?>"><?php echo $tax['name']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="alert alert-info" align="center" role="alert">Габариты товара по-умолчанию</div>
                            <div class="alert alert-warning" style="color:black;" role="alert">
                                Данная группа настроек предназначена для определения габаритов тех заказов, где присутствуют товары без заполненных габаритов -  размеров и/или веса. Здесь можно задать те значения, что будут браться по умолчанию для расчета стоимости отправления.
                            </div>
                            <div class="form-group">
                                <div class="sh-flex">
                                    <label class="col-sm-4 control-label">Вес, г:</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="shipping_5post_weightD" value="<?php echo $shipping_5post_weightD; ?>" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="sh-flex">
                                    <label class="col-sm-4 control-label">Длина, мм:</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="shipping_5post_lenghtD" value="<?php echo $shipping_5post_lenghtD; ?>" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="sh-flex">
                                    <label class="col-sm-4 control-label">Ширина, мм:</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="shipping_5post_widthD" value="<?php echo $shipping_5post_widthD; ?>" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="sh-flex">
                                    <label class="col-sm-4 control-label">Высота, мм:</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="shipping_5post_heightD" value="<?php echo $shipping_5post_heightD; ?>" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-warehouses">
                            <div class="alert alert-warning" style="color:black;" role="alert">
                                Перед началом работы необходимо создать склады, с которых вы отгружаете заказы. Все поля являются обязательными.
                                <br /><br />
                                Необходимо уведомить 5POST об открытии и добавлении нового склада для создания связки  склад партнера-склад 5POST. Без этого доставить заказ невозможно
                                Одновременно можно использовать несколько складов. Список складов хранится  в базе данных и на уровне административного интерфейса отображается не весь список, а последний добавленный склад.
                                <br /><br />
                                При необходимости редактировать склад или добавить новый,  обратитесь в 5POST.
                                <br /><br />
                                Выбрать склад можно при создании заявки.
                                <br /><br />
                                Если вы используете несколько складов и несколько тарифных планов, рекомендуем установить опцию “Автовыбор минимальной стоимости” во вкладке Калькуляция.
                                Рекомендуем быть внимательными, при заполнении полей, так как отредактировать либо удалить склад самостоятельно невозможно. В этом случае обращайтесь в 5POST.
                                Учтите, что, если вы создали склад на тестовом аккаунте - его надо будет создать заново на боевом.
                            </div>

                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#warehousesModal">Добавить склад</button>
                            <?php if($warehouse){ ?>

                            <br /><br />
                            <table class="table table-hover" style="width: 50%">
                                <tbody>
                                <tr style="background-color: #EDF7F9;">
                                    <td style="width: 50%; text-align: center;"><b>Наименование склада:</b></td>
                                    <td style="width: 50%; text-align: center;"><?php echo $warehouse['name']; ?></td>
                                </tr>
                                <tr>
                                    <td style="width: 50%; text-align: center;"><b>ID расположения в системе партнера:</b></td>
                                    <td style="width: 50%; text-align: center;"><?php echo $warehouse['partnerId']; ?></td>
                                </tr>
                                <tr>
                                    <td style="width: 50%; text-align: center;"><b>Регион:</b></td>
                                    <td style="width: 50%; text-align: center;"><?php echo $warehouse['region']; ?></td>
                                </tr>
                                <tr>
                                    <td style="width: 50%; text-align: center;"><b>Наименование области:</b></td>
                                    <td style="width: 50%; text-align: center;"><?php echo $warehouse['federaldistrict']; ?></td>
                                </tr>
                                <tr>
                                    <td style="width: 50%; text-align: center;"><b>Почтовый индекс:</b></td>
                                    <td style="width: 50%; text-align: center;"><?php echo $warehouse['zip']; ?></td>
                                </tr>
                                <tr>
                                    <td style="width: 50%; text-align: center;"><b>Наименование города:</b></td>
                                    <td style="width: 50%; text-align: center;"><?php echo $warehouse['city']; ?></td>
                                </tr>
                                <tr>
                                    <td style="width: 50%; text-align: center;"><b>Наименование улицы:</b></td>
                                    <td style="width: 50%; text-align: center;"><?php echo $warehouse['street']; ?></td>
                                </tr>
                                <tr>
                                    <td style="width: 50%; text-align: center;"><b>Номер дома:</b></td>
                                    <td style="width: 50%; text-align: center;"><?php echo $warehouse['house']; ?></td>
                                </tr>
                                <tr>
                                    <td style="width: 50%; text-align: center;"><b>Географические координаты:</b></td>
                                    <td style="width: 50%; text-align: center;"><?php echo $warehouse['coordsX']; ?> x <?php echo $warehouse['coordsY']; ?></td>
                                </tr>
                                <tr>
                                    <td style="width: 50%; text-align: center;"><b>Контактный телефон:</b></td>
                                    <td style="width: 50%; text-align: center;"><?php echo $warehouse['phone']; ?></td>
                                </tr>
                                <tr>
                                    <td style="width: 50%; text-align: center;"><b>Часовой пояс:</b></td>
                                    <td style="width: 50%; text-align: center;"><?php echo $warehouse['timezone']; ?></td>
                                </tr>
                                <tr style="background-color: #EDF7F9;">
                                    <td style="text-align: center;" colspan="2"><b>Расписание работы</b></td>
                                </tr>
                                <?php foreach ($warehouse['worktime'] as $key => $wt ){ ?>
                                <?php if($key =='1'){ ?>

                                <tr>
                                    <td style="width: 50%; text-align: center;"><b>Понедельник:</b></td>
                                    <td style="width: 50%; text-align: center;"><?php echo $wt['worktimeOpen']; ?> - <?php echo $wt['worktimeClose']; ?></td>
                                </tr>
                                <?php } ?>
                                <?php if($key =='2'){ ?>

                                <tr>
                                    <td style="width: 50%; text-align: center;"><b>Вторник:</b></td>
                                    <td style="width: 50%; text-align: center;"><?php echo $wt['worktimeOpen']; ?> - <?php echo $wt['worktimeClose']; ?></td>
                                </tr>
                                <?php } ?>
                                <?php if($key =='3'){ ?>

                                <tr>
                                    <td style="width: 50%; text-align: center;"><b>Среда:</b></td>
                                    <td style="width: 50%; text-align: center;"><?php echo $wt['worktimeOpen']; ?> - <?php echo $wt['worktimeClose']; ?></td>
                                </tr>
                                <?php } ?>
                                <?php if($key =='4'){ ?>


                                <tr>
                                    <td style="width: 50%; text-align: center;"><b>Четвер:</b></td>
                                    <td style="width: 50%; text-align: center;"><?php echo $wt['worktimeOpen']; ?> - <?php echo $wt['worktimeClose']; ?></td>
                                </tr>
                                <?php } ?>
                                <?php if($key =='5'){ ?>

                                <tr>
                                    <td style="width: 50%; text-align: center;"><b>Пятница:</b></td>
                                    <td style="width: 50%; text-align: center;"><?php echo $wt['worktimeOpen']; ?> - <?php echo $wt['worktimeClose']; ?></td>
                                </tr>
                                <?php } ?>
                                <?php if($key =='6'){ ?>

                                <tr>
                                    <td style="width: 50%; text-align: center;"><b>Суббота:</b></td>
                                    <td style="width: 50%; text-align: center;"><?php echo $wt['worktimeOpen']; ?> - <?php echo $wt['worktimeClose']; ?></td>
                                </tr>
                                <?php } ?>
                                <?php if($key =='7'){ ?>

                                <tr>
                                    <td style="width: 50%; text-align: center;"><b>Воскресенье:</b></td>
                                    <td style="width: 50%; text-align: center;"><?php echo $wt['worktimeOpen']; ?> - <?php echo $wt['worktimeClose']; ?></td>
                                </tr>
                                <?php } ?>
                                <?php } ?>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>
                        <div class="tab-pane" id="tab-calc">
                            <div class="alert alert-info" align="center" role="alert">Настройки калькуляции доставки</div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-5post-rateType">Тип тарифного плана, которым будет производиться расчет стоимости доставки:</label>
                                <div class="col-sm-3">
                                    <select name="shipping_5post_rateType" id="input-5post-rateType" class="form-control">
                                        <?php foreach ($rateType_list as $rate) { ?>
                                        <?php if ($rate['value'] == $shipping_5post_rateType) { ?>
                                        <option value="<?php echo $rate['value']; ?>" selected="selected"><?php echo $rate['name']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $rate['value']; ?>"><?php echo $rate['name']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="sh-flex">
                                    <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="Максимальный вес заказа, при котором расчет идет по базовому тарифу. Данные нужно взять из договора с 5post.">Базовый тариф, кг:</span></label>
                                    <div class="col-sm-3">
                                        <input type="number" name="shipping_5post_baseRate" value="<?php echo $shipping_5post_baseRate; ?>" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="sh-flex">
                                    <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="Шаг перевеса, за который идет надбавка к стоимости доставки. Данные нужно взять из договора с 5post.">Шаг перевеса, кг:</span></label>
                                    <div class="col-sm-3">
                                        <input type="number" name="shipping_5post_overweight" value="<?php echo $shipping_5post_overweight; ?>" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="sh-flex">
                                    <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="Уникальный номер партнера в системе 5Post. Требуется для генерации трэкномера. Запрашивается у менеджера 5Post.">Номер партнера:</span></label>
                                    <div class="col-sm-3">
                                        <input type="number" name="shipping_5post_numberPartner" value="<?php echo $shipping_5post_numberPartner; ?>" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="sh-flex">
                                    <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="Вы можете установить наценку на стоимость доставки, выбрав в типе наценки фиксированную сумму в рублях либо процент от стоимости доставки.">Наценка:</span></label>
                                    <div class="col-sm-3">
                                        <input type="number" min="1" name="shipping_5post_markup" id="markup" value="<?php echo $shipping_5post_markup; ?>" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-markup-type">Тип наценки:</label>
                                <div class="col-sm-3">
                                    <select name="shipping_5post_markup_type" id="input-markup-type" class="form-control">
                                        <?php if ($shipping_5post_markup_type) { ?>
                                        <option value="1" selected="selected">Процент (%)</option>
                                        <option value="0">Фиксированная</option>
                                        <?php } else { ?>
                                        <option value="1">Процент (%)</option>
                                        <option value="0" selected="selected">Фиксированная</option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-pvz"><span data-toggle="tooltip" title="Модуль не даст оформить заказ, если не выбрана точка выдачи.">Не давать оформить заказ без выбранной точки выдачи:</span></label>
                                <div class="col-sm-4">
                                    <div class="checkbox">
                                        <label> <?php if ($shipping_5post_pvz) { ?>
                                            <input type="checkbox" id="input-pvz" name="shipping_5post_pvz" checked="checked" />
                                            <?php } else { ?>
                                            <input type="checkbox" id="input-pvz" name="shipping_5post_pvz" />
                                            <?php } ?> </label>
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-info" align="center" role="alert">Настройки соответствия платежных систем</div>
                            <div class="alert alert-warning" style="color:black;" role="alert">
                                Настройка предназначена для корректности учета способа оплаты, выбранного покупателем. Необходимо указать модулю, какие именно платежные системы, установленные на сайте, считаются оплатой наличными на пункте выдачи, а какие - оплатой картой на пункте выдачи. Все платежные системы, которые не отмечены, модуль считает предоплатой.
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="cash-payment">
                                    Оплата наличными при получении заказа на ПВЗ, в постамате или на кассе магазина 5Post:
                                </label>
                                <div class="col-sm-3">
                                    <select name="shipping_5post_cash_payment[]" multiple class="form-control" style="height: 70px !important;" id="cash-payment">
                                        <option></option>
                                        <?php foreach ($payment_methods as $payment) { ?>
                                        <?php if($payment['code']){ ?>

                                        <option value="<?php echo $payment['code']; ?>" selected="selected"><?php echo $payment['title']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $payment['code']; ?>"><?php echo $payment['title']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="card-payment">
                                    Оплата картой при получении заказа на ПВЗ, в постамате или на кассе магазина 5Post:
                                </label>
                                <div class="col-sm-3">
                                    <select name="shipping_5post_card_payment[]" multiple class="form-control" style="height: 70px !important;" id="card-payment">
                                        <option></option>
                                        <?php foreach ($payment_methods as $payment) { ?>
                                        <?php if($payment['code']){ ?>

                                        <option value="<?php echo $payment['code']; ?>" selected="selected"><?php echo $payment['title']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $payment['code']; ?>"><?php echo $payment['title']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-status">
                            <div class="alert alert-warning" style="color:black;" role="alert">
                                Вы можете настроить сопоставление статусов заказа на сайте со статусами доставки  5POST. При изменении статуса доставки  заказы на сайте автоматически перейдут в выбранный вами статус.
                                Это позволит оперативно отслеживать статус заказа интернет-магазина.
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-status-5">Заявка отправлена:</label>
                                <div class="col-sm-4">
                                    <select name="shipping_5post_status_new" id="input-status-5" class="form-control">
                                        <option value="non"></option>
                                        <?php foreach ($order_statuses as $status) { ?>
                                        <?php if ($status['order_status_id'] == $shipping_5post_status_new) { ?>
                                        <option value="<?php echo $status['order_status_id']; ?>" selected="selected"><?php echo $status['name']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $status['order_status_id']; ?>"><?php echo $status['name']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-status-5">Заявка одобрена:</label>
                                <div class="col-sm-4">
                                    <select name="shipping_5post_status_valid" id="input-status-5" class="form-control">
                                        <option value="non"></option>
                                        <?php foreach ($order_statuses as $status) { ?>
                                        <?php if ($status['order_status_id'] == $shipping_5post_status_valid) { ?>
                                        <option value="<?php echo $status['order_status_id']; ?>" selected="selected"><?php echo $status['name']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $status['order_status_id']; ?>"><?php echo $status['name']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-status-5">Ошибка в обработке заказа (отклонен):</label>
                                <div class="col-sm-4">
                                    <select name="shipping_5post_status_rejected" id="input-status-5" class="form-control">
                                        <option value="non"></option>
                                        <?php foreach ($order_statuses as $status) { ?>
                                        <?php if ($status['order_status_id'] == $shipping_5post_status_rejected) { ?>
                                        <option value="<?php echo $status['order_status_id']; ?>" selected="selected"><?php echo $status['name']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $status['order_status_id']; ?>"><?php echo $status['name']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-status-5">Заказ на складе 5Post:</label>
                                <div class="col-sm-4">
                                    <select name="shipping_5post_status_warehouse" id="input-status-5" class="form-control">
                                        <option value="non"></option>
                                        <?php foreach ($order_statuses as $status) { ?>
                                        <?php if ($status['order_status_id'] == $shipping_5post_status_warehouse) { ?>
                                        <option value="<?php echo $status['order_status_id']; ?>" selected="selected"><?php echo $status['name']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $status['order_status_id']; ?>"><?php echo $status['name']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-status-5">Заказ в ячейке постамата	:</label>
                                <div class="col-sm-4">
                                    <select name="shipping_5post_status_inpostamat" id="input-status-5" class="form-control">
                                        <option value="non"></option>
                                        <?php foreach ($order_statuses as $status) { ?>
                                        <?php if ($status['order_status_id'] == $shipping_5post_status_inpostamat) { ?>
                                        <option value="<?php echo $status['order_status_id']; ?>" selected="selected"><?php echo $status['name']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $status['order_status_id']; ?>"><?php echo $status['name']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-status-5">Ошибка в обработке заказа (исполнение прервано):</label>
                                <div class="col-sm-4">
                                    <select name="shipping_5post_status_interrupted" id="input-status-5" class="form-control">
                                        <option value="non"></option>
                                        <?php foreach ($order_statuses as $status) { ?>
                                        <?php if ($status['order_status_id'] == $shipping_5post_status_interrupted) { ?>
                                        <option value="<?php echo $status['order_status_id']; ?>" selected="selected"><?php echo $status['name']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $status['order_status_id']; ?>"><?php echo $status['name']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-status-5">Посылка утеряна:</label>
                                <div class="col-sm-4">
                                    <select name="shipping_5post_status_lost" id="input-status-5" class="form-control">
                                        <option value="non"></option>
                                        <?php foreach ($order_statuses as $status) { ?>
                                        <?php if ($status['order_status_id'] == $shipping_5post_status_lost) { ?>
                                        <option value="<?php echo $status['order_status_id']; ?>" selected="selected"><?php echo $status['name']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $status['order_status_id']; ?>"><?php echo $status['name']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-status-5">Заказ в ячейке, срок истек, получение возможно:</label>
                                <div class="col-sm-4">
                                    <select name="shipping_5post_status_reclaim" id="input-status-5" class="form-control">
                                        <option value="non"></option>
                                        <?php foreach ($order_statuses as $status) { ?>
                                        <?php if ($status['order_status_id'] == $shipping_5post_status_reclaim) { ?>
                                        <option value="<?php echo $status['order_status_id']; ?>" selected="selected"><?php echo $status['name']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $status['order_status_id']; ?>"><?php echo $status['name']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-status-5">Заказ готов к повторной выдаче:</label>
                                <div class="col-sm-4">
                                    <select name="shipping_5post_status_repickup" id="input-status-5" class="form-control">
                                        <option value="non"></option>
                                        <?php foreach ($order_statuses as $status) { ?>
                                        <?php if ($status['order_status_id'] == $shipping_5post_status_repickup) { ?>
                                        <option value="<?php echo $status['order_status_id']; ?>" selected="selected"><?php echo $status['name']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $status['order_status_id']; ?>"><?php echo $status['name']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-status-5">Заказ не был востребован:</label>
                                <div class="col-sm-4">
                                    <select name="shipping_5post_status_unclaimed" id="input-status-5" class="form-control">
                                        <option value="non"></option>
                                        <?php foreach ($order_statuses as $status) { ?>
                                        <?php if ($status['order_status_id'] == $shipping_5post_status_unclaimed) { ?>
                                        <option value="<?php echo $status['order_status_id']; ?>" selected="selected"><?php echo $status['name']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $status['order_status_id']; ?>"><?php echo $status['name']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-status-5">Заказ получен покупателем:</label>
                                <div class="col-sm-4">
                                    <select name="shipping_5post_status_done" id="input-status-5" class="form-control">
                                        <option value="non"></option>
                                        <?php foreach ($order_statuses as $status) { ?>
                                        <?php if ($status['order_status_id'] == $shipping_5post_status_done) { ?>
                                        <option value="<?php echo $status['order_status_id']; ?>" selected="selected"><?php echo $status['name']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $status['order_status_id']; ?>"><?php echo $status['name']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-status-5">Заказ отменен:</label>
                                <div class="col-sm-4">
                                    <select name="shipping_5post_status_canceled" id="input-status-5" class="form-control">
                                        <option value="non"></option>
                                        <?php foreach ($order_statuses as $status) { ?>
                                        <?php if ($status['order_status_id'] == $shipping_5post_status_canceled) { ?>
                                        <option value="<?php echo $status['order_status_id']; ?>" selected="selected"><?php echo $status['name']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $status['order_status_id']; ?>"><?php echo $status['name']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-faq">
                            <div class="alert alert-info" align="center" role="alert">О модуле</div>
                            <a href="javascript:void(0);" style="font-size:15px;" id="moduleFor">- Для чего нужен модуль</a>
                            <div id="moduleForContent" class="faq-content" style="display:none; margin: 7px;">
                                Модуль обеспечивает интеграцию Интернет-магазина со службой доставки <a href="https://fivepost.ru/" target="_blank">5Post</a>. Обеспечивается отправка заявок на доставку заказов, мониторинг статусов доставки заказов и выставление соответствующих им статусов в админке Opencart. В модуле присутствует функционал печати наклеек со штрихкодами для заказов. Стоимость доставки вычисляется с помощью данных о точках и тарифах, полученных от 5Post.
                            </div>
                            <br />
                            <a href="javascript:void(0);" style="font-size:15px;" id="moduleWork">- Как работает модуль</a>
                            <div id="moduleWorkContent" class="faq-content" style="display:none; margin: 7px;">
                                Состав модуля:
                                <ul>
                                    <li>функционал службы доставки 5post;</li>
                                    <li>функционал расчета габаритов заказа;</li>
                                    <li>функционал расчета стоимости доставки;</li>
                                    <li>функционал отображения информации о пунктах выдачи заказов и постаматах;</li>
                                    <li>функционал оформления заявки на доставку;</li>
                                    <li>функционал скачивания наклеек;</li>
                                    <li>функционал синхронизации местоположений сайта с базой городов 5Post;</li>
                                    <li>база данных с отосланными заявками;</li>
                                    <li>прочий функционал</li>
                                </ul>
                                <p>Модуль устанавливает новую службу доставки «Интеграция с 5post», которую можно добавить на сайт. У службы есть один профиль: самовывоз. Он будет отображаться на странице оформления заказа, если в выбранном покупателем городе доставки возможна доставка в один из пунктов выдачи заказов. Возможность доставки по выбранному профилю, стоимость и сроки рассчитываются модулем на основании данных по точкам, полученным от 5Post.</p>
                                <p>Заявка на доставку составляется для каждого заказа в отдельности, причем контроль за корректностью введенных данных возлагается на пользователя. При сохранении данные о заявке сохраняются в базу данных. При отправке заявки модуль формирует json-документ согласно документации 5Post и отправляет его на сервер. Результат обработки заявки приходит сразу же, выдавая либо ошибку, либо информацию об успешном принятии заявки. Обновление информации происходит через планировщик задач (cron) на вашем сервере или хостинге. Получив ответ, модуль анализирует его и обновляет статусы заявок в зависимости от результатов их обработки, а так же выставляет статусы соответствующим заказам(подробнее смотрите пункт "Отслеживание статусов").</p>
                                <p><span style="color:red;">Важно!</span> Данный модуль разработан компанией, специализирующейся на разработке модулей доставки, но не являющейся представителем 5Post, поэтому мы не можем ответить на вопросы касательно работы сервиса 5Post.</p>
                            </div>
                            <div class="alert alert-info" style="margin:10px 0px;" align="center" role="alert">Начало работы</div>
                            <a href="javascript:void(0);" style="font-size:15px;" id="moduleSettings">- Настройка службы доставки</a>
                            <div id="moduleSettingsContent" class="faq-content" style="display:none; margin: 7px;">
                                <p>До начала работы вам необходимо получить данные для работы с  модулем (apiKey и другие), для этого нужно обратиться к своему менеджеру 5Post  либо связаться через форму обратной связи на сайте: <a href="https://fivepost.ru/become-partner/#s0" target="_blank">https://fivepost.ru/become-partner/#s0</a></p>
                                <p>Не стоит пугаться большого количества оповещений, которые отображаются в опциях ненастроенного модуля. Вы уже на верном пути, если обратились к FAQ.</p>
                                <p><b>Настройка состоит из следующих шагов:</b></p>
                                <ul>
                                    <li>Заполнение настроек;</li>
                                    <li>Заполнение опций модуля необходимо вести согласно блокам документации, расположенным рядом с группами настроек. Наведите на иконку со знаком вопроса для получения подробной информации об опции. Особое внимание обратите на поля, отмеченные красной звездочкой – они являются обязательными;</li>
                                    <li>Выполнить импорт местоположений. Для того, чтобы сделать импорт местоположений, нажмите на кнопку в верхней панели в административной части модуля;
                                        <img src="/../image/catalog/5post/faq/import.png"  alt="Импорт местоположений" title="Импорт местоположений">
                                    </li>
                                </ul>
                                <p>В случае возникновения ошибок можно попробовать их проигнорировать и продолжить импорт местоположений обновив страницу.</p>
                                <p>После этого  появятся дополнительные вкладки настройки.  Заполните поля настроек на вкладках, обратив особое внимание на вкладку Склады.</p>
                                <p>Ограничения по весу заказа учитываются самим модулем при расчете служб доставки. Данные о габаритах и весе товара берутся из штатных параметров Торгового каталога или из габаритов по умолчанию. Если модуль некорректно обрабатывает вес заказа - проверьте в первую очередь настройки торгового каталога в товаре.</p>
                            </div>
                            <br />
                            <a href="javascript:void(0);" style="font-size:15px;" id="modulePayment">- Настройки соответствия платежных систем</a>
                            <div id="modulePaymentContent" class="faq-content" style="display:none; margin: 7px;">
                                <p>Настройка предназначена для корректности учета способа оплаты, выбранного покупателем (клиентом сайта).</br>
                                    Необходимо указать какие именно платежные системы, установленные на сайте, считаются оплатой наличными, а какие - оплатой картой при получении клиентом сайта заказа у курьера, на пункте самовывоза, либо в постамате. Иными словами, при выборе каких платежных систем считается, что оплата заказа производится наложенным платежом при получении.</p>
                                <p>Если имеются платежные системы, не подразумевающие наложенный платеж: оплата пластиковой картой на сайте, выставление счета, банковский перевод и т.д. - не отмечайте их в этих селекторах! Все это предоплатные платежные системы, подразумевающие оплату клиентом сайта напрямую интернет-магазину, без приема оплаты заказа наложенным платежом.</p>
                            </div>
                            <br />
                            <a href="javascript:void(0);" style="font-size:15px;" id="moduleOrder">- Оформление и отправка заявки</a>
                            <div id="moduleOrderContent" class="faq-content" style="display:none; margin: 7px;">
                                <p>Для открытия формы отправки необходимо воспользоваться кнопкой "5post" в шапке на странице списка заказов.</p>
                                <p>В открывшемся окне необходимо заполнить данные заявки. Модуль проверит заполненность необходимых полей. По умолчанию поля будут заполнены свойствами заказа согласно указаниям в настройках.</p>
                                <p>Если заявка готова к отправке - нажмите кнопку "Отправить". После оповещения, что заявка сохранена, можно закрыть окно. Если при отправке возникнут ошибки, их можно просмотреть в этом же окне.</p>
                            </div>
                            <br />
                            <a href="javascript:void(0);" style="font-size:15px;" id="moduleStatus">- Отслеживание статусов</a>
                            <div id="moduleStatusContent" class="faq-content" style="display:none; margin: 7px;">
                                <p><b>Таблица заказов</b></p>
                                <p>Таблица заказов находится в разделе "Продажи" -> "Заказы". На этой странице можно ознакомиться с состояниями всех имеющихся заявок, с возможностью их фильтрации и сортировки.</p>
                                <p>Здесь принятые заказы можно отозвать и удалить, распечатать наклейку, проверить статус. В случае успешной отправки заявки все эти действия можно производить и из окна оформления заявки на странице конкретного заказа.</p>
                                <p><b>Обновление информации о заявке</b></p>
                                <p>Обновление информации происходит через планировщик задач (cron) на вашем сервере или хостинге.</p>
                                <p>Пример планировщика для возможности отслеживания и обновления статусов посылок /usr/bin/GET https://ВашСайт/index.php?route=extension/shipping/5post/getStatuses, для местоположений /usr/bin/GET https://ВашСайт/index.php?route=extension/shipping/5post/importDel .
                                    </br>Рекомендуемая частота обновлений - не реже раза в сутки.</p>
                                <p><b>Получение наклейки</b></p>
                                <p>Если заявка имеет статус "Заявка отправлена" и выше - заявка принята и можно получить с сервера 5post файл наклейки по заказу для распечатки в форме отправки заявок.</p>

                            </div>
                            <div class="alert alert-info" style="margin:10px 0px;" align="center" role="alert">Справочная информация</div>
                            <a href="javascript:void(0);" style="font-size:15px;" id="moduleTest">- Тестовый аккаунт</a>
                            <div id="moduleTestContent" class="faq-content" style="display:none; margin: 7px 5px 0px 7px;">
                                <p>Модуль поддерживает работу с тестовым контуром: вы можете авторизоваться с тестовыми доступами, чтобы проверить его работу. Учтите несколько важных моментов:</p>
                                <ul>
                                    <li>Если вы планируете отправить какие-то текущие заказы в 5post, это следует сделать до смены доступов. Доступные варианты доставки, склады отправки отправлений и непосредственно тарификация стоимости доставки могут отличаться от аккаунта к аккаунту - поэтому в случае перелогинивания на другой необходимо заново запустить импорт, а также выбрать желаемый склад из доступных вариантов.</li>
                                    <li>Поэтому в случае перелогинивания на другой аккаунт- нужно ввести новый api key и еще раз провести работы, указанные в блоке настройка модуля - настроить модуль и провести импорт местоположений.</li>
                                </ul>
                            </div>
                            <br />
                            <a href="javascript:void(0);" style="font-size:15px;" id="moduleCalculate">- Особенности расчета стоимости доставки</a>
                            <div id="moduleCalculateContent" class="faq-content" style="display:none; margin: 7px;">
                                <p>Стоимость доставки рассчитывается с помощью данных о тарифных планах точки 5Post, она же отображается покупателю при оформлении заказа.</p>
                                <p>Стоимость доставки зависит от габаритов заказа: его размеров и веса. Если в заказе несколько товаров - модуль считает их единой коробкой и выводит стоимость доставки для этой упаковки.</p>
                                <p>Габариты и вес, для которых рассчитывается доставка, можно увидеть на странице заказов (в админке), нажав на кнопку "5Post" и щелкнув в открывшемся окне по заголовку "Габариты заказа".</p>
                                <p>Если в заказе присутствуют товары с неуказанными габаритами или весом, - то расчет изначально производится без их учета. Для расчета стоимости доставки принимается максимальное значение из рассчитанных габаритов или веса и значения по умолчанию. Таким образом, причина того, что заказ в модуле весит больше, чем на сайте, в том, что в составе этого заказа есть товар с неуказанными габаритами.</p>
                            </div>
                            <br />
                            <a href="javascript:void(0);" style="font-size:15px;" id="moduleProblems">- Частые проблемы</a>
                            <div id="moduleProblemsContent" class="faq-content" style="display:none; margin: 7px;">
                                <b>Расчет не совпадает с личным кабинетом.</b>
                                <p>Внимательно ознакомьтесь с пунктом FAQ "Особенности расчета стоимости доставки": в нем детально расписано, как считается вес и габариты товара. Убедитесь, что вы работаете с боевыми доступами.</p>
                                <b>Доставка не считается.</b>
                                <p>Убедитесь, что добавлен склад отправки в настройках модуля. Обновите кэш модификаторов и проверьте журнал ошибок.</p>
                                <b>Служба доставки не отображается.</b>
                                <ul>
                                    <li>Убедитесь, что вы ввели верные данные авторизации в модуле.</li>
                                    <li>Убедитесь, что импорт был выполнен.</li>
                                    <li>Убедитесь, что в настройках модуля добавлен склад отправки.</li>
                                    <li>Проверьте включена ли доставка.</li>
                                </ul>
                                <b>Не отображается кнопка "5post" для оформления заявки.</b>
                                <p>Убедитесь, что вы ввели верные данные авторизации в модуле и Обновите кэш модификаторов.</p>
                                <b>Не отсылается заявка.</b>
                                <ul>
                                    <li>Убедитесь, что исправлены все возможные ошибки в полях (формат телефона верный, заполнены все необходимые поля, определен склад).</li>
                                    <li>Удалите (замените) из полей символы кавычек, угловые скобки, и т.п.</li>
                                </ul>
                                <b>Заявка отправилась, но не появилась в ЛК.</b>
                                <ul>
                                    <li>Убедитесь, что сервер 5post доступен (после очистки кэша продолжают отображаться в оформлении заказа), иначе - нужно ждать, пока сервер не "поднимется".</li>
                                    <li>Убедитесь, что заявка была отправлена в боевом режиме и с корректного аккаунта.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="warehousesModal" tabindex="-1" role="dialog" aria-labelledby="warehousesModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 style="float:left">Добавление склада</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning" align="center" role="alert">*Все поля обязательны для заполнения!</div>
                <form id="form-wh" class="form-horizontal">
                    <div class="form-group required">
                        <label for="warehouse-name" class="col-sm-4 control-label">Наименование склада:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="warehouse[name]" id="warehouse-name">
                        </div>
                    </div>
                    <div class="form-group required">
                        <label for="warehouse-partner" class="col-sm-4 control-label">ID расположения в системе партнера:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="warehouse[partnerId]" id="warehouse-partner">
                        </div>
                    </div>
                    <div class="form-group required">
                        <label class="col-sm-4 control-label" for="input-region">Регион:</label>
                        <div class="col-sm-6">
                            <select name="warehouse[region]" id="input-region" class="form-control">
                                <?php foreach($wh_regions as $key=> $region) { ?>

                                <option value="<?php echo $key; ?>"><?php echo $region; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group required">
                        <label class="col-sm-4 control-label" for="input-federaldistrict">Наименование области:</label>
                        <div class="col-sm-6">
                            <select name="warehouse[federaldistrict]" id="input-federaldistrict" class="form-control">
                                <?php foreach($wh_federaldistrict as $key=> $federaldistrict) { ?>

                                <option value="<?php echo $key; ?>"><?php echo $federaldistrict; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group required">
                        <label for="warehouse-zip" class="col-sm-4 control-label">Почтовый индекс:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="warehouse[zip]" id="warehouse-zip">
                        </div>
                    </div>
                    <div class="form-group required">
                        <label for="warehouse-city" class="col-sm-4 control-label">Наименование города:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="warehouse[city]" id="warehouse-city">
                        </div>
                    </div>
                    <div class="form-group required">
                        <label for="warehouse-street" class="col-sm-4 control-label">Наименование улицы:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="warehouse[street]" id="warehouse-street">
                        </div>
                    </div>
                    <div class="form-group required">
                        <label for="warehouse-house" class="col-sm-4 control-label">Номер дома:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="warehouse[house]" id="warehouse-house">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="warehouse-coordsX" class="col-sm-4 control-label">Географические координаты:</label>
                        <div class="col-sm-2" style="width: 21%;">
                            <input type="text" class="form-control" name="warehouse[coordsX]" id="warehouse-coordsX">
                        </div>
                        <label for="warehouse-coordsY" class="col-sm-1 control-label" style="padding-left: 0px; padding-right: 0px; width: 2%; ">X</label>
                        <div class="col-sm-2" style="width: 21%;">
                            <input type="text" class="form-control" name="warehouse[coordsY]" id="warehouse-coordsY">
                        </div>
                    </div>
                    <div class="form-group required">
                        <label for="warehouse-phone" class="col-sm-4 control-label">Контактный телефон:</label>
                        <div class="col-sm-6">
                            <input type="numb" class="form-control" placeholder="+7XXXXXXXXXX" name="warehouse[phone]" id="warehouse-phone">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="input-timezone">Часовой пояс:</label>
                        <div class="col-sm-3">
                            <select name="warehouse[timezone]" id="input-timezone" class="form-control">
                                <?php foreach ($wh_timezone as $timezone) { ?>
                                <option value="<?php echo $timezone; ?>"><?php echo $timezone; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="alert alert-info" align="center" role="alert">Расписание работы</div>
                    <div class="form-group" id="wt1">
                        <label for="warehouse-worktime" class="col-sm-4 control-label">Понедельник:</label>
                        <div class="col-sm-2" style="width: 21%;">
                            <select name="warehouse[worktime1open]" id="input-worktime1open" class="form-control">
                                <?php foreach ($wh_worktime as $worktime) { ?>
                                <option value="<?php echo $worktime; ?>"><?php echo $worktime; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <label for="warehouse-worktime" class="col-sm-1 control-label" style="padding-left: 0px; padding-right: 0px; width: 1%; ">-</label>
                        <div class="col-sm-2" style="width: 21%;">
                            <select name="warehouse[worktime1close]" id="input-worktime1close" class="form-control">
                                <?php foreach ($wh_worktime as $worktime) { ?>
                                <option value="<?php echo $worktime; ?>"><?php echo $worktime; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <label id="wt-bt1" class="col-sm-1 control-label" style="padding-left: 0px; padding-right: 0px; width: 1%; color:red; cursor:pointer;">X</label>
                    </div>
                    <div class="form-group" id="wt2">
                        <label for="warehouse-worktime" class="col-sm-4 control-label">Вторник:</label>
                        <div class="col-sm-2" style="width: 21%;">
                            <select name="warehouse[worktime2open]" id="input-worktime2open" class="form-control">
                                <?php foreach ($wh_worktime as $worktime) { ?>
                                <option value="<?php echo $worktime; ?>"><?php echo $worktime; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <label for="warehouse-worktime" class="col-sm-1 control-label" style="padding-left: 0px; padding-right: 0px; width: 1%; ">-</label>
                        <div class="col-sm-2" style="width: 21%;">
                            <select name="warehouse[worktime2close]" id="input-worktime2close" class="form-control">
                                <?php foreach ($wh_worktime as $worktime) { ?>
                                <option value="<?php echo $worktime; ?>"><?php echo $worktime; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <label id="wt-bt2" class="col-sm-1 control-label" style="padding-left: 0px; padding-right: 0px; width: 1%; color:red; cursor:pointer;">X</label>
                    </div>
                    <div class="form-group" id="wt3">
                        <label for="warehouse-worktime" class="col-sm-4 control-label">Среда:</label>
                        <div class="col-sm-2" style="width: 21%;">
                            <select name="warehouse[worktime3open]" id="input-worktime3open" class="form-control">
                                <?php foreach ($wh_worktime as $worktime) { ?>
                                <option value="<?php echo $worktime; ?>"><?php echo $worktime; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <label for="warehouse-worktime" class="col-sm-1 control-label" style="padding-left: 0px; padding-right: 0px; width: 1%; ">-</label>
                        <div class="col-sm-2" style="width: 21%;">
                            <select name="warehouse[worktime3close]" id="input-worktime3close" class="form-control">
                                <?php foreach ($wh_worktime as $worktime) { ?>
                                <option value="<?php echo $worktime; ?>"><?php echo $worktime; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <label id="wt-bt3" class="col-sm-1 control-label" style="padding-left: 0px; padding-right: 0px; width: 1%; color:red; cursor:pointer;">X</label>
                    </div>
                    <div class="form-group" id="wt4">
                        <label for="warehouse-worktime" class="col-sm-4 control-label">Четверг:</label>
                        <div class="col-sm-2" style="width: 21%;">
                            <select name="warehouse[worktime4open]" id="input-worktime4open" class="form-control">
                                <?php foreach ($wh_worktime as $worktime) { ?>
                                <option value="<?php echo $worktime; ?>"><?php echo $worktime; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <label for="warehouse-coordsY" class="col-sm-1 control-label" style="padding-left: 0px; padding-right: 0px; width: 1%; ">-</label>
                        <div class="col-sm-2" style="width: 21%;">
                            <select name="warehouse[worktime4close]" id="input-worktime4close" class="form-control">
                                <?php foreach ($wh_worktime as $worktime) { ?>
                                <option value="<?php echo $worktime; ?>"><?php echo $worktime; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <label id="wt-bt4" class="col-sm-1 control-label" style="padding-left: 0px; padding-right: 0px; width: 1%; color:red; cursor:pointer;">X</label>
                    </div>
                    <div class="form-group" id="wt5">
                        <label for="warehouse-worktime" class="col-sm-4 control-label">Пятница:</label>
                        <div class="col-sm-2" style="width: 21%;">
                            <select name="warehouse[worktime5open]" id="input-worktime5open" class="form-control">
                                <?php foreach ($wh_worktime as $worktime) { ?>
                                <option value="<?php echo $worktime; ?>"><?php echo $worktime; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <label for="warehouse-worktime" class="col-sm-1 control-label" style="padding-left: 0px; padding-right: 0px; width: 1%; ">-</label>
                        <div class="col-sm-2" style="width: 21%;">
                            <select name="warehouse[worktime5close]" id="input-worktime5close" class="form-control">
                                <?php foreach ($wh_worktime as $worktime) { ?>
                                <option value="<?php echo $worktime; ?>"><?php echo $worktime; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <label id="wt-bt5" class="col-sm-1 control-label" style="padding-left: 0px; padding-right: 0px; width: 1%; color:red; cursor:pointer;">X</label>
                    </div>
                    <div class="form-group" id="wt6">
                        <label for="warehouse-worktime" class="col-sm-4 control-label">Суббота:</label>
                        <div class="col-sm-2" style="width: 21%;">
                            <select name="warehouse[worktime6open]" id="input-worktime6open" class="form-control">
                                <?php foreach ($wh_worktime as $worktime) { ?>
                                <option value="<?php echo $worktime; ?>"><?php echo $worktime; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <label for="warehouse-worktime" class="col-sm-1 control-label" style="padding-left: 0px; padding-right: 0px; width: 1%; ">-</label>
                        <div class="col-sm-2" style="width: 21%;">
                            <select name="warehouse[worktime6close]" id="input-worktime6close" class="form-control">
                                <?php foreach ($wh_worktime as $worktime) { ?>
                                <option value="<?php echo $worktime; ?>"><?php echo $worktime; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <label id="wt-bt6" class="col-sm-1 control-label" style="padding-left: 0px; padding-right: 0px; width: 1%; color:red; cursor:pointer;">X</label>
                    </div>
                    <div class="form-group" id="wt7">
                        <label for="warehouse-worktime" class="col-sm-4 control-label">Воскресенье:</label>
                        <div class="col-sm-2" style="width: 21%;">
                            <select name="warehouse[worktime7open]" id="input-worktime7open" class="form-control">
                                <?php foreach ($wh_worktime as $worktime) { ?>
                                <option value="<?php echo $worktime; ?>"><?php echo $worktime; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <label for="warehouse-worktime" class="col-sm-1 control-label" style="padding-left: 0px; padding-right: 0px; width: 1%; ">-</label>
                        <div class="col-sm-2" style="width: 21%;">
                            <select name="warehouse[worktime7close]" id="input-worktime7close" class="form-control">
                                <?php foreach ($wh_worktime as $worktime) { ?>
                                <option value="<?php echo $worktime; ?>"><?php echo $worktime; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <label id="wt-bt7" class="col-sm-1 control-label" style="padding-left: 0px; padding-right: 0px; width: 1%; color:red; cursor:pointer;">X</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="button-wh" class="btn btn-primary">Создать</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
	$(document).ready(function(){
        $('#sort-order').keypress(function(e) {
            return e.which < 44 || e.which > 46;
        });
    });

    $(document).ready(function(){
        $('#increase').keypress(function(e) {
            return e.which < 44 || e.which > 48;
        });
    });

    $(document).ready(function(){
        $('#markup').keypress(function(e) {
            return e.which < 44 || e.which > 46;
        });
    });

    $('#button-wh').click(function(){
        $.ajax({
            url: 'index.php?route=extension/shipping/5post/generateWarehouseListCollection&token=<?php echo $token; ?>',
            type: 'post',
            data: $('#form-wh').serialize(),
            dataType: 'json',
            success: function(json) {
                if(json.error){
                    alert(json.error);
                }

                if(json.success){
                    alert('Склад успешно создан!');
                    $("#warehousesModal .close").click()
                }
            }
        });
    });

    $('#wt-bt1').click(function(){
        if (typeof $('#input-worktime1open').attr('disabled') == 'undefined'){
            $('#input-worktime1open').attr('disabled', true);
        }else{
            $('#input-worktime1open').attr('disabled', false);
        }

        if (typeof $('#input-worktime1close').attr('disabled') == 'undefined'){
            $('#input-worktime1close').attr('disabled', true);
        }else{
            $('#input-worktime1close').attr('disabled', false);
        }
    });

    $('#wt-bt2').click(function(){
        if (typeof $('#input-worktime2open').attr('disabled') == 'undefined'){
            $('#input-worktime2open').attr('disabled', true);
        }else{
            $('#input-worktime2open').attr('disabled', false);
        }

        if (typeof $('#input-worktime2close').attr('disabled') == 'undefined'){
            $('#input-worktime2close').attr('disabled', true);
        }else{
            $('#input-worktime2close').attr('disabled', false);
        }
    });

    $('#wt-bt3').click(function(){
        if (typeof $('#input-worktime3open').attr('disabled') == 'undefined'){
            $('#input-worktime3open').attr('disabled', true);
        }else{
            $('#input-worktime3open').attr('disabled', false);
        }

        if (typeof $('#input-worktime3close').attr('disabled') == 'undefined'){
            $('#input-worktime3close').attr('disabled', true);
        }else{
            $('#input-worktime3close').attr('disabled', false);
        }
    });

    $('#wt-bt4').click(function(){
        if (typeof $('#input-worktime4open').attr('disabled') == 'undefined'){
            $('#input-worktime4open').attr('disabled', true);
        }else{
            $('#input-worktime4open').attr('disabled', false);
        }

        if (typeof $('#input-worktime4close').attr('disabled') == 'undefined'){
            $('#input-worktime4close').attr('disabled', true);
        }else{
            $('#input-worktime4close').attr('disabled', false);
        }
    });

    $('#wt-bt5').click(function(){
        if (typeof $('#input-worktime5open').attr('disabled') == 'undefined'){
            $('#input-worktime5open').attr('disabled', true);
        }else{
            $('#input-worktime5open').attr('disabled', false);
        }

        if (typeof $('#input-worktime5close').attr('disabled') == 'undefined'){
            $('#input-worktime5close').attr('disabled', true);
        }else{
            $('#input-worktime5close').attr('disabled', false);
        }
    });

    $('#wt-bt6').click(function(){
        if (typeof $('#input-worktime6open').attr('disabled') == 'undefined'){
            $('#input-worktime6open').attr('disabled', true);
        }else{
            $('#input-worktime6open').attr('disabled', false);
        }

        if (typeof $('#input-worktime6close').attr('disabled') == 'undefined'){
            $('#input-worktime6close').attr('disabled', true);
        }else{
            $('#input-worktime6close').attr('disabled', false);
        }
    });

    $('#wt-bt7').click(function(){
        if (typeof $('#input-worktime7open').attr('disabled') == 'undefined'){
            $('#input-worktime7open').attr('disabled', true);
        }else{
            $('#input-worktime7open').attr('disabled', false);
        }

        if (typeof $('#input-worktime7close').attr('disabled') == 'undefined'){
            $('#input-worktime7close').attr('disabled', true);
        }else{
            $('#input-worktime7close').attr('disabled', false);
        }
    });

    $('#moduleFor').click(function(){
        $('#moduleForContent').toggle();
    });

    $('#moduleWork').click(function(){
        $('#moduleWorkContent').toggle();
    });

    $('#moduleSettings').click(function(){
        $('#moduleSettingsContent').toggle();
    });

    $('#modulePayment').click(function(){
        $('#modulePaymentContent').toggle();
    });

    $('#moduleOrder').click(function(){
        $('#moduleOrderContent').toggle();
    });

    $('#moduleStatus').click(function(){
        $('#moduleStatusContent').toggle();
    });

    $('#moduleTest').click(function(){
        $('#moduleTestContent').toggle();
    });

    $('#moduleCalculate').click(function(){
        $('#moduleCalculateContent').toggle();
    });

    $('#moduleProblems').click(function(){
        $('#moduleProblemsContent').toggle();
    });



</script>
<?php echo $footer; ?>