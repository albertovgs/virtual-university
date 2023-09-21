<form id="form_comments_edit">
  <div class="input-group">
    <input type="text" hidden name="inpCom" id="inpCom" value="<?= $comment->id_comment; ?>">
    <input type="text" name="inpCont" id="inpCont" placeholder="Write a comment..." class="form-control"
      value="<?= $comment->content_comment; ?>">
    <span class="input-group-append">
      <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane"></i>send</button>
    </span>
  </div>
</form>