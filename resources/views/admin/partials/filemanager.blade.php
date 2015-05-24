    <div class="filemanager">
        <div class="buttons">
            <button type="button" class="btn btn-default"  onclick="$('.filemanager .upload-file').show(); $('.filemanager .buttons').hide();"><i class="fa fa-plus-circle"></i> Hochladen</button>
            <button type="button" class="btn btn-default" onclick="$('.filemanager .new-dir').show(); $('.filemanager .buttons').hide();"><i class="fa fa-file-o"></i> Neuer Ordner</button>
            <button type="button" class="btn btn-success pull-right" onclick="window.location.href='{{ URL::to($routeUrl,array('dirId'=>$currentId)) }}?sync=1';"><i class="fa fa-repeat"></i> Aktualisieren</button>
        </div>
        <form action="{{ URL::to($routeUrl) }}" method="post" enctype="multipart/form-data">
            <div class="upload-file input-group" style="display: none;">
                <label for="filemanagerUpload"></label>
                <input type="file" name="uploadedFile" id="filemanagerUpload"/>
                <span class="input-group-btn">
                    <button class="btn btn-primary" type="button" onclick="this.form.submit();">Hochladen</button>
                </span>
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button" onclick="$('.filemanager .upload-file').hide(); $('.filemanager .buttons').show();">Abbrechen</button>
                </span>
            </div>
            <input type="hidden" name="dirId" value="{{ $currentId }}"/>
            <input type="hidden" name="action" value="upload"/>
        </form>
        <form action="{{ URL::to($routeUrl) }}" method="post">
            <div class="input-group new-dir" style="display: none;">
                <input type="text" class="form-control" name="folderName" placeholder="Ordnername">
                <span class="input-group-btn">
                    <button class="btn btn-primary" type="button" onclick="this.form.submit();">Anlegen</button>
                </span>
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button" onclick="$('.filemanager .new-dir').hide(); $('.filemanager .buttons').show();">Abbrechen</button>
                </span>
            </div>
            <input type="hidden" name="dirId" value="{{ $currentId }}"/>
            <input type="hidden" name="action" value="newDir"/>
        </form>
        <ol class="breadcrumb">
            @foreach($parents as $parent)
            <li><a href="{{ URL::to($routeUrl, array('dirId'=>$parent->id)) }}?{{ http_build_query($params) }}">{{ $parent->title }}</a></li>
            @endforeach
            <li class="active">{{ $dir->title }}</li>
        </ol>
        @if(!count($dir->children()))
            <div class="jumbotron">Der Ordner ist leer</div>
        @else
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>Datei</th>
                        <th>Datum</th>
                        <th>Aktion</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($dir->children() as $file)
                    @if($file->isDir())
                        <?php $url = URL::to($routeUrl, array('dirId'=>$file->id)) ?>
                        <?php $url .= '?' . http_build_query($params) ?>
                        <?php $icon = 'fa-folder-o' ?>
                        <?php $class = '' ?>
                    @else
                        <?php $url = $file->url ?>
                        <?php $icon = 'fa-file-o' ?>
                        <?php $class = $linkClass ?>
                    @endif
                    <tr>
                        <td class="thumb">
                            <a href="{{ $url }}" class="{{ $class }}">
                            @if(starts_with($file->getMimeType(),'image/'))
                                <div class="crop" style="background-image: url('{{ $file->url }}');" data-id="{{ $file->id }}"></div>
                            @else
                                <i class="fa {{ $icon }}"></i>
                            @endif
                            </a>
                        </td>
                        <td><a href="{{ $url }}" class="{{ $class }}" data-id="{{ $file->id }}">{{ $file->title }}</a></td>
                        <td>{{ $file->updated_at }}</td>
                        <td>?</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
    <div class="modal fade" id="upload" tabindex="-1" role="dialog" aria-labelledby="uploadtitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="uploadtitle">Datei hochladen</h4>
                </div>
                <div class="modal-body">
                Datei hochladen
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
                    <!-- button type="button" class="btn btn-primary">Save changes</button -->
                </div>
            </div>
        </div>
    </div> 