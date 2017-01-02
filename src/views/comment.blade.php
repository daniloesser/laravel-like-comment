<?php
$GLOBALS['commentDisabled'] = "";
if(!Auth::check())
    $GLOBALS['commentDisabled'] = "disabled";
$GLOBALS['commentClass'] = -1;
?>
<div class="laravelComment" id="laravelComment-{{ $comment_item_id }}">
    <h4 class="ui dividing header">Coment&aacute;rios e Avalia&ccedil;&otilde;es</h4>
    <div class="ui threaded comments" id="{{ $comment_item_id }}-comment-0">
        <form class="ui laravelComment-form form" id="{{ $comment_item_id }}-comment-form" data-parent="0" data-item="{{ $comment_item_id }}">
            <div class="field">
                <textarea id="0-textarea" rows="2" {{ $GLOBALS['commentDisabled'] }} placeholder="Como foi sua experiência? Compartilhe conosco!"></textarea>
                @if(!Auth::check())
                    <small>É preciso estar logado para realizar avaliações.</small>
                @endif
            </div>
            <input type="submit" class="ui basic small submit button" value="Salvar" {{ $GLOBALS['commentDisabled'] }}>
        </form>
<?php
$GLOBALS['commentVisit'] = array();

function dfs($comments, $comment){
    $GLOBALS['commentVisit'][$comment->id] = 1;
    $GLOBALS['commentClass']++;
?>
    <div class="comment show-{{ $comment->item_id }}-{{ (int)($GLOBALS['commentClass'] / 5) }}" id="comment-{{ $comment->id }}">
        <a class="avatar">
            <img src="{{ $comment->avatar }}">
        </a>
        <div class="content">
            <a class="author" url="{{ $comment->url or '' }}"> {{ $comment->name }} </a>
            <div class="metadata">
                <span class="date">em <i>{!! Carbon\Carbon::parse($comment->created_at)->format('d/m/Y à\s h:i:s') !!}</i></span>
            </div>
            <div class="text">
                {{ $comment->comment }}
            </div>
            <div class="actions grouped">
                {{ \risul\LaravelLikeComment\Controllers\CommentController::viewLike('comment-'.$comment->id) }}
                @if($comment->user_id != Auth::user()->id)
                    <a class="{{ $GLOBALS['commentDisabled'] }} reply reply-button btn btn-outline btn-xs " data-toggle="{{ $comment->id }}-reply-form" title="Responder"><i class="large icon reply"></i></a>
                @else
                    <a class="delete delete-button btn btn-outline btn-xs" data-id="{{ $comment->id }}" data-item-type="comment" title="Apagar"><i class="large icon trash"></i></a>
                @endif

            </div>

            <form id="{{ $comment->id }}-reply-form" class="ui laravelComment-form form" data-parent="{{ $comment->id }}" data-item="{{ $comment->item_id }}" style="display: none;">
                <div class="field">
                    <textarea id="{{ $comment->id }}-textarea" rows="2" {{ $GLOBALS['commentDisabled'] }}></textarea>
                    @if(!Auth::check())
                        <small>É preciso estar logado para realizar avaliações.</small>
                    @endif
                </div>
                <input type="submit" class="ui basic small submit button" value="Salvar" {{ $GLOBALS['commentDisabled'] }}>
            </form>
        </div>
        <div class="comments" id="{{ $comment->item_id }}-comment-{{ $comment->id }}">
<?php
    foreach ($comments as $child) {
        if($child->parent_id == $comment->id && !isset($GLOBALS['commentVisit'][$child->id])){
            dfs($comments, $child);
        }
    }
    echo "</div>";
    echo "</div>";
}

$comments = \risul\LaravelLikeComment\Controllers\CommentController::getComments($comment_item_id);
foreach ($comments as $comment) {
    if(!isset($GLOBALS['commentVisit'][$comment->id])){
        dfs($comments, $comment);
    }
}
?>
    </div>
</div>