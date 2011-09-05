<script type="text/javascript" charset="utf-8">
$(document).ready(function() {

    $('.options').tooltip({
        offset: [10, 2],
        position: ['bottom', 'center']
    }).dynamic({ bottom: { direction: 'down', bounce: true } });

    var fecha_ini = '<?php echo $data['fecha_ini']; ?>';
    var fecha_fin = '<?php echo $data['fecha_fin']; ?>';

    var id_cliente = '<?php echo $data['id_cliente']; ?>';
    <?php if( $data['usrtype'] == 'corp' ): ?>
    var id_grupo = '<?php echo $data['id_grupo']; ?>';
    <?php endif; ?>

    var dateChanging = false;

    var csmotypes = ['act', 'rea', 'pen', 'fpo'];

    function matrixload() {

        $(csmotypes).each(function(i) {

            $('#mtx-' + csmotypes[i] + '-data').empty()
            .html('<img src="global/img/preloader/facebook.gif" />');

            $('#mtx-' + csmotypes[i] + '-data').load('/matrix-csmo/' +
                csmotypes[i] + '/' + id_cliente + '/' +
                    fecha_ini + '/' + fecha_fin);
        });
    }

    $('#csmo-type-container input[type=checkbox]').change(function() {
        if ($(this).is(':checked')) {
            $(this).next('label').addClass('checked');
            $('#mtx-' + $(this).val()).show();
        } else {
            $(this).next('label').removeClass('checked');
            $('#mtx-' + $(this).val()).hide();
        }
    });

    $('#date-slider-range')

    .dateRangeSlider({
        bounds:{
            min: new Date(fecha_ini),
            max: new Date(fecha_fin)
        }
    })

    .bind('valuesChanged', function(event, ui) {

        fecha_ini = $.datepicker.formatDate('yy-mm-dd', ui.values.min);
        fecha_fin = $.datepicker.formatDate('yy-mm-dd', ui.values.max);

        if (!dateChanging) {
            if ((ui.values.min instanceof Date) && (ui.values.max instanceof Date)) {
                matrixload();
            }
        }
    })

    <?php if( $data['usrtype'] == 'corp' ): ?>
    $('.options').click(function() {
        id_cliente = $(this).text().trim();
        matrixload();
    });
    <?php endif; ?>

    matrixload();

    $('#action-btns #items #excel').click(function() {
        var csmos = '';
        $('#csmo-type-container input[type=checkbox]').each(function() {
            if ($(this).is(':checked')) {
                if (csmos != '') {
                    csmos = csmos + ',' + $(this).val();
                } else {
                    csmos = $(this).val();
                }

            }
        });
        if (csmos != '') {
            <?php if( $data['usrtype'] !== 'corp' ): ?>
            var v_url = '/matrix-csmo/excel' + '/' +
                id_cliente + '/' + fecha_ini + '/' + fecha_fin + '/' + csmos;
            <?php else: ?>
            var v_url = '/matrix-csmo/excel' + '/' +
                id_grupo + '/' + fecha_ini + '/' + fecha_fin + '/' + csmos;
            <?php endif; ?>
            $('#report iframe').src(v_url);
        } else {
            //messagebox('Debe elegir por lo menos un tipo de consumo');
            return false;
        }
    });

});
</script>


<div id="title-container">
    <div class="bordes3alt title sombras1alt">matriz de consumos</div>
</div>

