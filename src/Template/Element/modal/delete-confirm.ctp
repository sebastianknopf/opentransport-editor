<div class="modal fade" id="delete-confirm" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><?= __('Confirmation') ?></h4>
            </div>
            <div class="modal-body">
                <p><?= __('Are you sure you want to delete this item? This cannot be undone!') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>
                <button type="button" class="btn btn-danger" id="confirm"><?= __('Delete') ?></button>
            </div>
        </div>
    </div>
</div>
<?php $this->append('script') ?>
<script>
    $(document).ready(function () {
        $('#delete-confirm').on('show.bs.modal', function (e) {
            $message = $(e.relatedTarget).attr('data-message');
            $(this).find('.modal-body p').text($message);

            $title = $(e.relatedTarget).attr('data-title');
            $(this).find('.modal-title').text($title);

            $(this).find('#confirm').on('click', function () {
                $href = $(e.relatedTarget).attr('href');

                $post = $('<form method="POST">');
                $post.attr('action', $href);

                $('body').append($post);
                $post.submit();
            });
        });
    })
</script>
<?php $this->end() ?>