<div id="orders-modal" class="modal hide fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="orders-modal-label" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 id="orders-modal-header"></h3>
    </div>
    <div id="orders-modal-body" class="modal-body">

    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal">Continue Shopping</a>
        <?php
        echo $this->Html->link('View Cart', '/cart', array('class' => 'btn btn-primary'));
        ?>
    </div>
</div>
<?php
$js = <<<EOF
$('#OrderAddForm').submit(function(event) {
    event.preventDefault();
    var form = $(this);
    var modal = $('#orders-modal');
    $.ajax({
        type: 'POST',
        dataType: 'html',
        beforeSend: function () {
            form.children('input[type="submit"]').prop('disabled', true);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            form.children('input[type="submit"]').prop('disabled', false);
            $('#orders-modal-header').html('An Error has Occurred');
            $('#orders-modal-body').html('<p class="alert alert-error">An error has occurred. Please contact customer service if the problem persists.</p>');
            $('#orders-modal').modal();
        },
        success: function (data, textStatus) {
            form.children('input[type="submit"]').prop('disabled', false);
            $('#cart-status').html(data);
            $('#orders-modal-header').html('Items Added to Order');
            $('#orders-modal-body').html('<p class="alert alert-success">Item(s) have been added to your cart</p>');
            $('#orders-modal').modal();
        },
        url: form.attr('action'),
        data: form.serialize()
    });
});
EOF;
$this->Js->buffer($js);
?>

