{{-- QUICK ADD MODAL --}}
<div class="modal fade" id="quickAddModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content p-3">
      <button type="button" class="btn-close ms-auto me-2 mt-2" data-bs-dismiss="modal" aria-label="Đóng"></button>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-lg-5">
            <img id="qa-image" src="" class="img-fluid border rounded" alt="">
          </div>
          <div class="col-lg-7">
            <h5 id="qa-name" class="mb-1"></h5>
            <div class="d-flex align-items-baseline gap-2 mb-2">
              <span id="qa-price" class="h5 text-danger mb-0"></span>
              <small id="qa-oldprice" class="text-muted text-decoration-line-through"></small>
              <span id="qa-sale" class="badge bg-success d-none"></span>
            </div>

            <form id="qa-form">
              @csrf
              <input type="hidden" name="product_id" id="qa-product-id">
              <input type="hidden" name="variant_id" id="qa-variant-id">

              <div class="row g-2">
                <div class="col-md-6">
                  <label class="form-label">Màu</label>
                  <select class="form-select" id="qa-color"></select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Size</label>
                  <select class="form-select" id="qa-size"></select>
                </div>
              </div>

              <div class="row g-2 mt-2">
                <div class="col-md-6">
                  <label class="form-label">Số lượng</label>
                  <input type="number" name="quantity" id="qa-qty" class="form-control" value="1" min="1">
                </div>
                <div class="col-md-6 d-flex align-items-end">
                  <div id="qa-stock" class="small text-muted"></div>
                </div>
              </div>

              <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                  <i class="icon-basket-loaded"></i> Thêm vào giỏ
                </button>
                <a id="qa-detail-link" href="#" class="btn btn-outline-secondary">Xem chi tiết</a>
              </div>
            </form>

            <div id="qa-msg" class="small mt-2"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>