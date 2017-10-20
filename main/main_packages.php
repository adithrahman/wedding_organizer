
    <div class="container">
      <div class="jumbotron">

        <div>

          <p>Buat paket terlebih dahulu, lalu tambahkan paket ke fasilitas</p>
          <!-- Nav tabs -->
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#paket" aria-controls="paket" role="tab" data-toggle="tab">Paket</a></li>
            <li role="presentation"><a href="#fasilitas" aria-controls="fasilitas" role="tab" data-toggle="tab">Fasilitas</a></li>
          </ul>

          <!-- Tab panes -->
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="paket">

              <div class="container-narrow">
                <button class="btn btn-default" id="btn-pckg" data-toggle="modal" data-target="#mdl_pckg" data-title="Tambah paket">Tambah</button>
                <table id="tbl-pckg" class="table table-striped table-bordered" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Paket</th>
                      <th>Kapasitas</th>
                      <th>Harga</th>
                      <th width='80px'>Actions</th>
                    </tr> 
                  </thead>

                  <tbody>

                    <?php
                      $email = $_SESSION['user'];
                      $id = $db->getWOId($email);
                      $data = $db->getWOPackageOnTable($id);
                    ?>

                  </tbody>
                </table>
              </div>

            </div>
            <div role="tabpanel" class="tab-pane" id="fasilitas">


                <div class="container-narrow">
                  <button class="btn btn-default" id="btn-fasilitas" data-toggle="modal" data-target="#mdl_fasilitas" data-title="Tambah fasilitas">Tambah</button>

                    <table id="tbl-fasilitas" class="table table-striped table-bordered" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>Paket</th>
                          <th>Fasilitas</th>
                          <th>Item</th>
                          <th>Total</th>
                          <th width='80px'>Actions</th>
                        </tr>
                      </thead>

                      <tbody>

                          <?php
                            $email = $_SESSION['user'];
                            $id = $db->getWOId($email);
                            $data = $db->getWOFacilityOnTable($id);
                          ?>

                    </tbody>
                  </table>
                </div>

            </div>
          </div> <!-- Tab panes -->

        </div>

      </div>
    </div> <!-- Jumbotron -->


    <!-- MODAL !!! -->
    <!-- modal paket -->
    <div class="modal fade" id="mdl_pckg" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="height:1em"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" style="color:#555">Tambah paket</h4>
          </div>
          <div class="modal-body" style="color:#555">
            <form class="form-horizontal" id="pckg-form">
              <div class="form-group">
                <label for="recipient-name" class="col-sm-3 control-label" style="color:#555">Paket</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="pckg-name" id="pckg-name" style="color:#555" required autocomplete="off">
                </div>
              </div>
              <div class="form-group">
                <label for="recipient-name" class="col-sm-3 control-label" style="color:#555">Kapasitas</label>
                <div class="col-sm-9">
                  <input type="number" class="form-control" name="pckg-cpct" id="pckg-cpct" style="color:#555" required autocomplete="off">
                </div>
              </div>
              <div class="form-group">
                <label for="recipient-name" class="col-sm-3 control-label" style="color:#555">Harga</label>
                <div class="col-sm-9">
                  <div class="input-group">
                    <span class="input-group-addon">Rp</span>
                    <input type="text" class="form-control" name="pckg-price" id="pckg-price" style="color:#555" required autocomplete="off">
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" id="close-pckg" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="submit-pckg" name="submit-pckg">Add</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

        <!-- MODAL !!! -->
        <!-- modal paket -->
        <div class="modal fade" id="mdl_pupdate" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="height:1em"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="color:#555">Edit paket</h4>
              </div>
              <div class="modal-body" style="color:#555">
                <form class="form-horizontal" id="pupdate-form">
                  <div class="form-group">
                    <label for="recipient-name" class="col-sm-3 control-label" style="color:#555">Paket</label>
                    <div class="col-sm-9">
                      <input type="text" name="pupdate-oid" id="pupdate-oid" value="-" hidden=""/>
                      <input type="text" name="pupdate-oname" id="pupdate-oname" value="-" hidden=""/>
                      <input type="text" class="form-control" name="pupdate-name" id="pupdate-name" style="color:#555" required autocomplete="off">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="recipient-name" class="col-sm-3 control-label" style="color:#555">Kapasitas</label>
                    <div class="col-sm-9">
                      <input type="number" class="form-control" name="pupdate-cpct" id="pupdate-cpct" style="color:#555" required autocomplete="off">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="recipient-name" class="col-sm-3 control-label" style="color:#555">Harga</label>
                    <div class="col-sm-9">
                      <div class="input-group">
                        <span class="input-group-addon">Rp</span>
                        <input type="text" class="form-control" name="pupdate-price" id="pupdate-price" style="color:#555" required autocomplete="off">
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="close-pupdate" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="submit-pupdate" name="submit-pupdate">SAVE</button>
              </div>
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    <!-- modal fasilitas -->
    <div class="modal fade" id="mdl_fasilitas" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="height:1em"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" style="color:#555">Tambah fasilitas</h4>
          </div>
          <div class="modal-body" style="color:#555">
            <form class="form-horizontal" id="fasilitas-form">
              <div class="form-group">
                <label for="recipient-name" class="col-sm-3 control-label" style="color:#555">Paket</label>
                <div class="col-sm-9">
                  <select name="fasilitas-paket" id="fasilitas-paket" class="form-control">
                    <?php
                      $email = $_SESSION['user'];
                      $id = $db->getWOId($email);
                      $data = $db->getWOPackageOption($id);
                    ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="recipient-name" class="col-sm-3 control-label" style="color:#555">Fasilitas</label>
                <div class="col-sm-9">
                  <select name="fasilitas-tipe" id="fasilitas-tipe" class="form-control">
                    <option value="CATERING">CATERING</option>
                    <option value="RIAS">RIAS</option>
                    <option value="HIBURAN">HIBURAN</option>
                    <option value="DEKORASI">DEKORASI</option>
                    <option value="HOTEL">HOTEL</option>
                    <option value="DOKUMENTASI">DOKUMENTASI</option>
                    <option value="TRANSPORTASI">TRANSPORTASI</option>
                    <option value="GEDUNG">GEDUNG</option>
                    <option value="UNDANGAN">UNDANGAN</option>
                    <option value="SOUVENIR">SOUVENIR</option>
                    <option value="JASA-LAIN">JASA-LAIN</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="recipient-name" class="col-sm-3 control-label" style="color:#555">Item</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="fasilitas-item" id="fasilitas-item" style="color:#555" required autocomplete="off">
                </div>
              </div>
              <div class="form-group">
                <label for="recipient-name" class="col-sm-3 control-label" style="color:#555">Total</label>
                <div class="col-sm-9">
                  <input type="number" class="form-control" name="fasilitas-total" id="fasilitas-total" style="color:#555" required autocomplete="off">
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" id="close-fasilitas" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="submit-fasilitas" name="submit-fasilitas">Add</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

        <!-- modal fasilitas -->
        <div class="modal fade" id="mdl_fasilitasu" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="height:1em"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="color:#555">Edit fasilitas</h4>
              </div>
              <div class="modal-body" style="color:#555">
                <form class="form-horizontal" id="fasilitasu-form">
                  <div class="form-group">
                    <label for="recipient-name" class="col-sm-3 control-label" style="color:#555">Paket</label>
                    <div class="col-sm-9">
                      <input type="text" name="fasilitasu-paketo" id="fasilitasu-paketo" value="-" hidden=""/>
                      <select name="fasilitasu-paket" id="fasilitasu-paket" class="form-control">
                        <?php
                          $email = $_SESSION['user'];
                          $id = $db->getWOId($email);
                          $data = $db->getWOPackageOption($id);
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="recipient-name" class="col-sm-3 control-label" style="color:#555">Fasilitas</label>
                    <div class="col-sm-9">
                      <select name="fasilitasu-tipe" id="fasilitasu-tipe" class="form-control">
                        <option value="CATERING">CATERING</option>
                        <option value="RIAS">RIAS</option>
                        <option value="HIBURAN">HIBURAN</option>
                        <option value="DEKORASI">DEKORASI</option>
                        <option value="HOTEL">HOTEL</option>
                        <option value="DOKUMENTASI">DOKUMENTASI</option>
                        <option value="TRANSPORTASI">TRANSPORTASI</option>
                        <option value="GEDUNG">GEDUNG</option>
                        <option value="UNDANGAN">UNDANGAN</option>
                        <option value="SOUVENIR">SOUVENIR</option>
                        <option value="JASA-LAIN">JASA-LAIN</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="recipient-name" class="col-sm-3 control-label" style="color:#555">Item</label>
                    <div class="col-sm-9">
                      <input type="text" name="fasilitasu-itemo" id="fasilitasu-itemo" value="-" hidden=""/>
                      <input type="text" class="form-control" name="fasilitasu-item" id="fasilitasu-item" style="color:#555" required autocomplete="off">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="recipient-name" class="col-sm-3 control-label" style="color:#555">Total</label>
                    <div class="col-sm-9">
                      <input type="number" class="form-control" name="fasilitasu-total" id="fasilitasu-total" style="color:#555" required autocomplete="off">
                    </div>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="close-fasilitasu" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="submit-fasilitasu" name="submit-fasilitasu">SAVE</button>
              </div>
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->


        <link rel="stylesheet" href="assets/css/main_profile.css" />
