
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {

    $('.options').tooltip({
        offset: [10, 2],
        position: ['bottom', 'center']
    }).dynamic({ bottom: { direction: 'down', bounce: true } });

    var ciclo_ini = '<?php echo $data['ciclo_ini']; ?>';
    var ciclo_fin = '<?php echo $data['ciclo_fin']; ?>';

    var id_cliente = '<?php echo $data['id_cliente']; ?>';

    //var ids = '';

    function csmo_refresh() {

        $('#graph-container').load('/csmo-hist/graph/' +
            id_cliente + '/' + ciclo_ini + '/' + ciclo_fin);

        $('#ultimos-consumos').load('/csmo-hist/last/' +
                id_cliente + '/' + ciclo_ini + '/' + ciclo_fin);

        $('#csmo-actual').load('/csmo-hist/curr/' + id_cliente);
        $('#csmo-promedio').load('/csmo-hist/avg/' + id_cliente);
    }

    $('#ciclos-container #slider-range').slider({
        range: true,
        min: 201101,
        max: 201108,
        step: 1,
        values: [ ciclo_ini, ciclo_fin ],
        slide: function(event, ui) {
            ciclo_ini = ui.values[0];
            ciclo_fin = ui.values[1];
        },
        stop: function(event, ui) {
            if (id_cliente != '') {
                csmo_refresh();
            }
        }
    });

    <?php if( $data['usrtype'] == 'corp' ): ?>
    $('.options').click(function() {
        id_cliente = $(this).text().trim();
        csmo_refresh();
    });
    <?php endif; ?>

    if (id_cliente != '') {
        csmo_refresh();
    }

    $('#action-btns #items #excel').click(function() {
        <?php if( $data['usrtype'] !== 'corp' ): ?>
        var v_url = '/csmo-hist/excel' + '/' +
            id_cliente + '/' + ciclo_ini + '/' + ciclo_fin;
        <?php else: ?>
        var v_url = '/csmo-hist/excel' + '/' +
            id_grupo + '/' + ciclo_ini + '/' + ciclo_fin;
        <?php endif; ?>
        $('#report iframe').src(v_url);
    });

});
</script>

<div id="title-container">
    <div class="bordes3alt title sombras1alt">consumos hist&Oacute;ricos</div>
</div>

<div class="wrapper">

    <div id="ciclos-container" class="box bordes1alt sombras1alt">
        <div id="slider-range" class="bordes1alt"></div>
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
                    <li class="separator">&nbsp;&nbsp;</li>
                    <?php endforeach; ?>

                </ul>
            </li>

            <!--<li id="todos">
                    <a href="javascript:void(0);">TODOS</a>
                    <br /><input type="checkbox" />
                </li>-->

            <li id="right-btn" class="boton"><a href="javascript:void(0);"></a></li>

        </ul>
    </div>
    <?php else: ?>
        <br clear="all" />
    <?php endif; ?>

    <div id="graph-container" class="bordes4">
        <img src="<?php echo $data['graph']; ?>" width="100%" height="100%" />
    </div>

    <div id="data-container">

        <div id="box-consumos">
            <div class="top-bar bordes2alt sombras1alt"><p>ultimos consumos</p></div>
            <div class="box bordes3alt sombras1alt">
                <div id="ultimos-consumos"></div>
            </div>
        </div>

        <div id="box-actuales">

            <div id="box-actual">
                <div class="top-bar bordes2alt sombras1alt"><p>consumo actual</p></div>
                <div class="box bordes3alt sombras1alt">
                    <div id="csmo-actual"></div>
                </div>
            </div>

            <br clear="all" />

            <div id="box-promedio">
                <div class="top-bar bordes2alt sombras1alt"><p>promedio</p></div>
                <div class="box bordes3alt sombras1alt">
                    <p id="csmo-promedio"></div>
                </div>
            </div>

        </div>

    </div>

    <!--<div id="action-btns">
        <ul id="items">
            <li class="action-item" id="excel"><a href="javascript:void(0);"></a></li>
            <li class="action-item" id="pdf"><a href="javascript:void(0);"></a></li>
            <li class="action-item" id="mail"><a href="javascript:void(0);"></a></li>
            <li class="action-item" id="print"><a href="javascript:void(0);"></a></li>
        </ul>
    </div>-->

</div>

<div id="report">
    <iframe src=""></iframe>
</div>
