

    <div class="container">
      <div class="jumbotron">

        <div>


                <table id="tbl-adwo" class="table table-striped table-bordered" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Paket</th>
                      <th>Klien</th>
                      <th>WO</th>
                      <th>Order</th>
                      <th>Complete</th>
                      <th>Status</th>
                      <th>User pay</th>
                      <th>WO pay</th>
                      <th>Pay</th>
                      <th width='80px'>Actions</th>
                    </tr>
                  </thead>

                  <tbody>

                    <?php
                      //$email = $_SESSION['user'];
                      $data = $db->adminGetOrderOnTable();
                    ?>

                  </tbody>
                </table>


          </div>

        </div>
      </div> <!-- Jumbotron -->


          <div class="modal fade" id="mdl_ont" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="height:1em"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" style="color:#555">Change</h4>
                </div>
                <div class="modal-body">
                  <form class="form-horizontal" id="ont-form">
                    <div class="form-group">
                      <label for="recipient-name" class="col-sm-3 control-label" style="color:#555">Complete</label>
                      <div class="col-sm-9">
                        <input type="date" class="form-control" name="ont-complete" id="ont-complete" style="color:#555" required autocomplete="off" placeholder="mm/dd/yyyy">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="recipient-name" class="col-sm-3 control-label" style="color:#555">Status</label>
                      <div class="col-sm-9">
                        <select name="ont-status" id="ont-status" class="form-control">
                          <option value="APPROVE">APPROVE</option>
                          <option value="NO ACTION">NO ACTION</option>
                          <option value="DENIED">DENIED</option>
                          <option value="PENDING">PENDING</option>
                          <option value="COMPLETE">COMPLETE</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="recipient-name" class="col-sm-3 control-label" style="color:#555">User pay</label>
                      <div class="col-sm-9">
                        <select name="ont-upay" id="ont-upay" class="form-control">
                          <option value="LUNAS">LUNAS</option>
                          <option value="BELUM BAYAR">BELUM BAYAR</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="recipient-name" class="col-sm-3 control-label" style="color:#555">WO pay</label>
                      <div class="col-sm-9">
                        <select name="ont-wopay" id="ont-wopay" class="form-control">
                          <option value="LUNAS">LUNAS</option>
                          <option value="SEPARUH">SEPARUH</option>
                          <option value="PENDING">PENDING</option>
                        </select>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" id="close-ont" data-dismiss="modal">Cancel</button>
                  <button type="button" class="btn btn-primary" id="submit-ont" name="submit-addr">Save changes</button>
                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->


      <link rel="stylesheet" href="assets/css/main_profile.css" />
