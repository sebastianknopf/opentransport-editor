<div class="box-tools pull-right">
    <button type="button" class="btn btn-box-tool">
        <a href="<?= (isset($_REDIRECT) && $_REDIRECT != null) ? $_REDIRECT : $this->Url->build(['action' => 'index']) ?>">
            <i class="fa fa-times"></i>
        </a>
    </button>
</div>