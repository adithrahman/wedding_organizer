

    <div class="container">
      <div class="jumbotron">

        <div>

          <!-- Nav tabs -->
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#wo" aria-controls="wo" role="tab" data-toggle="tab">WO</a></li>
            <li role="presentation"><a href="#client" aria-controls="client" role="tab" data-toggle="tab">Client</a></li>
          </ul>

          <!-- Tab panes -->
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="wo">

              <div class="container-narrow">
                <table id="tbl-adwo" class="table table-striped table-bordered" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>WO name</th>
                      <th>Owner</th>
                      <th>Email</th>
                      <th>Phone</th>
                      <th>Address</th>
                      <th>Deskripsi</th>
                      <th>Created</th>
                      <th>Approved</th>
                      <th width='80px'>Actions</th>
                    </tr>
                  </thead>

                  <tbody>

                    <?php
                      $email = $_SESSION['user'];
                      $data = $db->adminGetWOOnTable();
                    ?>

                  </tbody>
                </table>
              </div>

            </div>

            <div role="tabpanel" class="tab-pane" id="client">
              <div class="container-narrow">

                    <table id="tbl-adclient" class="table table-striped table-bordered" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Gender</th>
                          <th>Phone</th>
                          <th>Address</th>
                          <th>Created</th>
                          <th>Active</th>
                          <th width='80px'>Actions</th>
                        </tr>
                      </thead>

                      <tbody>

                        <?php
                          $email = $_SESSION['user'];
                          $data = $db->adminGetClientOnTable();
                        ?>

                      </tbody>
                    </table>
                  </div>

                </div>
            </div> <!-- Tab panes -->

          </div>

        </div>
      </div> <!-- Jumbotron -->


      <link rel="stylesheet" href="assets/css/main_profile.css" />