<div class="wrapper"><!-- container -->

    <div id="box1">
        <div class="top-bar bordes2alt sombras1alt"><p>tipo de consumo</p></div>
        <div id="csmo-type-container" class="box bordes3alt sombras1alt">
            <fieldset>
                <input id="chkact" type="checkbox" name="chkact" value="act" checked="true">
                <label for="chkact" class="checkbox checked">Activa</label>
            </fieldset>
            <fieldset>
                <input id="chkrea" type="checkbox" name="chkrea" value="rea">
                <label for="chkrea" class="checkbox">Reactiva</label>
            </fieldset>
            <fieldset>
                <input id="chkpen" type="checkbox" name="chkpen" value="pen">
                <label for="chkpen" class="checkbox">Penalizada</label>
            </fieldset>
            <fieldset>
                <input id="chkfpo" type="checkbox" name="chkfpo" value="fpo">
                <label for="chkfpo" class="checkbox">Factor Potencia</label>
            </fieldset>
        </div>
    </div>

    <br clear="all" />

    <div id="box2">
        <div class="top-bar bordes2alt sombras1alt"><p>periodo</p></div>
        <div id="date-slider-range" class="box bordes3alt sombras1alt"></div>
    </div>

    <?php if( $data['usrtype'] == 'corp' ): ?>
    <div class="box-slide">
        <ul id="slides">

            <li id="left-btn" class="boton"><a href="javascript:void(0);"></a></li>

            <li id="num-chek">
                <ul class="listado">

                    <?php foreach($data['clientes'] as $k1=>$v1): ?>
                    <li class="list-item">
                        <!-- /csmo-hist/corp/<?php echo $data['id_grupo']; ?>/<?php echo $v1->id_cliente; ?> -->
                        <a href="javascript:void(0);" class="options"
                            title="<?php echo $v1->nombre_facturacion; ?>">
                            <?php echo $v1->id_cliente; ?>
                        </a>
                        <!--<input type="checkbox" />-->
                    </li>
                    <li class="separator">&nbsp;</li>
                    <?php endforeach; ?>

                </ul>
            </li>

            <!--<li id="todos">
                    <a href="javascript:void(0);" title="todos">TODOS</a>
                    <br /><input type="checkbox" />
                </li>-->

            <li id="right-btn" class="boton"><a href="javascript:void(0);"></a></li>

        </ul>
    </div>
    <?php else: ?>
    <br clear="all" />
    <?php endif; ?>

    <!-- begin matriz energia activa -->
    <div class="grilla" id="mtx-act" style="display: block;">
        <!--<div class="on-off">
            <input type="checkbox" name="activa" id="activa">
            <label for="activa" class="switch"></label>
        </div>-->
        <div class="boxes">
            <div class="top-bar bordes2alt sombras1alt gridtop-bar">
                <p>matriz de energ&iacute;a activa</p>
            </div>
            <div id="mtx-act-data" class="box bordes3alt sombras1alt datagridbox"></div>
        </div>
        <br clear="all" />
    </div>

    <!-- begin matriz energia reactiva -->
    <div class="grilla" id="mtx-rea">
        <!--<div class="on-off">
            <input type="checkbox" id="reactiva">
            <label for="reactiva" class="switch"></label>
        </div>-->
        <div class="boxes">
            <div class="top-bar bordes2alt sombras1alt gridtop-bar">
                <p>matriz de energ&iacute;a reactiva</p>
            </div>
            <div id="mtx-rea-data" class="box bordes3alt sombras1alt datagridbox"></div>
        </div>
        <br clear="all" />
    </div>

    <!-- begin matriz energia penalizada -->
    <div class="grilla" id="mtx-pen">
        <!--<div class="on-off">
            <input type="checkbox" id="penalizada">
            <label for="penalizada" class="switch"></label>
        </div>-->
        <div class="boxes">
            <div class="top-bar bordes2alt sombras1alt gridtop-bar">
                <p>matriz de energ&iacute;a penalizada</p>
            </div>
            <div id="mtx-pen-data" class="box bordes3alt sombras1alt datagridbox"></div>
        </div>
        <br clear="all" />
    </div>

    <!-- begin matriz factor de potencia -->
    <div class="grilla" id="mtx-fpo">
        <!--<div class="on-off">
            <input type="checkbox" id="fpotencia">
            <label for="fpotencia" class="switch"></label>
        </div>-->
        <div class="boxes">
            <div class="top-bar bordes2alt sombras1alt gridtop-bar">
                <p>matriz de factor de potencia</p>
            </div>
            <div id="mtx-fpo-data" class="box bordes3alt sombras1alt datagridbox"></div>
        </div>
        <br clear="all" />
    </div>

</div>

<div id="action-btns">
    <ul id="items">
        <li class="action-item" id="excel"><a href="javascript:void(0);"></a></li>
        <!--<li class="action-item" id="pdf"><a href="javascript:void(0);"></a></li>-->
        <!--<li class="action-item" id="mail"><a href="javascript:void(0);"></a></li>-->
        <!--<li class="action-item" id="print"><a href="javascript:void(0);"></a></li>-->
    </ul>
</div>

<br clear="all" />

<div id="report">
    <iframe src=""></iframe>
</div>
