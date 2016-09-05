<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">@lang('ems::forms.actions.close.title')</span></button>
    <h4 class="modal-title">@lang('ems::mail.base.mailing-preview')</h4>
</div>

<?
    $previousLink = "openModalIframe('" .  $paginator->previousPageUrl()  . "')";
    $nextLink     = "openModalIframe('" .  $paginator->nextPageUrl()  . "')";
    $directLink = "openModalIframe('" . URL::current() . "?page=' + $(this).val())";
    $sendUrl = URL::route('mail-configurations.preview-mailing.send', [$config->getId(), $paginator->currentPage()]);
    $successMessage = Lang::get('ems::mail.send-preview-mail.success',['recipient'=>$previewRecipient]);
    $errorMessage = Lang::get('ems::mail.send-preview-mail.error',['recipient'=>$previewRecipient]);
    $sendLink = "sendTestMailFromMailingPreview('$sendUrl', '$successMessage', '$errorMessage')";
?>

<div class="modal-body no-padding"> <!-- style="width: 800px; height: 800px; " -->

    <div class="mailbox-controls with-border text-center">
        <!-- /.btn-group -->
        <button type="button" onclick="{{ $nextLink }}" class="btn btn-default btn-sm pull-right {{ $paginator->nextPageUrl() ? '' : 'disabled' }}" data-toggle="tooltip" title="" data-original-title="@lang('ems::mail.base.next-message')">
            <i class="fa fa-chevron-right"></i></button>
        <button type="button" onclick="{{ $previousLink }}" class="btn btn-default btn-sm pull-right {{ $paginator->previousPageUrl() ? '' : 'disabled' }}" data-toggle="tooltip" title="" data-original-title="@lang('ems::mail.base.previous-message')">
            <i class="fa fa-chevron-left"></i></button>
        
        
        <div style="clear: both;"></div>
    </div><!-- /.mailbox-controls -->
    <div class="mailbox-read-info">
        <h3>{{ $message->getSubject() }}</h3>
        <h5><span title="To">@lang('ems::mail.base.To'):</span> {{ Scaffold::shortName($message->recipient()) }}
                  <span class="mailbox-read-time pull-right">15 Feb. 2016 11:03 PM</span></h5>
    </div>
    <iframe id="mail-preview" style="width: 100%; height: 500px; border: none;">
    </iframe>
    <div class="mailbox-controls with-border text-center">
    </div>
    <div class="mailbox-controls with-border text-center">
        <button type="button" onclick="{{ $previousLink }}" class="btn btn-default btn-sm pull-left {{ $paginator->previousPageUrl() ? '' : 'disabled' }}" data-toggle="tooltip" title="" data-original-title="@lang('ems::mail.base.previous-message')">
            <i class="fa fa-chevron-left"></i></button>

        <form class="form-inline" style="display: inline;">
            <div class="form-group">
                <input type="page" title="@lang('ems::mail.base.page-input-help')" onchange="{{ $directLink }}" value="{{ $paginator->currentPage() }}" class="form-control" id="mail-preview-page" style="width: 50px;">
                /
                <input type="_total_pages" title="@lang('ems::mail.base.total-input-help')" value="{{ $paginator->lastPage() }}" class="form-control" disabled="disabled" style="width: 50px;">
            </div>
        </form>
        <button type="button" onclick="{{ $nextLink }}" class="btn btn-default btn-sm pull-right {{ $paginator->nextPageUrl() ? '' : 'disabled' }}" data-toggle="tooltip" title="" data-original-title="@lang('ems::mail.base.next-message')">
            <i class="fa fa-chevron-right"></i></button>
        <div style="clear: both;"></div>
    </div><!-- /.mailbox-controls -->
    

</div><!-- /.modal-body -->
<div class="modal-footer">
    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">@lang('ems::forms.actions.close.title')</button>
    <button type="button" onclick="{{ $sendLink }}" class="btn btn-primary">@lang('ems::mail.base.send-this-as-test')</button>
</div>

<script>

var doc = document.getElementById('mail-preview').contentWindow.document;
doc.open();
doc.write({!! json_encode($message->getBody()) !!});
doc.close();

</script>
