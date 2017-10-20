


    <div class="container">
      <div class="jumbotron">

        <div class="table-responsive">
        <?php
            $email = $_SESSION['user'];
            $id = $db->getWOId($email);
            $data = $db->getWOJobsOnPanel($id);
        ?>
        </div>

      </div>
    </div>

    <link rel="stylesheet" href="assets/css/main_profile.css" />

<script type="text/javascript">
    $(document).ready(function(){
        $('select').on('change', function() {
            var idx = $('select').index(this);
            var sid = this.id;
            var ids = sid.substring(9,sid.length);
            var idi = '#in-pkt-'+ids;
            //alert(idi);
            $(idi).val(this.value)
            //document.getElementsByName("in-pkt")[idx].value = this.value;
            //var str=$(this).val(); // On Change get the option value
            //$('input[name="in-pkt"]').val(this.value);
            //$('input.in-pkt').val(this.value);
            //$("[name^='in-pkt']").val(this.value);
        });             
    });      
</script>
