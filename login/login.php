<!-- 로그인 모달 -->
<div class="modal fade" id="loginModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm" style="max-width: 1200px;">
    <div class="modal-content" style="height: 400px;">
      <div class="modal-header" style="height: 50px;">
        <h6 class="modal-title" id="loginModalLabel">AMESS 로그인</h6>
        <button type="button" style="font-size: .65rem;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="height: 300px; overflow-y: auto;">
        <div class="d-flex align-items-center justify-content-center" style="height: 300px;">
          <section class="w-100 m-auto shadow-lg p-2 rounded-4 container text-center" style="height: 300px;">
            <div class="py-1" style="margin:10px">
              <h6 class="fw-bold">로그인</h6>
            </div>
            <form action='login/login_server.php' method='post' autocomplete="off">
              <div class="col col-xl-10 m-auto">
                <div class="form-floating mb-3">
                  <input type="text" class="form-control" name="id" id='id' placeholder="ID" style="height: 55px !important;">
                  <label for="nameInput">ID</label>
                </div>
              </div>
              <div class="col col-xl-10 m-auto">
                <div class="form-floating">
                  <input type="password" class="form-control" name="password" id='pw' placeholder="Password" style="height: 55px !important;">
                  <label for="floatingPassword">Password</label>
                </div>
              </div>
              <div class="g-4 m-4 my-3">
                <button type='button' class="btn btn-outline-warning btn-sm" style="--bs-btn-padding-y: .35rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .65rem;"><a href='login_add_new.php' style='text-decoration: none; font-size: .65rem;'>신규등록</a></button>
                <button type="submit" class="btn btn-outline-success btn-sm" style="--bs-btn-padding-y: .35rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .65rem;">확인</button>
              </div>
            </form>
          </section>

          </style>
        </div>
      </div>
    </div>
  </div>
</div>