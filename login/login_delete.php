<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title fs-5" id="staticBackdropLabel">!~ Warning ~!!</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>정말 삭제 하시겠습니까?</p>
        <form method='POST' action='login_process.php?id=<?= $filtered['id']  ?>'>
          <input type='hidden' name='id' id='id'>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">취소</button>
        <button type="button" class="btn btn-danger" name='del_btn' id='del_btn'>삭제</button>
      </div>
    </div>
  </div>
</div>